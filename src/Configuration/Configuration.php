<?php
namespace Extasy\Login\Configuration;

use Extasy\Users\RepositoryInterface;
use Extasy\Login\Session\SessionRepositoryInterface;
use Extasy\Login\LoginAttempts\LoginAttemptRepository;

class Configuration
{
    const secondPeriod = 0;
    const minutePeriod = 1;
    const hourPeriod = 2;
    const dayPeriod = 3;
    const weekPeriod = 4;
    const monthPeriod = 5;
    const yearPeriod = 6;
    const infinityPeriod = 7;

    public $attemptsPerPeriod = 0;

    public $cleanupPeriod = 0;
    public $period = 0;
    /**
     * @var SessionRepositoryInterface
     */
    public $sessionRepository = null;
    /**
     * @var LoginAttemptRepository
     */
    public $loginAttemptsRepository = null;
    /**
     * @var RepositoryInterface
     */
    public $usersRepository = null;


    public function __construct($data = [])
    {

        foreach ($data as $key => $value) {
            if ( property_exists( $this, $key )) {
                $this->$key = $value;
            }

        }
    }
}