<?php
namespace Extasy\Login\LoginAttempts;

use Extasy\Users\User;
interface LoginAttemptRepository
{
    public function insert(LoginAttempt $attempt);

    public function delete( $period );

    public function getAttemptsCountForPeriod( User $user, $period, $status );

    public function getFailureAttemptsCountBeforeLastSuccess( User $user);
    /**
     * @return LoginAttempt
     */
    public function getLastSuccess( User $user );

    /**
     * @return LoginAttempt
     */
    public function getLastFail( User $user );


}