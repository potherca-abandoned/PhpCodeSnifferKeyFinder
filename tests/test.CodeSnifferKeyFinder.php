<?php
/**
 * @covers CodeSnifferKeyFinder::<!public>
 */
class CodeSnifferKeyFinderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CodeSnifferKeyFinder
     */
    protected $object;

    public static function setUpBeforeClass()
    {
        include_once realpath(__DIR__ . '/../lib') . '/class.CodeSnifferKeyFinder.php';
    }

    protected function setUp()
    {
        $this->object = new CodeSnifferKeyFinder;
    }

///////////////////////////////////// Tests \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @test
     *
     * @covers   CodeSnifferKeyFinder::findKeysInFolder
     *
     * @@dataProvider provideValidEmptyParameter_For_FindKeysInFolder
     *
     * @param $p_oDirectoryIterator
     */
    public function findKeysInFolder_ExpectsRecursiveDirectoryIterator_WhenCalled(RecursiveDirectoryIterator $p_oDirectoryIterator)
    {
        $this->object->findKeysInFolder($p_oDirectoryIterator);
    }

    /**
     * @test
     *
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
     *
     * @covers   CodeSnifferKeyFinder::findKeysInFolder
     *
     * @@dataProvider provideValidEmptyParameter_For_FindKeysInFolder
     *
     * @param $p_oDirectoryIterator
     *
     * @return array
     */
    public function findKeysInFolder_returnsArray_WhenCalledWithRecursiveDirectoryIterator(RecursiveDirectoryIterator $p_oDirectoryIterator)
    {
        $aKeysInFolder = $this->object->findKeysInFolder ($p_oDirectoryIterator);

        $this->assertInternalType('array', $aKeysInFolder);

        return $aKeysInFolder;
    }

    /**
     * @test
     *
     * @covers   CodeSnifferKeyFinder::findKeysInFolder
     *
     * @depends  findKeysInFolder_returnsArray_WhenCalledWithRecursiveDirectoryIterator
     *
     * @param $aKeysInFolder
     */
    public function findKeysInFolder_returnedArrayIsEmpty_WhenCalledWithRecursiveDirectoryIterator($aKeysInFolder)
    {
        $this->assertEmpty($aKeysInFolder);
    }

    /**
     * @test
     *
     * @covers   CodeSnifferKeyFinder::findKeysInFolder
     *
     * @return array
     */
    public function findKeysInFolder_returnedArrayIsNotEmpty_WhenCalledWithRecursiveDirectoryIteratorThatPointsToSnifferFolder()
    {
        $oDirectoryIterator = $this->provideRecursiveDirectoryIterator($this->fetchCodeSnifferDirectory());

        $aKeysInFolder = $this->object->findKeysInFolder($oDirectoryIterator);

        $this->assertNotEmpty($aKeysInFolder);

        return $aKeysInFolder;
    }

    /**
     * @test
     *
     * @covers   CodeSnifferKeyFinder::findKeysInFolder
     *
     * @depends  findKeysInFolder_returnedArrayIsNotEmpty_WhenCalledWithRecursiveDirectoryIteratorThatPointsToSnifferFolder
     *
     * @param $p_aKeysInFolder
     *
     * @return array
     */
    public function findKeysInFolder_returnedKeysMatchPattern_WhenCalledWithRecursiveDirectoryIteratorThatPointsToSnifferFolder(Array $p_aKeysInFolder)
    {
        foreach($p_aKeysInFolder as $t_sKey)
        {
            // A valid PHP CodeSniffer key should contain the following parts: <standard_folder>.<sniff_subfolder>.<sniff_file_without_Sniff_suffix>.<error_name>
            $this->assertRegExp('#([a-z0-9_]+\.){3}[a-z0-9_]+#ix', $t_sKey);
        }

        return $p_aKeysInFolder;
    }

        /**
         * @test
         *
         * @covers   CodeSnifferKeyFinder::findKeysInFolder
         *
         * @depends  findKeysInFolder_returnedKeysMatchPattern_WhenCalledWithRecursiveDirectoryIteratorThatPointsToSnifferFolder
         *
         * @param array $p_aKeysInFolder
         *
         * @return array
         */
    public function findKeysInFolder_returnedArrayContainCorrectKeys_WhenCalledWithRecursiveDirectoryIteratorThatPointsToSnifferFolder(Array $p_aKeysInFolder)
    {
        /*
         * As we would have to write more logic to ascertain the file and folder
         * names we are just checking against the hardcoded values that should
         * be returned when parsing the test sniff folder
         */

        $aExpected = array(
              'TestSniffs.WhiteSpace.DisallowTabIndent.TabsUsed'
            , 'TestSniffs.WhiteSpace.ScopeIndent.Incorrect'
            , 'TestSniffs.Classes.DuplicateClassName.Found'
            , 'TestSniffs.Commenting.Todo.TaskFound'
            , 'TestSniffs.Commenting.Fixme.TaskFound'
        );
        $this->assertEquals($aExpected, $p_aKeysInFolder);
    }

///////////////////////////////// DataProviders \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function provideValidEmptyParameter_For_FindKeysInFolder()
    {
        $sFolder = $this->fetchCodeSnifferDirectory() . '/Sniffs/EmptyFolder';
        $oDirectoryIterator = $this->provideRecursiveDirectoryIterator($sFolder);

        return array(
            array($oDirectoryIterator)
        );
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
        return new RecursiveDirectoryIterator($p_sFolder, RecursiveDirectoryIterator::SKIP_DOTS);
    }

    protected function fetchCodeSnifferDirectory ()
    {
        return __DIR__ . '/TestSniffs';
    }
}

#EOF