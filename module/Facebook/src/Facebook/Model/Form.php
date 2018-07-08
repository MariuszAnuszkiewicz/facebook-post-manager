<?php

namespace Facebook\Facebook\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Facebook\Form\Validator;

class Form implements InputFilterAwareInterface
{
    public $link_name;
    public $message;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->link_name = (!empty($data['link_name'])) ? $data['link_name'] : null;
        $this->message = (!empty($data['message'])) ? $data['message'] : null;
    }

    /**
     * Set input filter
     *
     * @param  InputFilterInterface $inputFilter
     * @return InputFilterAwareInterface
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
       throw new \Exception('metoda nie używana');
    }

    /**
     * Retrieve input filter
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
             $inputFilter = new InputFilter();
             $factory = new InputFactory();

             $inputFilter->add($factory->createInput(array(
                 'name' => 'link_name',
                 'require' => true,
                 'filters' => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 4,
                             'max'      => 70,
                         ),
                     ),
                 ),
             )));
             $inputFilter->add($factory->createInput(array(
                 'name' => 'message',
                 'require' => true,
                 'filters' => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name' => Validator\TextareaValidator::class,
                     ),
                 ),
             )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}