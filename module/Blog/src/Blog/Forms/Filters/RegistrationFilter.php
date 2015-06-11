<?php

namespace Blog\Forms\Filters;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class RegistrationFilter implements InputFilterAwareInterface {
    
    public $fullName;
    public $surname;
    public $email;
    public $password;
    protected $inputFilter;
    
    public function __construct(){
        
    }
    
    public function exchangeArray($data){
        $this->fullName = (isset($data['full_name'])) ? $data['full_name'] : null;
        $this->surname = (isset($data['surname'])) ? $data['surname'] : null;
        $this->email = (isset($data['login'])) ? $data['login'] : null;
        $this->password = (isset($data['pass'])) ? $data['pass'] : null;
    }
    
    public function getArrayCopy(){
        return get_object_vars($this);
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }
    
    public function getInputFilter(){
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'full_name',
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
                            'min' => 3,
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
                'name' => 'surname',
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
                            'min' => 3,
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
                'name' => 'login',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    
                    array (
                        'name' => 'EmailAddress',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\EmailAddress::INVALID_FORMAT => "Некорректный адресс",
                            )
                        ),
                    ),
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
                            'min' => 3,
                            'max' => 255,
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
                            'min' => 4,
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
                'name' => 'pass_re_enter',
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
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'pass', // name of first password field
                            'message' => 'пароли не совпадают'
                            
                        ),
                    ),
                ),
                )));
            $inputFilter->add($factory->createInput(array(
                'name' => 'captcha',
                'validators' => array(
                    array(
                       'name' =>'NotEmpty', 
                         'options' => array(
                             'messages' => array(
                                 \Zend\Validator\NotEmpty::IS_EMPTY => 'Докажите что вы не робот!' 
                             ),
                         ),
                     ),
                    
                ),
                )));

            
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }    
}