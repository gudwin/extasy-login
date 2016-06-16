<?php


namespace Extasy\Login\tests\Usecases;

use Extasy\Login\Configuration\Configuration;
use Extasy\Login\LoginAttempts\LoginAttempt;
use Extasy\Login\Usecases\CleanupLoginAttempts;
use Extasy\Login\tests\BaseTest;
use Extasy\Users\User;

class CleanupLoginAttemptsTests extends BaseTest
{
    public function testCleanup()
    {
        $user = new User([], $this->usersConfigurationRepository);
        $user->login = 'stan';
        $this->configuration->usersRepository->insert($user);
        //
        $loginAttempt = new LoginAttempt();
        $loginAttempt->status = LoginAttempt::successStatus;
        $loginAttempt->date = date('Y-m-d H:i:s', strtotime('-2 hours'));
        $loginAttempt->user_id = $user->id->getValue();
        //
        $loginAttempt = new LoginAttempt();
        $loginAttempt->status = LoginAttempt::failStatus;
        $loginAttempt->date = date('Y-m-d H:i:s', strtotime('-1 minute'));
        $loginAttempt->user_id = $user->id->getValue();


        $this->configuration->attemptsPerPeriod = Configuration::hourPeriod;
        $usecase = new CleanupLoginAttempts($this->configuration);
        $usecase->execute();
        //
        $this->assertEquals(null, $this->configuration->loginAttemptsRepository->getLastFail($user));
        $this->assertTrue($this->configuration->loginAttemptsRepository->getLastSuccess($user) instanceof LoginAttempt);
    }
}