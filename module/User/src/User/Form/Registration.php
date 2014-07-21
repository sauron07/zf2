<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Captcha\Image as CaptchaImage;

class Registration extends Form implements InputFilterProviderInterface
{
    protected $captcha;

    public function setCaptcha(CaptchaAdapter $captcha){
        $this->captcha = $captcha;
    }

    public function __construct($name = null, $captchaUrl = null)
    {
        parent::__construct('register');
        $this->setAttribute('method', 'post');

        $captchaImage = new CaptchaImage([
            'font' => APPLICATION_PATH. '/fonts/Loma.ttf',
            'width' => 150,
            'height' => 50,
            'wordLen' => 5,
            'dotNoiseLevel' => 5,
            'lineNoiseLevel' => 5,
            'fontSize' => 18
        ]);
        $captchaImage->setGcFreq(3);
        $captchaImage->setImgDir(APPLICATION_PATH . '/img/captcha/');
        $captchaImage->setImgUrl('/img/captcha/');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'login',
            'type' => 'Text',
            'options' => array(
                'label' => 'Login',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'options' => array(
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
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'options' => array(
                'label' => 'Please verify you are human.',
                'captcha' => $captchaImage
            ),
        ));
        $this->add(array(
            'type' => 'Csrf',
            'name' => 'csrfReg',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => null
                )
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Submit'
            ),
        ));
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception ('Not used');
    }

    public function getInputFilterSpecification()
    {
        return [
            'login' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                    ]
                ]
            ],
//            'email' => [
//                'required' => true,
//                'validators' => [
//                    [
//                        'name' => 'NotEmpty',
//                    ],
//                    [
//                        'name' => 'EmailAddress',
//                    ]
//                ]
//            ],
//            'password' => [
//                'required' => true,
//                'validators' => [
//                    [
//                        'name' => 'NotEmpty',
//                    ],
//                    [
//                        'name' => 'StringLength',
//                        'options' => [
//                            'min' => 5,
//                            'message' => 'Password wast be longer then 5 chars'
//                        ]
//                    ]
//                ]
//            ]
        ];
    }
}