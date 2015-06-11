<?php

namespace Blog\Forms;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Captcha\Image;


class RegistrationForm extends Form {
    public function    __construct($name = null){
        parent::__construct('registrationform');
        
        /*form elements*/
        
        $this->add(array(
            'name'    => 'full_name',
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
                'label' => 'surname'
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
                'id'          => 'login',
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
                'id'          => 'pass_re_enter',
                'placeholder' => 'Confirm your password',
                'class'       => 'form-control',
            )
        ));
         $captcha = new Image(array(
            'font' => $_SERVER['DOCUMENT_ROOT'].'/fonts/1.ttf',
            'width' => 200,
            'height' => 75,
            'wordLen' => 3,
            'dotNoiseLevel' => 80,
            'lineNoiseLevel' => 30)
        );
        $captcha->setImgDir($_SERVER['DOCUMENT_ROOT'].'/captcha/images');
        $captcha->setFontSize(30);
        $captcha->setUseNumbers(true);
        $captcha->setImgUrl('/captcha/images');


        $captchaElement = new Element\Captcha('captcha');
        $captchaElement->setOptions(array(
            'label' => 'Введите код',
            'captcha' => $captcha,
        ));
        
        $captchaElement->setAttributes(array(
            'id' => 'registrCaptchaElement',
            'class' => 'form-control',
            'placeholder' => 'Enter the code here',
            'style' => 'margin-top: 10px'
        ));
        $this->add($captchaElement);
        

        $this->add(array(
            'name'    => 'submit',
            'type'    => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'submit',
                'value' => 'Register',
                'class' => 'btn regular-btn'
            )
          ));
        
        
    }
}