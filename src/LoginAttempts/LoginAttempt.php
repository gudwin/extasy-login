<?php
namespace Extasy\Login\LoginAttempts;

use Extasy\Model\Model;

/**
 * Class LoginAttempt
 * @package Extasy\Login\LoginAttempts
 * @property \Extasy\Model\Columns\Index $id
 * @property \Extasy\Model|Columns\IP $host
 * @property \Extasy\Model\Columns\Datetime $date
 * @property \Extasy\Users\Columns\Username $user_id
 * @property \Extasy\Model\Columns\Number $status
 * @property \Extasy\Model\Columns\Input $method
 */
class LoginAttempt extends Model
{
    const failStatus = 0;
    const successStatus = 1;

    public function getFieldsInfo()
    {
        return [
            'fields' => array(
                'id' => '\\Extasy\\Model\\Columns\\Index',
                'host' => '\\Extasy\\Model\\Columns\\IP',
                'date' => '\\Extasy\\Model\\Columns\\Datetime',
                'user_id' => '\\Extasy\\Model\\Columns\\BaseColumn',
                'status' => [
                    'class' => '\\Extasy\\Model\\Columns\\BaseColumn',
                    'values' => [
                        self::failStatus => 'Failure',
                        self::successStatus => 'Success',
                    ],
                ],
                'method' => '\\Extasy\\Model\\Columns\\Input'
            )
        ];
    }
}