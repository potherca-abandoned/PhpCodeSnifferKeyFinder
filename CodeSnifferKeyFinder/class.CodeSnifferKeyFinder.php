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
            $sKey = $this->retrieveKeyFromFile($oFile);

            if($sKey !== null) {
                $aKeys[$sKey] = $oFile->getPathname();
            }
        }

		return $aKeys;
	}

    protected function retrieveKeyFromFile(SplFileInfo $p_oFile)
    {
        $sKey = null;

        if($this->isValidFile($p_oFile) === true)
        {
            $aKey = array();

            #$sKey = 'standard_folder.sniff_subfolder.sniff_file_without_Sniff_suffix.error_name';
            $aKey[] = 'error_name';

            $aPath = explode (DIRECTORY_SEPARATOR, $p_oFile->getPathname ());
            var_dump($aPath);
            // Get file name without suffix
            array_unshift($aKey, basename(array_pop($aPath), 'Sniff.php'));
            // Get subfolder
            array_unshift($aKey, array_pop($aPath));
            // Get standard folder
            array_unshift($aKey, array_pop($aPath));

            $sKey = implode ('.', $aKey);
        }

        return $sKey;
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