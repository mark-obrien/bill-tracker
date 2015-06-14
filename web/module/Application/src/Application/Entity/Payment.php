<?php
  
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\Bill;
use Doctrine\Common\Collections\Criteria;
  
/**
 * A Payment.
 *
 * @ORM\Entity
 * @ORM\Table(name="payment")
 * @property date $date
 * @property float $amount
 * @property int $id
 */
class Payment implements InputFilterAwareInterface 
{
    protected $inputFilter;
  
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bill", inversedBy="payments")
     * @var Bill
     **/
    protected $bill;

    /**
     * @ORM\Column(type="string")
     */
    protected $bill_json;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $date;

    /**
     * @ORM\Column(type="string")
     */
    protected $date_json;

    /**
     * @ORM\Column(type="decimal", scale=2, precision=10)
     */
    protected $amount;

    /**
     * @ORM\Column(type="decimal", scale=2, precision=10)
     */
    protected $running_balance;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property) 
    {
        return $this->$property;
    }
  
    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) 
    {
        $this->$property = $value;
    }
  
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy() 
    {
        return get_object_vars($this);
    }

    public function get_bill_name(Bill $bill){

        return $bill->creditor;
    }
  
    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function exchangeArray ($data = array()) 
    {
        $this->id = $data['id'];
        $this->date = new \DateTime("now");
        $this->date_json = $this->date->format('Y-m');
        $this->bill_json = $this->get_bill_name($this->bill);
        $this->amount = $data['amount'];
        $this->running_balance = $this->bill->balance - $data['amount'];
        $this->bill->balance = $this->running_balance;
    }

    public function addBill(Bill $bill)
     {
         $this->bill = $bill;
     }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
  
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'amount',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 10,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'date',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 10,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
