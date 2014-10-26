<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Crypt\Password\Bcrypt;
use Zend\Debug\Debug;

/**
 * A user is a person
 *
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="user", indexes={@ORM\Index(name="username_idx", columns={"username"})})
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property \DateTime $dateAdded
 * @property \DateTime $dateDisabled
 * @property string $password
 * @property Role role
 */
class User implements InputFilterAwareInterface
{
    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * Name user uses to log on
     * @ORM\Column(type="string", nullable=false, length=64)
     * @var string
     */
    protected $username;

    /**
     * Last Name of user
     * @ORM\Column(type="string", name="last_name", nullable=true, length=32)
     * @var string
     */
    protected $lastName;

    /**
     * First Name of user
     * @ORM\Column(type="string", name="first_name", nullable=true, length=32)
     * @var string
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", name="email", nullable=true, length=64)
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(type="boolean", name="active", nullable=false)
     * @var boolean
     */
    protected $active = false;

    /**
     * password
     * @ORM\Column(type="string")
     * @var string
     */
    protected $password;

    /**
     * Password reset code
     * @ORM\Column(type="string",  nullable=true)
     * @var string
     */
    protected $passwordResetCode;

    /**
     * Date user was added
     * @ORM\Column(type="datetime", name="date_added", nullable=false)
     * @var \DateTime
     */
    protected $dateAdded;

    /**
     * Date account was disabled, null if enabled.
     * @ORM\Column(type="datetime", name="date_disabled",nullable=true)
     * @var \DateTime
     */
    protected $dateDisabled;

    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="users")
     **/
    protected $role;

    protected $newPassword; // not persisted.

    public function __construct()
    {
        $this->dateAdded = new \DateTime();
        $this->addresses = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
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

    public function getRole()
    {
        return $this->role->name;
    }

    /**
     * Stores encrypted password
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        set_time_limit(31);
        //$bcrypt = new Bcrypt();
        //$this->password = $bcrypt->create($password);
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Checks encrypted password
     * @param string $password
     * @return bool
     */
    public function checkPassword($password)
    {
        //$bcrypt = new Bcrypt();
        //return $bcrypt->verify($password,$this->password);
        return password_verify($password, $this->password);
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
    public function populate($data = array())
    {
        //Debug::dump($data);die;
        if (isset($data['id'])){
            $this->id = $data['id'];
        }
        if (isset($data['username'])){
            $this->username = $data['username'];
        } else {
            $this->username = $data['email'];
        }
        $this->email = $data['email'];
        $this->firstName = $data['firstName'];
        $this->lastName = $data['lastName'];
        if (isset($data['password'])) $this->setPassword($data['password']);
        if (isset($data['active'])) $this->active = $data['active'];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'       => 'id',
                'required'   => true,
                'filters' => array(
                    array('name'    => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'username',
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
                            'max'      => 64,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'firstName',
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
                            'max'      => 32,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'lastName',
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
                            'max'      => 32,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
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
                            'max'      => 64,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'active',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Digits'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Between',
                        'options' => array(
                            'min'      => 0,
                            'max'      => 1,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}