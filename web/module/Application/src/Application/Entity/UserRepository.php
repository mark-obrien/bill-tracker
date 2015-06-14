<?php
namespace Application\Entity;

use Doctrine\ORM\EntityRepository;
use Zend\Math\Rand;
use Zend\Session\Container;
use Entities;
use Zend\Debug\Debug;

class UserRepository extends EntityRepository
{
    var $currentUser = null;

    /**
     * Looks up user by username and checks if password is valid
     * @param string $username
     * @param string $password
     * @return \Application\Entity\User
     * @throws \Exception
     */
    public function authenticate($username,$password)
    {
        $user=$this->findOneByUsername($username);

        if ($user && $user->dateDisabled == null) {
            if ($user->checkPassword($password)) {
                $this->setCurrentUser($user);
                return $user;
            }
        }
        throw new \Exception('Username/password not valid.',15);
    }

    /**
     * Sets currently logged in user in session
     * @param mixed $user
     */
    public function setCurrentUser($user)
    {
        $user_session = new Container('user');
        $username = null;
        $currentUser = null;
        if ($user)
        {
            $username = $user->username;
            switch($user->getRole())
            {
                case "Admin":
                    $currentUser = $user;
                    break;
                case "Consultant":
                    $consultantUser = $this->getEntityManager()->getRepository('\Application\Entity\UserConsultant')->findOneBy(array('user'=>$user));
                    $currentUser = $consultantUser;
                    break;
                case "Franchisor":
                    $franchisorUser = $this->getEntityManager()->getRepository('\Application\Entity\UserFranchisor')->findOneBy(array('user'=>$user));
                    $currentUser = $franchisorUser;
                    break;
            }
        }
        $user_session->username = $username;
        $this->currentUser = $currentUser;
    }

    /**
     * Gets current user from session, or null if no user logged in.
     * @return User
     */
    public function getCurrentUser()
    {
        if ($this->currentUser) return $this->currentUser;

        $user_session = new Container('user');
        if ($user_session->username) {
            $em = $this->getEntityManager();
            $ur = $em->getRepository('Application\Entity\User');
            $user = $ur->findOneByUsername($user_session->username);
            if (!$user) $user_session->username=null; // it was bogus, erase it.
            $this->setCurrentUser($user);
            return $user;
        }
        return null;
    }

    /**
     * Return previous user, set if impersonating
     * @return null
     */
    public function getPreviousUser()
    {
        $user_session = new Container('user');
        if ($user_session->previous_username) {
            $em = $this->getEntityManager();
            $ur = $em->getRepository('Application\Entity\User');
            $user = $ur->findOneByUsername($user_session->previous_username);
            return $user;
        }
        return null;
    }

    public function checkIfUserExists($criteria)
    {
        $user = $this->findOneBy(array('username'=>$criteria));
        if($user)
        {
            return true;
        }
        return false;
    }

    /**
     * Clears username from session.
     */
    public function logOut()
    {
        $user_session = new Container('user');
        $user_session->getManager()->getStorage()->clear('user');
        $this->setCurrentUser(null);
    }

    /**
     * Sets impersonation user
     * @param User $user
     */
    public function impersonate(User $user){
        $user_session = new Container('user');
        $user_session->previous_username = $this->getCurrentUser()->username;
        $this->setCurrentUser($user);
    }

    /**
     * Clears the previousUser and sets the given user to current user
     */
    public function clearImpersonate(User $user)
    {
        $user_session = new Container('user');
        $user_session->previous_username = null;
        $this->setCurrentUser($user);
    }

    /**
     * Checks if user is logged on
     * @return bool
     */
    public function isUser()
    {
        $user = $this->getCurrentUser();
        return $user!=null;
    }

    /**
     * Checks if user is logged in as an admin
     * @return bool
     */
    public function isAdmin()
    {
        $user = $this->getCurrentUser();
        return $user && $user->isAdmin();
    }

    /**
     * Checks if user is logged in as an uber admin.
     * @return bool
     */
    public function isUberAdmin()
    {
        $user = $this->getCurrentUser();
        return $user && $user->isUberAdmin();
    }

    /**
     * Create a new random token, store it in the database
     */
    public function resetPasswordToken($user, $token_lifetime)
    {
        $token = $this->generateResetToken($token_lifetime);
        $user->passwordResetCode = $token;
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Generate Reset Token for a user
     * - Token comprises of a 40 character long string concatenated with '|' [expire time stamp]
     */
    private function generateResetToken($token_lifetime)
    {
        $current_time = time();
        $expire_time = $current_time + $token_lifetime;
        $token = Rand::getString(40, 'abcdefghijklmnopqrstuvwxyz', true).'|'.$expire_time;
        return $token;
    }

    /**
     * Checks if reset token is valid
     */
    public function isResetTokenValid($token)
    {
        if(empty($token))
            return false;

        $timestamp = substr($token, strpos($token, "|") + 1, strlen($token) - strpos($token, "|"));

        // Check timestamp on expiration
        if(time() > $timestamp)
            return false;

        // Check if a valid user is found
        return $this->findOneBy(array('passwordResetCode' => $token));
    }
}
