<?php
namespace Extasy\Login\Usecases;

use Extasy\Login\Configuration\Configuration;
use Extasy\Login\Exception\ForbiddenException;
use Extasy\Usecase\Usecase;

class Logout
{
    use Usecase;
    /**
     * @var Configuration
     */
    protected $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    protected function action()
    {
        if (!$this->configuration->sessionRepository->isLogined()) {
            throw new ForbiddenException('No active user session');
        }
        $this->configuration->sessionRepository->setCurrentUser(null);
    }
}