<?php

namespace Auth\Forms;

use Zend\Captcha\Image;
use Zend\Form\Element;
use Zend\Form\Form;

class LoginForm extends Form {

    public function __construct() {

        parent::__construct('login-form');

        $login = new Element('login');
        $login->setLabel('Login');
        $login->setAttributes(array(
            'type' => 'text'
        ));

        $pass = new Element\Password('pass');
        $pass->setLabel('Password');

        $pass->setAttributes(array(
            'type' => 'password'
        ));
        
        $remember = new Element\Checkbox('remember');
        $remember->setLabel('Remember Me');
        $remember->setValue('1');

         $captcha = new Image(array(
            'font' => '/var/www/zator/public/fonts/1.ttf',
            'width' => 200,
            'height' => 75,
            'wordLen' => 3,
            'dotNoiseLevel' => 80,
            'lineNoiseLevel' => 30)
        );
        $captcha->setImgDir('/var/www/zator/public/captcha/images');
        $captcha->setFontSize(48);
        $captcha->setUseNumbers(true);
        $captcha->setImgUrl('/captcha/images');

        $captchaElement = new Element\Captcha('loginCaptcha');
        $captchaElement->setOptions(array(
            'label' => 'Please verify you are human',
            'captcha' => $captcha,
        ));
        $captchaElement->setAttribute('id', 'loginCaptchaElement');



        $send = new Element('send');
        $send->setValue(SUBMIT_TKN);
        $send->setAttributes(array(
            'type' => 'submit'
        ));

        //$this->add($captcha);
        $this->add($login);
        $this->add($pass);
        $this->add($captchaElement);
        $this->add($remember);
        $this->add($send);
    }

}