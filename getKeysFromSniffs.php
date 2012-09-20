<?php

    include_once 'lib/class.CodeSnifferKeyFinder.php';
    include_once 'lib/class.CodeSnifferKeyFinderUtilities.php';

    $oKeyFinder = new CodeSnifferKeyFinder();
    $oUtil = new CodeSnifferKeyFinderUtilities();

    $bCommandLine = $oUtil->iscommandLine();

    try
    {
        $sFolder = $oUtil->getFolder();
    }
    catch (InvalidArgumentException $eInvalidArgumentException)
    {
        echo 'ERROR : ' . $eInvalidArgumentException->getMessage() . "\n";
    }


    if(isset($sFolder))
    {
        $oDirectoryIterator = new RecursiveDirectoryIterator($sFolder);
        $aKeys = $oKeyFinder->findKeysInFolder($oDirectoryIterator);

        foreach($aKeys as $t_sKey)
        {
            echo
                  ($bCommandLine?'':'<li>')
                . $t_sKey
                . ($bCommandLine?'':'</li>')
                . "\n"
            ;
        }
    }

#EOF
