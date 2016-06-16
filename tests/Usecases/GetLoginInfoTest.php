<?php
namespace Extasy\Login\tests\Usecases;

use Extasy\Login\LoginAttempts\LoginAttempt;
use Extasy\Login\LoginAttempts\LoginInfo;
use Extasy\Login\tests\BaseTest;
use Extasy\Login\tests\Helpers\LoginAttemptsHelper;
use Extasy\Login\tests\Samples\MemoryLoginAttemptsRepository;
use Extasy\Login\Usecases\GetLoginInfo;
use Extasy\Users\User;

class GetLoginInfoTest extends BaseTest
{
    use LoginAttemptsHelper;
    public function setUp() {
        parent::setUp();
        $this->repository = $this->configuration->loginAttemptsRepository;
    }
    /**
     * @expectedException \Extasy\Login\Exception\ForbiddenException
     */
    public function testGetLoginInfoWithoutActiveSession()
    {
        $usecase = new GetLoginInfo($this->configuration);
        $usecase->execute();
    }

    /**
     * @group testGetLoginInfo
     */
    public function testGetLoginInfo()
    {
        $user = new User([], $this->usersConfigurationRepository);
        $user->login = 'dan';
        $user->id = 1;

        $this->configuration->sessionRepository->setCurrentUser($user);

        $successTime = $this->getDate('-1 minute');
        //
        $attempts = [
            [
                'date' => $this->getDate('-3 hours'),
                'status' => LoginAttempt::failStatus,
                'user_id' => $user->id->getValue()
            ],
            [
                'date' => $this->getDate('-2 hours'),
                'status' => LoginAttempt::failStatus,
                'user_id' => $user->id->getValue()
            ],
            ['date' => $successTime, 'status' => LoginAttempt::successStatus, 'user_id' => $user->id->getValue()],
        ];
        $this->fixtureAttempts( $attempts );

        $usecase = new GetLoginInfo($this->configuration);
        /**
         * @var LoginInfo
         */
        $result = $usecase->execute();


        $this->assertTrue( $result instanceof LoginInfo );
        $this->assertTrue( $result->successAttempt->getValue() instanceof LoginAttempt);
        $this->assertTrue( $result->failAttempt->getValue() instanceof LoginAttempt);
        $this->assertEquals(2, $result->failedCount->getValue());
    }
}