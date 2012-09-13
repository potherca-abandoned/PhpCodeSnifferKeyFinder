<?php
/**
 *
 */
class CodeSnifferKeyFinderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CodeSnifferKeyFinder
     */
    protected $object;

    public static function setUpBeforeClass()
    {
        include_once 'class.CodeSnifferKeyFinder.php';
    }

    protected function setUp()
    {
        $this->object = new CodeSnifferKeyFinder;
    }

///////////////////////////////////// Tests \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @test
     * @covers   CodeSnifferKeyFinder::findKeysInFolder
     *
     * @@dataProvider provideValidEmptyParameter_For_FindKeysInFolder
     *
     * @param $p_oDirectoryIterator
     */
    public function findKeysInFolder_ExpectsRecursiveDirectoryIterator_WhenCalled($p_oDirectoryIterator)
    {
        $this->object->findKeysInFolder($p_oDirectoryIterator);
    }

    /**
     * @test
     * @covers            CodeSnifferKeyFinder::findKeysInFolder
     *
     * @expectedException PHPUnit_Framework_Error
     *
     * @dataProvider provideInvalidParameter_For_FindKeysInFolder
     *
     * @param $p_mSubject
     */
    public function findKeysInFolder_TriggersError_WhenCalledWithSomethingOtherThanRecursiveDirectoryIterator($p_mSubject)
    {
        $this->object->findKeysInFolder($p_mSubject);
    }

    /**
     * @test
     * @covers   CodeSnifferKeyFinder::findKeysInFolder
     *
     * @@dataProvider provideValidEmptyParameter_For_FindKeysInFolder
     *
     * @param $p_oDirectoryIterator
     */
    public function findKeysInFolder_returnsArray_WhenCalledWithRecursiveDirectoryIterator($p_oDirectoryIterator)
    {
        $aKeysInFolder = $this->object->findKeysInFolder ($p_oDirectoryIterator);
        $this->assertInternalType('array',$aKeysInFolder);
    }

    /**
     * @test
     * @covers   CodeSnifferKeyFinder::findKeysInFolder
     *
     * @@dataProvider provideValidEmptyParameter_For_FindKeysInFolder
     *
     * @param $p_oDirectoryIterator
     */
    public function findKeysInFolder_returnedArrayIsEmpty_WhenCalledWithRecursiveDirectoryIterator($p_oDirectoryIterator)
    {
        $aKeysInFolder = $this->object->findKeysInFolder ($p_oDirectoryIterator);
        $this->assertEmpty($aKeysInFolder);
    }

    /**
     * @test
     * @covers   CodeSnifferKeyFinder::findKeysInFolder
     *
     * @@dataProvider provideValidFilledParameter_For_FindKeysInFolder
     *
     * @param $p_oDirectoryIterator
     *
     * @return array
     */
    public function findKeysInFolder_returnedArrayIsNotEmpty_WhenCalledWithRecursiveDirectoryIteratorThatPointsToSnifferFolder($p_oDirectoryIterator)
    {
        $aKeysInFolder = $this->object->findKeysInFolder($p_oDirectoryIterator);
        $this->assertNotEmpty($aKeysInFolder);

        return array($aKeysInFolder);
    }

    /**
     * @test
     * @covers   CodeSnifferKeyFinder::findKeysInFolder
     *
     * @@dataProvider provideValidFilledParameter_For_FindKeysInFolder
     *
     * @param $p_oDirectoryIterator
     *
     * @return array
     */
    public function findKeysInFolder_returnedKeysMatchPattern_WhenCalledWithRecursiveDirectoryIteratorThatPointsToSnifferFolder($p_oDirectoryIterator)
    {
        $aKeysInFolder = $this->object->findKeysInFolder($p_oDirectoryIterator);
        foreach($aKeysInFolder as $t_sKey)
        {
            // A valid PHP CodeSniffer key should contain the following parts: <standard_folder>.<sniff_subfolder>.<sniff_file_without_Sniff_suffix>.<error_name>
            $this->assertRegExp('#([a-z0-9_]+\.){3}[a-z0-9_]+#ix', $t_sKey);
        }
    }

///////////////////////////////// DataProviders \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function provideValidEmptyParameter_For_FindKeysInFolder()
    {
        return $this->provideRecursiveDirectoryIterator(__DIR__);
    }

    public function provideValidFilledParameter_For_FindKeysInFolder()
    {
        $sFolder = $this->getCodeSnifferDirectory();
        return $this->provideRecursiveDirectoryIterator($sFolder);
    }

    public function provideInvalidParameter_For_FindKeysInFolder()
    {
        return array(
              array(null)
            , array(true)
            , array(false)
            , array(0)
            , array(2)
            , array('foo')
        );
    }

//////////////////////////////// Helper Methods \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    protected function provideRecursiveDirectoryIterator($p_sFolder)
    {
        $sFolder = (string) $p_sFolder;
        $oDirectoryIterator = new RecursiveDirectoryIterator($sFolder);

        return array(
            array($oDirectoryIterator)
        );
    }

    protected function getCodeSnifferDirectory ()
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
            $sOutput = substr($sOutput, strpos ($sOutput, 'php_dir'));
            $aPath = preg_split('/\s/', $sOutput, null, PREG_SPLIT_NO_EMPTY);

            if(! is_dir($aPath[1] . '/PHP/CodeSniffer'))
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