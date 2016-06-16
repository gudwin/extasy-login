<?php
namespace Extasy\Login\tests\Integrational;

use Extasy\Login\Configuration\Configuration;
use Extasy\Login\tests\Helpers\LoginAttemptsHelper;
use PHPUnit_Framework_TestCase;
use Extasy\Users\User;
use Extasy\Users\tests\Samples\MemoryConfigurationRepository;
use Extasy\Login\LoginAttempts\LoginAttempt;
use Extasy\Login\LoginAttempts\LoginAttemptRepository;

abstract class LoginAttemptsRepositoryTest extends PHPUnit_Framework_TestCase
{
    use LoginAttemptsHelper;

    /**
     * @var User
     */
    protected $defaultUser = null;

    /**
     * @var User
     */
    protected $anotherUser = null;

    protected abstract function loginAttemptsRepositoryFactory();

    public function setUp()
    {
        parent::setUp();

        $configurationRepository = new MemoryConfigurationRepository();
        $this->repository = $this->loginAttemptsRepositoryFactory();
        $this->defaultUser = new User([], $configurationRepository);
        $this->defaultUser->id = 1;
        $this->anotherUser = new User([], $configurationRepository);
        $this->anotherUser->id = 2;

    }

    public function testGetLastSuccess()
    {
        $this->assertTrue(is_null($this->repository->getLastSuccess($this->defaultUser)));
        $attempt = new LoginAttempt();
        $attempt->status = LoginAttempt::successStatus;
        $attempt->user_id = $this->defaultUser->id->getValue();

        $this->fixtureAttempts(
            [
                $attempt,
                ['status' => LoginAttempt::successStatus, 'user_id' => $this->anotherUser->id->getValue()],
                ['status' => LoginAttempt::failStatus, 'user_id' => $this->defaultUser->id->getValue()],
            ]
        );

        $response = $this->repository->getLastSuccess($this->defaultUser);
        $this->shortCompareAttempts($attempt, $response);

    }


    public function testGetLastFail()
    {
        $this->assertTrue(is_null($this->repository->getLastFail($this->defaultUser)));
        //
        $attempt = new LoginAttempt();
        $attempt->status = LoginAttempt::failStatus;
        $attempt->user_id = $this->defaultUser->id->getValue();

        $this->fixtureAttempts(
            [
                $attempt,
                ['status' => LoginAttempt::successStatus, 'user_id' => $this->anotherUser->id->getValue()],
                ['status' => LoginAttempt::failStatus, 'user_id' => $this->defaultUser->id->getValue()],
            ]
        );
        $response = $this->repository->getLastFail($this->defaultUser);
        $this->shortCompareAttempts($attempt, $response);
    }

    /**
     * @group testGetAttemptsCountForPeriod
     */
    public function testGetAttemptsCountForPeriod()
    {
        $this->fixtureAttempts(
            [
                [
                    'status' => LoginAttempt::successStatus,
                    'user_id' => $this->defaultUser->id->getValue(),
                    'date' => $this->getDate('-2 hour')
                ],
                [
                    'status' => LoginAttempt::successStatus,
                    'user_id' => $this->defaultUser->id->getValue(),
                    'date' => $this->getDate('-1 hour')
                ],
                [
                    'status' => LoginAttempt::successStatus,
                    'user_id' => $this->defaultUser->id->getValue(),
                    'date' => $this->getDate('-59 second')
                ],
                [
                    'status' => LoginAttempt::successStatus,
                    'user_id' => $this->defaultUser->id->getValue(),
                    'date' => $this->getDate('-58 second')
                ],
            ]
        );
        $this->assertEquals(4,
            $this->repository->getAttemptsCountForPeriod($this->defaultUser, Configuration::dayPeriod, LoginAttempt::successStatus));
        $this->assertEquals(2,
            $this->repository->getAttemptsCountForPeriod($this->defaultUser, Configuration::minutePeriod, LoginAttempt::successStatus));
        $this->assertEquals(0,
            $this->repository->getAttemptsCountForPeriod($this->defaultUser, Configuration::secondPeriod, LoginAttempt::successStatus));
    }

    public function testDeleteFromRepository()
    {
        $this->fixtureAttempts([
            [
                'status' => LoginAttempt::successStatus,
                'user_id' => $this->defaultUser->id->getValue(),
                'date' => $this->getDate('-2 hour')
            ],
            [
                'status' => LoginAttempt::successStatus,
                'user_id' => $this->defaultUser->id->getValue(),
                'date' => $this->getDate('-1 hour')
            ],
            [
                'status' => LoginAttempt::successStatus,
                'user_id' => $this->defaultUser->id->getValue(),
                'date' => $this->getDate('-59 second')
            ],
            [
                'status' => LoginAttempt::successStatus,
                'user_id' => $this->defaultUser->id->getValue(),
                'date' => $this->getDate('-58 second')
            ]
        ]);
        //
        $this->repository->delete(Configuration::minutePeriod);
        $this->assertEquals(2,
            $this->repository->getAttemptsCountForPeriod($this->defaultUser, Configuration::yearPeriod, LoginAttempt::successStatus));
        //
        $this->repository->delete(Configuration::secondPeriod);
        $this->assertEquals(0,
            $this->repository->getAttemptsCountForPeriod($this->defaultUser, Configuration::yearPeriod, LoginAttempt::successStatus));
    }

    public function testGetFailureAttemptsCountBeforeLastSuccess()
    {
        $initialMap = [
            [
                'status' => LoginAttempt::successStatus,
                'user_id' => $this->defaultUser->id->getValue(),
                'date' => $this->getDate('-59 second')
            ],
            [
                'status' => LoginAttempt::failStatus,
                'user_id' => $this->defaultUser->id->getValue(),
                'date' => $this->getDate('-58 second')
            ],
            [
                'status' => LoginAttempt::failStatus,
                'user_id' => $this->defaultUser->id->getValue(),
                'date' => $this->getDate('-57 second')
            ]
        ];
        $this->fixtureAttempts($initialMap);
        $this->assertEquals(0, $this->repository->getFailureAttemptsCountBeforeLastSuccess($this->defaultUser));
        //
        $attempt = new LoginAttempt();
        $attempt->date = $this->getDate('-5 second');
        $attempt->user_id = $this->defaultUser->id->getValue();
        $attempt->status = LoginAttempt::successStatus;
        $this->repository->insert($attempt);
        //
        $this->assertEquals(2, $this->repository->getFailureAttemptsCountBeforeLastSuccess($this->defaultUser));
        //
        $attempt = new LoginAttempt();
        $attempt->date = $this->getDate('-4 second');
        $attempt->user_id = $this->defaultUser->id->getValue();
        $attempt->status = LoginAttempt::failStatus;
        $this->repository->insert($attempt);
        //
        $attempt = new LoginAttempt();
        $attempt->date = $this->getDate('-3 second');
        $attempt->user_id = $this->defaultUser->id->getValue();
        $attempt->status = LoginAttempt::successStatus;
        $this->repository->insert($attempt);
        //
        $this->assertEquals(1, $this->repository->getFailureAttemptsCountBeforeLastSuccess($this->defaultUser));

    }


    protected function shortCompareAttempts($attempt, $response)
    {
        $this->assertEquals($attempt->user_id->getValue(), $response->user_id->getValue());
        $this->assertEquals($attempt->status->getValue(), $response->status->getValue());
    }
}