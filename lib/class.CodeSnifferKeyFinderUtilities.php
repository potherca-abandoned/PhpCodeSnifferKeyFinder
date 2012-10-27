<?php

class CodeSnifferKeyFinderUtilities
{
    public function isCommandLine()
    {
        return (PHP_SAPI === 'cli');
    }

    public function getFolder()
    {
        $sFolder = null;

        if($this->isCommandLine() === true)
        {
            if(!isset($_SERVER['argv'][1]))
            {
                throw new InvalidArgumentException('This script expects exactly one parameter: The path to the CodeSniffer directory to parse.');
            }
            else
            {
                $sFolder = $_SERVER['argv'][1];
            }
        }
        else
        {
            if(!isset($_GET['dir']))
            {
                throw new InvalidArgumentException(
                    'This script expects a parameter named "dir" to be set with the path to the CodeSniffer directory to parse.'
                        . 'Example: "' . $_SERVER['PHP_SELF'] . '?dir=/path/to/your/CodeSniffer/Directory"');
            }
            else
            {
                $sFolder = $_GET['dir'];
            }
        }

        return $sFolder;
    }

    public function getPhpCodeSnifferPath()
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
}

#EOF