<?php

namespace Facebook\Form;

use Zend\Form\Form;

class FacebookForm extends Form
{

    /**
     * FacebookForm constructor.
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct('facebook');
        $this->setAttributes(array(
            'method' => 'post',
        ));
        $this->add(array(
            'name' => 'link_name',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
               'class' => 'input_link',
            ),
        ));
        $this->add(array(
            'name' => 'message',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
               'class' => 'textarea_message',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
               'attributes' => array(
                  'value' => 'Zapisz',
                  'id' => 'submit_btn',
                  'class' => 'btn btn-primary',
               ),
        ));
    }
}