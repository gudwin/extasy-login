<?php
namespace Extasy\Login\tests\Configuration;

use Extasy\Login\Configuration\Configuration;
use Extasy\Login\tests\Samples\MemorySessionRepository;
use PHPUnit_Framework_TestCase;

class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function testConfigLoaded()
    {
        $configuration = new Configuration([
            'attemptsPerPeriod' => 10,
            'period' => Configuration::monthPeriod,
            'sessionRepository' => new MemorySessionRepository()
        ]);

        $this->assertEquals(Configuration::monthPeriod, $configuration->period);
        $this->assertEquals(10, $configuration->attemptsPerPeriod);
    }
}