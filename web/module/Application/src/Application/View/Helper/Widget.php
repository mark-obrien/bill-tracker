<?php
/**
 * To create a new Widget type, the following files must be added and modified
 * - Create a class in the Application/View/Helper directory named appropriately and extending this Widget Class
 * - Add class to Application/Module.php under viewHelper service factories
 */

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class Widget extends AbstractHelper implements ServiceManagerAwareInterface
{
    protected $_em;
    protected $_sm;

    public function __construct($em, $sm) {
        $this->_sm = $sm;
        $this->_em = $em;
    }

    /**
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->_em) {
            $this->_em = $this->_sm->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->_em;
    }

    /**
     *
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->_em = $em;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->_sm->getServiceLocator();
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $locator
     * @return void
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->_sm = $serviceManager;
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
}