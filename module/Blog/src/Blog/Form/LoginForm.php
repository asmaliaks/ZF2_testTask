<?php

namespace Blog\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;

class LoginForm extends Form {
    
    public function __construct($name = null){
        parent::__construct('loginform');
        
        /* form elements */
        
        $this->add(array(
            'name'    => 'login',
            'type'    => 'Zend\Form\Element\Email',
            'options' => array(
                'label' => 'Login'
            ),
            'attributes' => array(
                'id'          => 'login',
                'placeholder' => 'Email',
                'class'       => 'form-control'
            )
        ));
        $this->add(array(
            'name'    => 'password',
            'type'    => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => 'Password'
            ),
            'attributes' => array(
                'id'          => 'pass',
                'placeholder' => 'Password',
                'class'       => 'form-control'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Войти',
                'class' => 'btn',
            ),
        ));
    }

}
