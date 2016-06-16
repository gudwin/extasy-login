<?php
namespace Extasy\Login\tests\Samples;

use Extasy\Login\Configuration\Configuration;
use Extasy\Users\User;
use Extasy\Login\LoginAttempts\LoginAttempt;
use Extasy\Login\LoginAttempts\LoginAttemptRepository;

class MemoryLoginAttemptsRepository implements LoginAttemptRepository
{
    protected $repository = [];
    protected $increment = 1;

    public function insert(LoginAttempt $attempt)
    {
        if (empty($attempt->id->getValue())) {
            $attempt->id->setValue($this->increment);
        }
        $this->repository[] = $attempt;
        $this->increment++;
        usort($this->repository, function ($a, $b) {
            if ($a->date->getValue() == $b->date->getValue()) {
                return 0;
            }

            return $a->date->getValue() > $b->date->getValue() ? 1 : -1;
        });
    }

    public function delete($period)
    {
        $deleteTime = $this->parsePeriod($period);
        foreach ($this->repository as $key => $row) {
            $needToDelete = $row->date->getValue() <= $deleteTime;
            if ($needToDelete) {
                unset($this->repository[$key]);
            }
        }
    }

    public function getAttemptsCountForPeriod(User $user, $period, $status)
    {
        $result = 0;
        //
        $data = array_reverse($this->repository);
        $time = $this->parsePeriod($period);

        foreach ($data as $key => $row) {
            $needBreak = $row->date->getValue() < $time;
            if ($needBreak) {
                break;
            }
            $isSameUser = $user->id->getValue() == $row->user_id->getValue();
            $isSameStatus = $status == $row->status->getValue();
            if ($isSameStatus && $isSameUser) {
                $result++;
            }
        }

        return $result;
    }

    public function getFailureAttemptsCountBeforeLastSuccess(User $user)
    {
        $result = 0;
        $data = array_reverse($this->repository);
        $isFirstSuccess = false;

        foreach ($data as $key => $row) {
            $isSameUser = $user->id->getValue() == $row->user_id->getValue();
            $isSuccessStatus = LoginAttempt::successStatus == $row->status->getValue();
            //
            if ($isSameUser) {
                if ($isSuccessStatus) {
                    if ($isFirstSuccess) {
                        break;
                    } else {
                        $isFirstSuccess = true;
                    }
                } else {
                    if ( $isFirstSuccess ) {
                        $result++;
                    }
                }
            }

        }

        return $result;
    }

    /**
     * @return LoginAttempt
     */
    public function getLastSuccess(User $user)
    {
        $data = array_reverse($this->repository);
        foreach ($data as $key => $row) {
            $isSameUser = $user->id->getValue() == $row->user_id->getValue();
            $isSuccessStatus = LoginAttempt::successStatus == $row->status->getValue();
            //
            if ($isSuccessStatus && $isSameUser) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @return LoginAttempt
     */
    public function getLastFail(User $user)
    {
        $data = array_reverse($this->repository);
        foreach ($data as $key => $row) {
            $isSameUser = $user->id->getValue() == $row->user_id->getValue();
            $isFailStatus = LoginAttempt::failStatus == $row->status->getValue();
            //
            if ($isFailStatus && $isSameUser) {
                return $row;
            }
        }

        return null;
    }

    protected function parsePeriod($period)
    {
        $map = [
            Configuration::secondPeriod => 1,
            Configuration::minutePeriod => 60,
            Configuration::hourPeriod => 3600,
            Configuration::dayPeriod => 86400,
            Configuration::weekPeriod => 86400 * 7,
            Configuration::monthPeriod => 86400 * 31,
            Configuration::yearPeriod => 86400 * 365,
            Configuration::infinityPeriod => -1 * time(),
        ];

        return date('Y-m-d H:i:s',time() - $map[$period]);
    }
}