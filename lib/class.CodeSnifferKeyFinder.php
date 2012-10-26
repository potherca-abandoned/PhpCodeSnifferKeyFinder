<?php

/**
 *
 * PHP CodeSniffer keys are build up like this:
 *
 *     <standard_folder>.<sniff_subfolder>.<sniff_file_without_Sniff_suffix>.<error_name>
 *
 * This class will look for all '*.sniff.php' files in a given directory,
 * and retrieve all the keys that have been registered in those files.
 *
 */
class CodeSnifferKeyFinder
{
    /**
     * @var RecursiveDirectoryIterator
     */
    public $m_oFolder;

    /**
     * @param \RecursiveDirectoryIterator $p_oFolder
     */
    public function setFolder (RecursiveDirectoryIterator $p_oFolder)
    {
        $this->m_oFolder = $p_oFolder;
    }

    /**
     * @return \RecursiveDirectoryIterator
     */
    public function getFolder ()
    {
        return $this->m_oFolder;
    }

    /**
	 * Find all PHP CodeSnifferKeys in a given directory.
	 *
	 * @param RecursiveDirectoryIterator $p_oFolder
	 *
	 * @return array
	 */
	public function findKeysInFolder(RecursiveDirectoryIterator $p_oFolder)
	{
        $this->setFolder($p_oFolder);

        $aKeys = array();

        $recursiveIterator = new RecursiveIteratorIterator($p_oFolder);

        foreach($recursiveIterator as $oFile)
        {
            /** @var SplFileInfo $oFile  */
            $aKeysFromFile = $this->findKeysInFile($oFile);

            if(!empty($aKeysFromFile))
            {
                $aKeys = array_merge($aKeys, array_unique($aKeysFromFile));
            }
        }

	asort($aKeys);

	return $aKeys;
	}

    protected  function findKeysInFile(SplFileInfo $p_oFile)
    {
        $aKeys = array();

        if($this->isValidFile($p_oFile) === true)
        {
            $aPath = explode (DIRECTORY_SEPARATOR, $p_oFile->getPathname());
            $aKeysFromFile = $this->retrieveKeyNamesFromFile ($p_oFile);

                // Building a valid PHP CodeSniffer key in reverse order
            $aKey = array();
            array_unshift($aKey, basename(array_pop($aPath), 'Sniff.php')); // <sniff_file_without_Sniff_suffix>
            array_unshift($aKey, array_pop($aPath));                        // <sniff_subfolder>
            array_pop($aPath);                                              // Skipp the 'Sniffs' folder
            array_unshift($aKey, array_pop($aPath));                        // <standard_folder>

            $sKey = implode ('.', $aKey);

            foreach ($aKeysFromFile as $t_sKey)
            {
                $aKeys[] = $sKey . '.' . $t_sKey;
            }#foreach
        }

        return $aKeys;
    }

    protected function retrieveKeyNamesFromFile(SplFileInfo $p_oFile)
    {
        $aKeys = array();
        $sContents = file_get_contents ($p_oFile->getPathname());

        //     public function addWarning($warning, $stackPtr, $code='', $data=array(), $severity=0)
        //     public function addError($error, $stackPtr, $code='', $data=array(), $severity=0)
        $sRegexp = '!                                                           # START PATTERN
        (?:->\s*                                                                # open NCS (non-capturing subpattern)
            add(?P<SEVERITY>Warning|Error)                                      # Method name
        )                                                                       # close NCS
        \s*\(                                                                   # (
                                                                                # START PARAMETERS
            (?|(?P<MESSAGE>\$[a-z0-9_]+)|[\'"](?P<MESSAGE>.*?)[\'"])            # message OR name of the variable containing the message
            \s*,\s*                                                             # ,
            (.*?)                                                               # unneeded parameter
            \s*,\s*                                                             # ,
            (?|(?P<KEY>\$[a-z0-9_]+)|[\'"](?P<KEY>.*?)[\'"])                    # key OR name of the variable containing the key
            .*                                                                  # unneeded parameter
                                                                                # END PARAMETERS
        \)\s*;                                                                  # )
        !xm';                                                                   # END PATTERN

        if(preg_match_all($sRegexp, $sContents, $aFullMatches, PREG_SET_ORDER) > 0)
        {
            foreach ($aFullMatches as $t_aMatch)
            {
                $sKey = $t_aMatch['KEY'];
                if($sKey{0} === '$')
                {
                    $sFullMatch = $t_aMatch[0];
                    $aContents = explode ("\n", $sContents);
                    foreach ($aContents as $t_iLineNumber => $t_sLine)
                    {
                        if(strpos($t_sLine, $sFullMatch) !== false)
                        {
                            $iLineNumber = $t_iLineNumber;
                            break;
                        }#if
                    }#foreach

                    if(isset($iLineNumber))
                    {
                        // Lets look for that variable!
                        while($iLineNumber > -1) // fallback in case we don't find it
                        {
                            $iLineNumber--;
                            $sLine = $aContents[$iLineNumber];

                            if(preg_match('/function [a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\s*\(.*?/ms', $sLine, $aMatches) > 0)
                            {
                                // We really don't want to keep parsing beyond the boundary of the current function
                                break;
                            }
                            else if(preg_match('#\\' . $sKey . '\s*=\s*(\'|")(?P<KEY>.*?)\1#', $sLine, $aMatches) > 0)
                            {
                                $sKey = $aMatches['KEY'];
                                break;
                            }#if
                        }#while
                    }#if
                }#if

                $aKeys[] = $sKey;
            }#foreach
        }

        return $aKeys;
    }

    protected function isValidFile (SplFileInfo $p_oFile)
    {
        $bValid = false;

        if(
            $p_oFile->getExtension () === 'php'
            && substr ($p_oFile->getBasename ('.php'), -5) === 'Sniff'
        ){
            $bValid = true;
        }

        return $bValid;
    }

}

#EOF