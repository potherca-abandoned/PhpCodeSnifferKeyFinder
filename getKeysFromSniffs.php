<?php

$sFolder = '/path/to/your/CodeSniffer/Directory';


include_once 'CodeSnifferKeyFinder/class.CodeSnifferKeyFinder.php';

$oKeyFinder = new CodeSnifferKeyFinder();
$oDirectoryIterator = new RecursiveDirectoryIterator($sFolder);
$aKeys = $oKeyFinder->findKeysInFolder($oDirectoryIterator);

var_export($aKeys);

#EOF
