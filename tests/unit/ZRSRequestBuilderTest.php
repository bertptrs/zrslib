<?php

namespace zrslib\tests\unit;


use zrslib\ZRSRequestBuilder;

class ZRSRequestBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaults()
    {
        $result = (new ZRSRequestBuilder())->build();
        $expected = [
            'day' => date('j'),
            'month' => date('n'),
            'year' => date('Y'),
            'selgebouw' => '_ALL_',
            'res_instantie' => '_ALL_',
            'submit' => 'Uitvoeren',
            'gebruiker' => '',
            'aanvrager' => '',
            'zrssort' => 'aanvangstijd',
            'activiteit' => '',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testWithDate()
    {
        $date = new \DateTime('October 30, 1923');
        $result = (new ZRSRequestBuilder())->withDate($date)->build();
        $expected = [
            'day' => '30',
            'month' => '10',
            'year' => '1923',
            'selgebouw' => '_ALL_',
            'res_instantie' => '_ALL_',
            'submit' => 'Uitvoeren',
            'gebruiker' => '',
            'aanvrager' => '',
            'zrssort' => 'aanvangstijd',
            'activiteit' => '',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testWithBuilding()
    {
        $result = (new ZRSRequestBuilder())->withBuilding('SNELLIUS+SNELLIUS - Rekencentrum/Informatiseringsgroep')->build();
        $expected = [
            'day' => date('j'),
            'month' => date('n'),
            'year' => date('Y'),
            'selgebouw' => 'SNELLIUS+SNELLIUS - Rekencentrum/Informatiseringsgroep',
            'res_instantie' => '_ALL_',
            'submit' => 'Uitvoeren',
            'gebruiker' => '',
            'aanvrager' => '',
            'zrssort' => 'aanvangstijd',
            'activiteit' => '',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testWithOrganisation()
    {
        $result = (new ZRSRequestBuilder())->withOrganisation('INF+Subfaculteit informatica')->build();
        $expected = [
            'day' => date('j'),
            'month' => date('n'),
            'year' => date('Y'),
            'selgebouw' => '_ALL_',
            'res_instantie' => 'INF+Subfaculteit informatica',
            'submit' => 'Uitvoeren',
            'gebruiker' => '',
            'aanvrager' => '',
            'zrssort' => 'aanvangstijd',
            'activiteit' => '',
        ];

        $this->assertEquals($expected, $result);
    }
}