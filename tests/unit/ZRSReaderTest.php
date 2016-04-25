<?php

namespace zrslib\tests\unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_TestCase;
use zrslib\ZRSReader;

/**
 * Test cases for the ZRS reader class.
 *
 * @author Bert Peters
 */
class ZRSReaderTest extends PHPUnit_Framework_TestCase
{

    public function testGetBuildings()
    {
        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__.'/searchpage.html')),
        ]);

        $handler = HandlerStack::create($mock);
        $client  = new Client(['handler', $handler]);

        $instance = new ZRSReader($client);

        $result  = $instance->getBuildings();
        $correct = require 'buildings.php';

        $this->assertEquals($correct, $result);
    }

    public function testGetOrganisations()
    {
        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__.'/searchpage.html')),
        ]);

        $handler = HandlerStack::create($mock);
        $client  = new Client(['handler', $handler]);

        $instance = new ZRSReader($client);

        $result = $instance->getOrganisations();
        $correct = require 'organisations.php';

        $this->assertEquals($correct, $result);
    }
}