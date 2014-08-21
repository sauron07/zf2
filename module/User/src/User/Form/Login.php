<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 7/27/14
 * Time: 12:52 PM
 */

namespace User\Form;


use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceManager;

class Login extends Form implements InputFilterProviderInterface
{
    private $filters = [
        ['name' => 'StripTags'],
        ['name' => 'StringTrim'],
        ['name' => 'HtmlEntities']
    ];

    public function __construct()
    {
        parent::__construct('login');

        $this->setAttributes([
                'method' => 'post',
                'class' => 'form-inline'
            ]
        );

        $this->add([
            'name' => 'login',
            'type' => 'Text',
            'options' => [
                'label' => 'Login'
            ]
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'Password',
            'options' => [
                'label' => 'Password',
            ]
        ]);

        $this->add([
            'name' => 'rememberMe',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => [
                'label' => 'Remember me',
                'checked_value' => true,
                'unchecked_value' => false,
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'value' => 'Login',
                'class' => 'btn'
            ]
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'login' => [
                'required' => true,
                'filters' => $this->filters,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                    ],
                ],
                'break_chain_on_failure' => true,
            ],
            'password' => [
                'required' => true,
                'filters' => $this->filters,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                    ],
                ],
                'break_chain_on_failure' => true,
            ],
            'rememberMe' => [
                'required' => false,
                'break_chain_on_failure' => true,
            ],
        ];
    }
}