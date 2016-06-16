<?php
namespace Extasy\Login\tests\Helpers;

use Extasy\Login\Configuration\Configuration;
use Extasy\Login\LoginAttempts\LoginAttempt;
use Extasy\Login\LoginAttempts\LoginAttemptRepository;

trait LoginAttemptsHelper
{
    /**
     * @var LoginAttemptRepository;
     */
    protected $repository;

    protected function getDate($period)
    {
        return date('Y-m-d H:i:s', strtotime($period));
    }

    protected function fixtureAttempts($attempts)
    {
        $this->repository->delete(Configuration::infinityPeriod);
        //
        foreach ($attempts as $row) {
            if ($row instanceof LoginAttempt) {
                $this->repository->insert($row);
                continue;
            }
            $attempt = new LoginAttempt();
            if (isset($row['status'])) {
                $attempt->status = $row['status'];
            }
            if (isset($row['user_id'])) {
                $attempt->user_id = $row['user_id'];
            }
            if (isset($row['date'])) {
                $attempt->date = $row['date'];
            }
            $this->repository->insert($attempt);
        }
    }
}