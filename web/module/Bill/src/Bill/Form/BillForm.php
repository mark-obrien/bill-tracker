<?php
namespace Bill\Form;

use Zend\Form\Form;

class BillForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('bill');

        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
                'class' => 'id'
            ),
        ));

        $this->add(array(
            'name' => 'creditor',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'creditor form-control'
            ),
            'options' => array(
                'label' => 'Creditor',
            ),
        ));

        $this->add(array(
            'name' => 'creditor_url',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'creditor form-control'
            ),
            'options' => array(
                'label' => 'Creditor URL',
            ),
        ));

        $this->add(array(
            'name' => 'type',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'type form-control'
            ),
            'options' => array(
                'label' => 'Type',
            ),
        ));

        $this->add(array(
            'name' => 'due_date',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'type form-control'
            ),
            'options' => array(
                'label' => 'Due Date',
            ),
        ));

        $this->add(array(
            'name' => 'm_payment',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'balance form-control'
            ),
            'options' => array(
                'label' => 'Monthly Payment',
            ),
        ));

        $this->add(array(
            'name' => 'balance',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'balance form-control'
            ),
            'options' => array(
                'label' => 'Balance',
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
