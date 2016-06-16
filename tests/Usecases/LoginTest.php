<?php

namespace Extasy\Login\tests\Usecases;

use Extasy\Login\tests\BaseTest;
use Extasy\Login\Usecases\Login;
use Extasy\Users\User;

class LoginTest extends BaseTest {

    /**
     * @expectedException \Extasy\Login\Exception\ForbiddenException
     */
    public function testLoginWithBannedUser() {
        $user = new User([], $this->usersConfigurationRepository );
        $user->confirmation_code = 'banned_code';
        $usecase = new Login( $user, $this->configuration );
        $usecase->execute();
    }
    public function testLogin() {
        $fixture = 'mike';
        $user = new User([], $this->usersConfigurationRepository );
        $user->login = $fixture;

        $usecase = new Login( $user, $this->configuration );
        $usecase->execute();
        //
        $this->assertTrue( $this->configuration->sessionRepository->getCurrentUser() instanceof User);
        //
        $returnedUser = $this->configuration->sessionRepository->getCurrentUser();
        $this->assertEquals( $fixture , $returnedUser->login->getValue() );
    }
}