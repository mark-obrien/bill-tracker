<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Form\LoginForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractBillController
{
    public function indexAction()
    {
        $this->hidePageHeader();

        $form = new LoginForm();
        if(!empty($_COOKIE['franchoice-entree']))
        {
            $data = unserialize($_COOKIE['franchoice-entree']);
            if(!empty($data['username']))
            {
                $form->setData(array('username' => $data['username']));
            }
        }
        return array('form' => $form);
    }

    /*
     * Test sending mail
     * - URL http://franchoice.vg.blueearth.net/application/index/test-mail
     */
    public function testMailAction()
    {
        // send alert message
        $email_body = "Thank you!";
        $this->getEmail()->sendAlert('One Sheet Edits', 'test@test.com', $email_body);

        // Call get mailer and set need info on message
        $this->getEmail()->reset();
        $this->getEmail()->getMessage()
            ->setSubject('This is the subject'.rand(0,1231232))
            ->setFrom('from@test.com')
            ->addTo('test@test.com')
            ->addBcc("hidden@domain.com");

        // Important -- Use this to set the body
        $this->getEmail()->setBody('<h3>Woot!!!!!!!!</h3><h4>This is the new message, woot!</h4>');

        // For adding attachments
        $file = $this->getEntityManager()->find('Application\Entity\File', 3);
        if($file)
            $this->getEmail()->getMailer()->addAttachment($file->getFullPath());

        // Send message and get results
        $result = $this->getEmail()->send();
        if ($result->isValid()) {
            echo 'Message sent. Congratulations!';

            // Reset Mailer
            $this->getEmail()->reset();
            $this->getEmail()->getMessage()->addTo("test@test.com");
            $result = $this->getEmail()->send();

        } else {
            if ($result->hasException()) {
                echo sprintf('An error occurred. Exception: \n %s', $result->getException()->getTraceAsString());
            } else {
                echo sprintf('An error occurred. Message: %s', $result->getMessage());
            }
        }

        exit;

    }
}
