<?php
namespace Bill\Form;

use Zend\Form\Form;

class PaymentForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('payment');

        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
                'class' => 'id'
            ),
        ));

        $this->add(array(
            'name' => 'bill_id',
            'attributes' => array(
                'type'  => 'hidden',
                'class' => 'id'
            ),
        ));

        $this->add(array(
            'name' => 'amount',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'payment form-control'
            ),
            'options' => array(
                'label' => 'Amount',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submit-button',
                'class' => 'btn btn-default'
            ),
        ));

    }
}
