<?php

namespace Application\Controller;

use Zend\Mail;
use Zend\Mail\Exception;
use Application\Entity\User;
use Application\Form\LoginForm;
use Application\Controller\AbstractBillController;

class AccountController extends AbstractBillController
{
    public function indexAction()
    {

    }

    // Clears impersonation and logs in as previous User
    public function stopImpersonateAction()
    {
        $previousUserId = $this->params()->fromRoute('id', null);
        if (is_null($previousUserId)){
            return $this->redirect()->toRoute('home', null, null, true);
        }
        $previousUser = $this->getEntityManager()->find('\Application\Entity\User', $previousUserId);
        $this->getUserRepo()->clearImpersonate($previousUser);
        $this->flashMessenger()->addSuccessMessage("You are logged in as your original user");
        return $this->redirect()->toRoute('franadmin');

    }

    public function loginAction()
    {
        $return = array();

        $form = new LoginForm();
        $request = $this->getRequest();
        if($request->isPost())
        {
            $data = $request->getPost();
            $form->setData($data);

            if($form->isValid())
            {
                $formData = $form->getData();
                $userRepo = $this->_getUserRepo();

                try {
                    $user = $userRepo->authenticate($formData['username'], $formData['password']);
                } catch(\Exception $e) {
                    $return['messages'][]=$e->getMessage();
                }

                if(isset($user))
                {
                    //route user to correct admin based on role
                    switch($user->getRole())
                    {
                        case $this::ADMIN_ROLE:
                            $routeSlug = 'bill';
                            break;
                    }

                    // Remember me
                    if($formData['rememberme'])
                    {
                        $cookie = array('username' => $user->username);
                        setcookie("bill-tracker", serialize($cookie), time()+60*60*24*100,'/');
                    }

                    return $this->redirect()->toRoute($routeSlug);
                }
                else
                {
                    $this->flashMessenger()->addErrorMessage("Username or Password incorrect. Please try again.");
                    return $this->redirect()->toRoute('home');
                }
            }
        }

        $form->get('submit')->setValue('Submit');
        $return['form'] = $form;
        return $return;
    }

    public function logoutAction()
    {
        $userRepo = $this->_getUserRepo();
        $userRepo->logout();
        $this->flashMessenger()->addSuccessMessage("You have been logged out successfully.");
        return $this->redirect()->toRoute('home');
    }

    public function forgotPasswordAction()
    {
        $this->setPageTitle('Forgot Password');

        $return = array();

        $form = new ForgotPasswordForm();

        $request = $this->getRequest();
        if($request->isPost())
        {
            $data = $request->getPost();
            $form->setInputFilter($form->getInputFilter());
            $form->setData($data);

            if($form->isValid())
            {
                $formData = $form->getData();
                $userRepo = $this->getUserRepo();

                if(!$userRepo->checkIfUserExists(array('username'=>$formData['username'])))
                {
                    $this->flashMessenger()->addErrorMessage("Username is invalid");
                    $return['form'] = $form;
                    return $return;
                }

                $user = $userRepo->findOneBy(array('username'=>$formData['username']));

                $userRepo->resetPasswordToken($user, 7200);

                // Send the user an email containing their new password.
                $reset_url = $this->getRequest()->getUri()->getScheme()."://".$this->getRequest()->getUri()->getHost()
                    .$this->url()->fromRoute("Application\account", array('action' => 'reset-password'), array('query' => array('rc' => $user->passwordResetCode)));
                $body = "There was recently a request to change the password for your account.<br/><br/>";
                $body.= "If you requested this password change, please click on the following link to reset your password:<br/>";
                $body.= "<a href='$reset_url'>$reset_url</a><br/><br/>";
                $body.= "If you did not make this request, you can ignore this message and your password will remain the same.";
                $this->getEmail()->sendAlert("Forgot Password", $user->email, $body);

                $this->flashMessenger()->addSuccessMessage("A reset e-mail with instructions has been sent");
                $form = new ForgotPasswordForm();
            }
        }

        $return['form'] = $form;

        return $return;
    }

    public function resetPasswordAction()
    {
        $this->setPageTitle('Reset Password');

        $return = array();
        $request = $this->getRequest();

        $form = new ResetPasswordForm();

        // Check for post submission
        if($request->isPost())
        {
            $data = $request->getPost();
            $form->setInputFilter($form->getInputFilter());
            $form->setData($data);

            if($form->isValid())
            {
                $formData = $form->getData();
                $userRepo = $this->getUserRepo();

                $user = $userRepo->findOneBy(array('id'=>$formData['user_id']));

                if(!$user)
                {
                    $this->flashMessenger()->addErrorMessage("The user is invalid.");
                    return $return;
                }

                $user->setPassword($formData['password']);
                $user->passwordResetCode = null;
                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('Your password has been reset successfully. Please login.');

                // Re-direct
                return $this->redirect()->toRoute('home');
            }
            else {
                $form->get('password')->setValue('');
                $form->get('password-retype')->setValue('');
            }
        }

        else
        {
            // Check user from token
            $user = $this->getUserRepo()->isResetTokenValid($request->getQuery()->rc);
            if(!$user instanceof User)
            {
                $this->flashMessenger()->addErrorMessage("The URL is invalid.");
                // Re-direct
                return $this->redirect()->toRoute('home');
            }

            $form->get('user_id')->setValue($user->id);
        }

        $return['form'] = $form;

        return $return;
    }

    private function _getUserRepo()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\User');
    }

}