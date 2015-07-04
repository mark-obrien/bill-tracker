<?php

namespace Application\Form\View\Helper;

use Zend\Form\Element\Button;
use Zend\Form\Element\MonthSelect;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;
use Zend\Form\View\Helper\FormRow;

class FormRowBS extends FormRow{

    /**
     * Sets default partial to bs2col
     * @var string
     */
    public function __invoke(ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
    {
        if ($partial == null) {
            $partial = 'bs2col';
        }

        return parent::__invoke($element, $labelPosition, $renderErrors, $partial);
    }

}