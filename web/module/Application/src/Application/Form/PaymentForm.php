<?php
namespace Application\Form;

use Zend\Form\Form;

class PaymentForm extends Form
{
    public function __construct($name = null)
    {
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
                'value' => 'Pay Bill',
                'id' => 'submit-button',
                'class' => 'btn btn-default'
            ),
        ));

    }
}
