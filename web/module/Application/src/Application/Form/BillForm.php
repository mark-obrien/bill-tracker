<?php
namespace Application\Form;

use Application\Entity\Bill;
use Zend\Form\Form;
use Zend\Form\Element;

class BillForm extends Form
{
    const DAY_OF_MONTH = array(
        '1' => '1st',
        '2' => '2nd',
        '3' => '3rd',
        '4' => '4th',
        '5' => '5th',
        '6' => '6th',
        '7' => '7th',
        '8' => '8th',
        '9' => '9th',
        '10' => '10th',
        '11' => '11th',
        '12' => '12th',
        '13' => '13th',
        '14' => '14th',
        '15' => '15th',
        '16' => '16th',
        '17' => '17th',
        '18' => '18th',
        '19' => '19th',
        '20' => '20th',
        '21' => '21st',
        '22' => '22nd',
        '23' => '23rd',
        '24' => '24th',
        '25' => '25th',
        '26' => '27th',
        '28' => '28th',
        '29' => '29th',
        '30' => '30th',
        '31' => '31st'
    );

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
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'type form-control'
            ),
            'options' => array(
                'label' => 'Type',
                'value_options' => Bill::$types
            ),
        ));

        $this->add(array(
            'name' => 'debt_type',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'type form-control'
            ),
            'options' => array(
                'label' => 'Debt Type',
                'value_options' => Bill::$debt_types
            ),
        ));

        $this->add(array(
            'name' => 'due_date',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'type form-control',
            ),
            'options' => array(
                'label' => 'Due Date',
                'value_options' => self::DAY_OF_MONTH
            ),
        ));

        $this->add(array(
            'name' => 'monthly_payment',
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
