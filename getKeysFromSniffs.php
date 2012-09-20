<?php

    // @TODO: The $sFolder variable should be a parameter, either from $_GET or $argv
    $sFolder = '/path/to/your/CodeSniffer/Directory';

    $bCommandLine = (php_sapi_name() === PHP_SAPI);

    include_once 'lib/class.CodeSnifferKeyFinder.php';

    $oKeyFinder = new CodeSnifferKeyFinder();
    $oDirectoryIterator = new RecursiveDirectoryIterator($sFolder);
    $aKeys = $oKeyFinder->findKeysInFolder($oDirectoryIterator);

    // @TODO: If we're on the command line, in which case the output should NOT be HTML   BMP/2012/09/20
    foreach($aKeys as $t_sKey)
    {
        echo $bCommandLine?'':'<li>' . $t_sKey . $bCommandLine?'':'</li>' . "\n";
    }

    function getPhpCodeSnifferPath()
    {
        $sFolder = '';
        $sOutput = `pear config-show`;

        if($sOutput === null)
        {
            throw new UnexpectedValueException('PEAR does not seem to be installed or is not reachable from the commandline.');
        }
        else if(strpos($sOutput, 'php_dir') === false)
        {
            throw new UnexpectedValueException('Could not find the PEAR directory in the output from the commandline.');
        }
        else
        {
            $sOutput = substr($sOutput, strpos($sOutput, 'php_dir'));

            $aPath   = preg_split('/\s/', $sOutput, null, PREG_SPLIT_NO_EMPTY);

            if(!is_dir($aPath[1] . '/PHP/CodeSniffer'))
            {
                throw new UnexpectedValueException('Could not find the CodeSniffer directory.');
            }
            else
            {
                $sFolder = $aPath[1] . '/PHP/CodeSniffer';
            }
        }
        return $sFolder;
    }

#EOF
