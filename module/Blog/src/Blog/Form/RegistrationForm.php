<?php

namespace Blog\Form;

use Zend\Form\Form;
use Zend\Form\Element;

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
                'placeholder' => 'Enter your pass',
                'class'       => 'form-control',
            )
        ));
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