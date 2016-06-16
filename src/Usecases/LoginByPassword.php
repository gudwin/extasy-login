<?php


namespace Extasy\Login\Usecases;

use Extasy\Login\Configuration\Configuration;
use Extasy\Login\Exception\ForbiddenException;
use Extasy\Model\NotFoundException;
use Extasy\Usecase\Usecase;
use Extasy\Users\User;
use Extasy\Users\Search\Request;

class LoginByPassword
{
    use Usecase;
    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var string
     */
    protected $login = null;
    /**
     * @var string
     */
    protected $password = null;

    public function __construct($login, $password, Configuration $configuration)
    {
        $this->login = $login;
        $this->password = $password;
        $this->configuration = $configuration;
    }

    protected function action()
    {
        $searchRequest = new Request();
        $searchRequest->fields = [
            'login' => $this->login
        ];
        /**
         * @var User
         */
        $user = $this->configuration->usersRepository->findOne($searchRequest);
        if (empty($user)) {
            throw new NotFoundException(sprintf('User with login=`%s` not found', $this->login));
        }
        $isSamepassword = $user->password->hash($this->password) === $user->password->getValue();
        if (!$isSamepassword) {
            throw new ForbiddenException(sprintf('Password not match. %s = %s', $user->password->hash( $this->password), $user->password->getValue()));
        }
        //
        $loginUsecase = new Login($user, $this->configuration);
        $loginUsecase->execute();
    }
}