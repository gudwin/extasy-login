<?php
namespace Extasy\Login\Usecases;

use Extasy\Login\Exception\ForbiddenException;
use Extasy\Login\LoginAttempts\LoginInfo;
use Extasy\Login\Configuration\Configuration;
use Extasy\Usecase\Usecase;

class GetLoginInfo
{
    use Usecase;
    /**
     * @var Configuration
     */
    protected $configuration = null;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    protected function action()
    {
        if (!$this->configuration->sessionRepository->isLogined()) {
            throw new ForbiddenException('Authorized user not found');
        }
        $user = $this->configuration->sessionRepository->getCurrentUser();
        //
        $loginInfo = new LoginInfo();
        $loginInfo->successAttempt = $this->configuration->loginAttemptsRepository->getLastSuccess( $user );
        $loginInfo->failAttempt = $this->configuration->loginAttemptsRepository->getLastFail( $user );
        $loginInfo->failedCount = $this->configuration->loginAttemptsRepository->getFailureAttemptsCountBeforeLastSuccess($user);
        //
        return $loginInfo;
    }
}