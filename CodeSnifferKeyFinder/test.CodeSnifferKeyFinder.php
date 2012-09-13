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
     * @@dataProvider provideValidParameter_For_FindKeysInFolder
     *
     * @param $p_oDirectoryIterator
     */
    public function findKeysInFolder_ExpectsRecursiveDirectoryIterator_WhenCalled($p_oDirectoryIterator)
    {
        $this->object->findKeysInFolder($p_oDirectoryIterator);
    }

    /**
     * @test
     * @covers   CodeSnifferKeyFinder::findKeysInFolder
     *
     * @@dataProvider provideValidParameter_For_FindKeysInFolder
     *
     * @param $p_oDirectoryIterator
     */
    public function findKeysInFolder_returnsArray_WhenCalledRecursiveDirectoryIterator($p_oDirectoryIterator)
    {
        $this->object->findKeysInFolder($p_oDirectoryIterator);
    }

///////////////////////////////// DataProviders \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
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

    public function provideValidParameter_For_FindKeysInFolder()
    {
        $oDirectoryIterator = new RecursiveDirectoryIterator(__DIR__);
        return array(
            array($oDirectoryIterator)
        );
    }
}
