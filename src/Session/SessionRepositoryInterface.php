<?php
namespace Extasy\Login\Session;


interface SessionRepositoryInterface
{
    /**
     * @return \Extasy\Users\User
     */
    public function getCurrentUser();
    public function setCurrentUser(  $user );

    /**
     * @return bool
     */
    public function isLogined();
}