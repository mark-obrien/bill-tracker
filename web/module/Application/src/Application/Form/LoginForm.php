<?php

namespace Application\Form;

use Zend\Form\Form;

class LoginForm extends Form{

    public function __construct($name = null)
    {
        parent::__construct('login');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'rememberme',
            'type' => 'checkbox',
            'attributes' => array(
                'type' => 'checkbox'
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Submit',
                'id' => 'submitbutton'
            ),
        ));
    }
}