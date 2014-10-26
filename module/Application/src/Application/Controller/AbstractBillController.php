<?php

namespace Application\Controller;

use Zend\Mail\Message;
use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\ViewModel;


class AbstractBillController extends AbstractActionController
{
    const ADMIN_ROLE = 'Admin';
    const CONSULTANT_ROLE = 'Consultant';
    const FRANCHISOR_ROLE = 'Franchisor';
    const ANY_ROLE = 'Any';

    protected $_em;
    protected $_email;
    protected $_role;

    /**
     * Returns the Doctrine Entity Manager
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if(isset($this->_em))
            return $this->_em;

        return $this->setEntityManager();
    }

    /**
     * Sets the Doctrine Entity Manager
     * @return array|object
     */
    protected function setEntityManager()
    {
        $this->_em = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        return $this->_em;
    }

    /**
     * Return the User Repository Class
     * @return \Entity\UserRepository
     */
    public function getUserRepo()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\User');
    }

    /**
     * Returns repository for an entity class name, with namespace optional.
     * For entities without annotated repositories, result is general Doctrine repository.
     * @param $name
     * @return \Doctrine\ORM\EntityRepository | null
     */
    public function getRepo($name){

        if (strpos($name, "\\") === 0){
            $name = substr($name,1);
        }

        if (strpos($name,'Application\\Entity\\' ) === FALSE){
            $name = 'Application\\Entity\\' . $name;
        }

        $repo = null;
        if (class_exists($name)){
            $repo = $this->getEntityManager()->getRepository($name);
        }
        return $repo;
    }

    /**
     * Returns current user
     * @return mixed
     */
    public function getCurrentUser()
    {
        return $this->getUserRepo()->getCurrentUser();
    }

    /**
     * Called on every page load as the first function
     * Will re-direct based on required role of child controller
     * 1st --- Checks if impersonating an account, and adds to layout if so
     * @param \Zend\MVC\MvcEvent $e
     * @return bool|mixed|\Zend\Http\Response
     */
    public function onDispatch(\Zend\MVC\MvcEvent $e){
        if($this->getCurrentUser())
        {
            if($previous_user = $this->getUserRepo()->getPreviousUser())
            {
                $this->layout()->setVariable('previousUser', $previous_user);
            }
        }

        if(!empty($this->getRole())) {
            $result = $this->initRoleCheckRouting($this->getRole());
            if($result)
            {
                return $result;
            }
            else
            {
                return parent::onDispatch($e);
            }
        }
        return parent::onDispatch($e);
    }

    /**
     * Return role variable
     * @return mixed
     */
    public function getRole()
    {
        return $this->_role;
    }

    /**
     * Checks to see if users is logged with given role
     * If not, they are re-directed home
     * @param $roleNeeded
     * @return bool|\Zend\Http\Response
     */
    public function initRoleCheckRouting($roleNeeded)
    {
        $currentUser = $this->getCurrentUser();
        if(!$this->isLoggedIn('You must be logged in to view this page.'))
        {
            return $this->redirect()->toRoute('home');
        }
        else
        {
            $currentUserRole = $currentUser->getRole();
            if($roleNeeded != $currentUserRole && $currentUserRole != 'Admin' && $roleNeeded != self::ANY_ROLE)
            {
                switch($currentUserRole)
                {
                    case self::ADMIN_ROLE:
                        $routeSlug = 'franadmin';
                        break;
                    case self::CONSULTANT_ROLE:
                        $routeSlug = 'cadmin';
                        break;
                    case self::FRANCHISOR_ROLE:
                        $routeSlug = 'fadmin';
                        break;
                }
                $this->flashMessenger()->addWarningMessage('You do not have permission to see this page.');
                return $this->redirect()->toRoute($routeSlug);
            }
            else
            {
                return false;
            }
        }

    }

    /**
     * Check to see if the current user is an Admin
     * @param string $message
     * @return bool
     */
    public function checkAdmin($message = '')
    {
        $currentUser = $this->getCurrentUser();
        if(empty($currentUser))
        {
            if($message)
            {
                $fm = $this->flashMessenger();
                $fm->addMessage($message);
            }
            return false;
        }

        return $currentUser->isAdmin();
    }

    /**
     * Check to see if someone is logged in
     * @param string $message
     * @return bool
     */
    public function isLoggedIn($message = '')
    {
        $currentUser = $this->getCurrentUser();
        if(empty($currentUser))
        {
            if($message)
            {
                $fm = $this->flashMessenger();
                $fm->addMessage($message);
            }
            return false;
        }

        return true;
    }

    /**
     * Gets site config values
     * @return array|object
     */
    public function getConfig()
    {
        return $this->getServiceLocator()->get('config');
    }

    /**
     * Returns the Email service
     */
    public function getEmail()
    {
        if(isset($this->_email))
            return $this->_email;

        return $this->setEmail();
    }

    /**
     * Sets the Email service
     */
    protected function setEmail()
    {
        $this->_email = $this
            ->getServiceLocator()
            ->get('email');

        return $this->_email;
    }

    /**
     * Set attribute on layout
     * @param $title
     */
    public function setPageTitle($title)
    {
        $this->layout()->setVariable("page_title", $title);
    }

    /**
     * Set attribute on layout
     * @param $desc
     */
    public function setPageDesc($desc)
    {
        $this->layout()->setVariable("page_description", $desc);
    }

    /**
     * Set attribute on layout
     */
    public function hidePageHeader()
    {
        $this->layout()->setVariable('hide_title', true);
    }

    /**
     * Create a new activity for a candidate
     */
    public function createActivity($candidate, $type, $date = null, $notes = null, $franchise = null, $partner = null)
    {
        $activity = new Activity();
        $activity->candidate = $candidate;
        $activity->type = $type;

        if($date)
            $activity->date = $date;

        if($notes)
            $activity->notes = $notes;

        if($franchise)
            $activity->franchise = $franchise;

        if($partner)
            $activity->partner = $partner;

        $this->getEntityManager()->persist($activity);
        return $activity;
    }
}