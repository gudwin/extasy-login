<?php


namespace Extasy\Login\Usecases;

use Extasy\Login\Configuration\Configuration;
use Extasy\Usecase\Usecase;

class CleanupLoginAttempts
{
    use Usecase;
    /**
     * @var Configuration
     */
    protected $configuration = null;

    public function __construct( Configuration $configuration )
    {
        $this->configuration = $configuration;
    }

    protected function action() {
        $this->configuration->loginAttemptsRepository->delete( $this->configuration->cleanupPeriod );
    }
}