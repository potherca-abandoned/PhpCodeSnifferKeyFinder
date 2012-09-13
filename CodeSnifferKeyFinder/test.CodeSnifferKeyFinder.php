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

	/**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new CodeSnifferKeyFinder;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
	 * @test
     * @covers CodeSnifferKeyFinder::findKeysInFolder
     */
    public function findKeysInFolder()
    {
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
