<?php
namespace Extasy\Login\tests\Usecases;

use Extasy\Login\Exception\ForbiddenException;
use Extasy\Login\tests\BaseTest;
use Extasy\Login\Usecases\Logout;
use Extasy\Users\User;

class LogoutTest extends BaseTest
{
    /**
     * @expectedException \Extasy\Login\Exception\ForbiddenException
     */
    public function testLogoutWithoutCurrentSession()
    {
        $usecase = new Logout($this->configuration);
        $usecase->execute();
    }

    public function testLogout()
    {
        $user = new User([], $this->usersConfigurationRepository);
        $this->configuration->sessionRepository->setCurrentUser($user);
        $usecase = new Logout($this->configuration);
        $usecase->execute();
        $this->assertFalse($this->configuration->sessionRepository->isLogined());
    }

}
