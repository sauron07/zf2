<?php
namespace User\Form;

use Zend\Form\Form;

class Registration extends Form
{
    public function __construct($name = null){

        parent::__construct('registration');
    }

    public function init()
    {
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'login',
            'type' => 'Text',
            'option' => array(
                'label' => 'Login',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'option' => array(
                'label' => 'Email',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Password',
            )
        ));
        $this->add(array(
            'type' => 'Csrf',
            'name' => 'csrfReg',
            'option' => array(
                'csrf_options' => array(
                    'timeout' => null
                )
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'options' => array(
                'label' => 'Submit'
            ),
        ));
    }
}