<?php
namespace Extasy\Login\tests;

use Extasy\Users\Configuration\Configuration as UsersConfiguration;
use Extasy\Login\tests\Samples\MemoryLoginAttemptsRepository;
use Extasy\Users\tests\Samples\MemoryUsersRepository;
use Extasy\Login\tests\Samples\MemorySessionRepository;
use Extasy\Users\tests\Samples\MemoryConfigurationRepository;
use PHPUnit_Framework_TestCase;
use Extasy\Login\Configuration\Configuration;
abstract class BaseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Configuration
     */
    protected $configuration = null;

    /**
     * @var UsersConfiguration
     */
    protected $usersConfiguration = null;
    /**
     * @var MemoryConfigurationRepository
     */
    protected $usersConfigurationRepository = null;

    public function setUp()
    {
        parent::setUp();
        $this->usersConfiguration = new UsersConfiguration();
        $this->usersConfigurationRepository = new MemoryConfigurationRepository();
        $this->usersConfigurationRepository->write( $this->usersConfiguration );

        $this->configuration = new Configuration([
            'attemptsPerPeriod' => 10000,
            'period' => Configuration::infinityPeriod,
            'sessionRepository' => new MemorySessionRepository(),
            'loginAttemptsRepository' => new MemoryLoginAttemptsRepository(),
            'usersRepository' => new MemoryUsersRepository(),
        ]);
    }
}