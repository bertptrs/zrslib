<?php
namespace zrslib\tests\unit;

use PHPUnit_Framework_TestCase;

/**
 * Test cases for the ZRS reader class.
 *
 * @author Bert Peters
 */
class ZRSReaderTest extends PHPUnit_Framework_TestCase
{
    public function testGetBuildings()
    {
        $instance = \zrslib\ZRSReader::getInstance();

        $result = $instance->getBuildings();

        $this->assertEquals(64, $result);
    }
}