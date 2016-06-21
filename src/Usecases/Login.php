<?php
namespace Extasy\Login\Usecases;

use Extasy\Login\Configuration\Configuration;
use Extasy\Login\Exception\ForbiddenException;
use Extasy\Usecase\Usecase;
use Extasy\Users\User;

class Login
{
    use Usecase;
    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var User
     */
    protected $user = null;

    public function __construct(User $user, Configuration $configuration)
    {
        $this->configuration = $configuration;
        $this->user = $user;
    }

    protected function action()
    {
        if ($this->user->isBanned()) {
            throw new ForbiddenException('User not allowed to login');
        }

        $this->configuration->sessionRepository->setCurrentUser($this->user);
    }
}