<?php
namespace Extasy\Login\tests\Integrational;

use Extasy\Users\User;
use PHPUnit_Framework_TestCase;
use Extasy\Login\Session\SessionRepositoryInteface;

use Extasy\Users\Configuration\Configuration as UsersConfiguration;
use Extasy\Users\tests\Samples\MemoryConfigurationRepository;

abstract class SessionRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SessionRepositoryInteface
     */
    protected $repository = null;

    /**
     * @var MemoryConfigurationRepository
     */
    protected $memoryConfigurationRepository = null;

    /**
     * @var UsersConfiguration
     */
    protected $usersConfiguration = null;

    public function setUp()
    {
        parent::setUp();
        $this->repository = $this->getSessionRepository();
        $this->memoryConfigurationRepository = new MemoryConfigurationRepository();
        $this->usersConfiguration = new UsersConfiguration();
        $this->memoryConfigurationRepository->write($this->usersConfiguration);
    }

    protected abstract function getSessionRepository();

    public function testNotLogined()
    {
        $this->assertFalse($this->repository->isLogined());
    }

    public function testStorage()
    {
        $user = new User([], $this->memoryConfigurationRepository);
        $user->login = 'bob';
        $this->repository->setCurrentUser($user);

        // Assuming that object returned from repository could be same from semantical perspective
        // but different from perspective of actual property values
        $this->assertEquals($user->login->getValue(), $this->repository->getCurrentUser()->login->getValue());
        $this->assertEquals($user->id->getValue(), $this->repository->getCurrentUser()->id->getValue());

    }
}