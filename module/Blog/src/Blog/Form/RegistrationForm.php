<?php

namespace Blog\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Captcha\Image;


class RegistrationForm extends Form {
    public function    __construct($name = null){
        parent::__construct('registrationform');
        
        /*form elements*/
        
        $this->add(array(
            'name'    => 'name',
            'type'    => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Full Name'
            ),
            'attributes' => array(
                'id'          => 'full_name',
                'placeholder' => 'Full Name',
                'class'       => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'surname',
            'type'    => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Full Name'
            ),
            'attributes' => array(
                'id'          => 'surname',
                'placeholder' => 'Surname',
                'class'       => 'form-control'
            )
        ));
        
        $this->add(array(
            'name'    => 'login',
            'type'    => 'Zend\Form\Element\Email',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'id'          => 'email',
                'placeholder' => 'Email',
                'class'       => 'form-control'
            )
         ));
        
        $this->add(array(
            'name'    => 'pass',
            'type'    => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
                'id'          => 'pass',
                'placeholder' => 'Enter your pass',
                'class'       => 'form-control',
            )
        ));
        $this->add(array(
            'name'    => 'pass_re_enter',
            'type'    => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
                'id'          => 'pass_re',
                'placeholder' => 'ReEnter your pass',
                'class'       => 'form-control',
            )
        ));
         $captcha = new Image(array(
            'font' => '/var/www/zf2/public/fonts/1.ttf',
            'width' => 200,
            'height' => 75,
            'wordLen' => 3,
            'dotNoiseLevel' => 80,
            'lineNoiseLevel' => 30)
        );
        $captcha->setImgDir('/var/www/zf2/public/captcha/images');
        $captcha->setFontSize(45);
        $captcha->setUseNumbers(true);
        $captcha->setImgUrl('/captcha/images');


        $captchaElement = new Element\Captcha('captcha');
        $captchaElement->setOptions(array(
            'label' => 'Please verify you are human',
            'captcha' => $captcha,
        ));
        
        $captchaElement->setAttributes(array(
            'id' => 'registrCaptchaElement',
            'class' => 'form-control',
            'placeholder' => 'Enter the coe here',
            'style' => 'margin-top: 10px'
        ));
        $this->add($captchaElement);
        

        $this->add(array(
            'name'    => 'submit',
            'type'    => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'submit',
                'value' => 'Зарегестрироваться',
                'class' => 'btn regular-btn'
            )
          ));
        
        
    }
}