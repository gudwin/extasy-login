<?
namespace Extasy\Login\tests\Samples;

use Extasy\Users\User;
use Extasy\Login\Session\SessionRepositoryInterface;

class MemorySessionRepository implements SessionRepositoryInterface
{
    protected $currentUser = null;
    /**
     * @return \Extasy\Users\User
     */
    public function getCurrentUser() {
        return $this->currentUser;
    }

    public function setCurrentUser( $user ) {
        $this->currentUser = $user;
    }

    /**
     * @return bool
     */
    public function isLogined() {
        return !empty( $this->currentUser );
    }
}