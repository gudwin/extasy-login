<?php


namespace Extasy\Login\tests\Usecases;

use Extasy\Login\tests\BaseTest;
use Extasy\API\Domain\Exceptions\ForbiddenException;
use Extasy\Login\Usecases\LoginByPassword;
use Extasy\Users\User;

class LoginByPasswordTest extends BaseTest
{
    const loginFixture = 'peter';
    const passwordFixture = 'a123456';

    public function setUp()
    {
        parent::setUp();
        $user = new User([], $this->usersConfigurationRepository);
        $user->login = self::loginFixture;
        $user->password = self::passwordFixture;

        //
        $this->configuration->usersRepository->insert($user);
    }

    /**
     * @expectedException \Extasy\Login\Exception\ForbiddenException
     */
    public function testWithIncorrectPassword()
    {
        $usecase = new LoginByPassword(self::loginFixture, '11112', $this->configuration);
        $usecase->execute();
    }

    public function testLoginWithPassword()
    {
        $usecase = new LoginByPassword(self::loginFixture, self::passwordFixture, $this->configuration);
        $result = $usecase->execute();
        $user = $this->configuration->sessionRepository->getCurrentUser();
        $this->AssertTrue($user instanceof User);
        $this->assertEquals( self::loginFixture, $user->login->getValue());
    }
}