<?php

namespace Auth\Forms\Filters;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class LoginFilter implements InputFilterAwareInterface {
    public $login;
    public $pass;
    protected $inputFilter;
    
    public function __construct(){
        
    }
    public function exchangeArray($data) {
        $this->pass = (isset($data['pass'])) ? $data['pass'] : null;
        $this->login = (isset($data['login'])) ? $data['login'] : null;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }
    
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'login',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                             array(
                                'name' =>'NotEmpty', 
                                  'options' => array(
                                      'messages' => array(
                                          \Zend\Validator\NotEmpty::IS_EMPTY => 'Поле не может быть пустым!' 
                                      ),
                                  ),
                              ),
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 2,
                                    'max' => 20,
                                    'messages' => array(
                                        'stringLengthTooShort' => 'Слишком короткий пароль - минимум 3 символа!', 
                                        'stringLengthTooLong' => 'Слишком длинный пароль - максимум 20 символов!' 
                                    ),
                                ),
                            ),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'pass',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 2,
                                    'max' => 255,
                                ),
                            ),
                        ),
            )));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}


