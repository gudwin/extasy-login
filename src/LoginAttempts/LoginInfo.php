<?php
namespace Extasy\Login\LoginAttempts;

use Extasy\Model\Model;

/**
 * Class LoginInfo
 * @package Extasy\Login\LoginAttempts
 * @property \Extasy\Model\Columns\Index $id
 * @property \Extasy\Model\Columns\BaseColumn $successAttempt
 * @property \Extasy\Model\Columns\BaseColumn $failAttempt
 * @property \Extasy\Model\Columns\Number $failedCount
 */
class LoginInfo extends Model
{
    public function getFieldsInfo()
    {
        return [
            'fields' => [
                'id' => '\\Extasy\\Model\\Columns\\Index',
                'successAttempt' => '\\Extasy\\Model\\Columns\\BaseColumn',
                'failAttempt' => '\\Extasy\\Model\\Columns\\BaseColumn',
                'failedCount' => '\\Extasy\\Model\\Columns\\BaseColumn',
            ]
        ];
    }
}