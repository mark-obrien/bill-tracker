<?php
  
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Element\Time;
use Zend\Http\Header\Date;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use DateTime;
  
/**
 * A Bill.
 *
 * @ORM\Entity
 * @ORM\Table(name="bill")
 * @property string $creditor
 * @property string $type
 * @property int $id
 */
class Bill implements InputFilterAwareInterface 
{
    protected $inputFilter;
  
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $creditor;

    /**
     * @ORM\Column(type="string")
     */
    protected $creditor_url;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @ORM\Column(type="decimal", scale=2, precision=10)
     */
    protected $m_payment;

    /**
     * @ORM\Column(type="decimal", scale=2, precision=10)
     */
    protected $balance;

    /**
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="bill",cascade={"persist"}, orphanRemoval=true)
     * @var payment[]
     */
    protected $payments = null;


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
  
    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function exchangeArray ($data = array()) 
    {
        $this->id = $data['id'];
        $this->creditor = $data['creditor'];
        $this->creditor_url = $data['creditor_url'];
        $this->type = $data['type'];
        $this->m_payment = $data['m_payment'];
        $this->balance = $data['balance'];
    }

    public function get_payments(){
        $criteria = Criteria::create()
            ->orderBy(array("date" => Criteria::DESC));
        return $this->payments->matching($criteria);
    }

    public function get_latest_payment(){

        if($this->payments->count() > 0){
            $criteria = Criteria::create()
                ->orderBy(array("date" => Criteria::DESC));
            $payment = $this->payments->matching($criteria)->first();
            return $payment;
        }

        else {
            return 'No Payment Recorded';
        }
    }

    public function get_latest_payment_class(){

        $payment_class = "text-warning";
        $diff = "";

        if($this->payments->count() > 0){
            $criteria = Criteria::create()
                ->orderBy(array("date" => Criteria::DESC))
                ->setMaxResults(1);
            $payment = $this->payments->matching($criteria)->first();


            $today = new DateTime('now');
            $today = $today->format('Y-m-d');
            $today = date_create($today);

            $payment_date = $payment->date;
            $payment_date = $payment_date->format('Y-m-d');
            $payment_date = date_create($payment_date);

            $interval = date_diff($today, $payment_date);

            $differenceFormat = '%a';

            $diff = $interval->format($differenceFormat);

            if($diff <= 15){
                $payment_class = "text-success";
            }
            elseif($diff >= 16 && $diff < 25){
                $payment_class = "text-warning";
            }
            else {
                $payment_class = "text-danger transition-danger";
            }
        }

        return $payment_class . " " . $diff;
    }

    public function get_current_balance(){

        $balance = $this->balance;

        if($this->payments->count() > 0){
            $criteria = Criteria::create()
                ->orderBy(array("date" => Criteria::DESC));
            $payments = $this->payments->matching($criteria);

            foreach($payments as $payment){
                $balance = $balance - $payment->amount;
            }
        }

        return "$".$balance;
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
                'name'     => 'creditor',
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
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'creditor_url',
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
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
 
            $inputFilter->add(array(
                'name'     => 'type',
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
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
 
            $this->inputFilter = $inputFilter;
        }
 
        return $this->inputFilter;
    }
}
