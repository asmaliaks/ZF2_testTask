<?php
namespace Blog\Forms\Filters;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class PostFilter implements InputFilterAwareInterface {
    
    public $title;
    public $text;
    protected $inputFilter;
    
    public function __construct(){
        
    }
    
    public function exchangeArray($data) {
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->text = (isset($data['text'])) ? $data['text'] : null;
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
                        'name' => 'title',
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

            $inputFilter->add($factory->createInput(array(
                        'name' => 'text',
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
                                ),
                            ),
                        ),
            )));

//            $inputFilter->add($factory->createInput(array(
//                        'name' => 'remember',
//                        'required' => false,
//            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }    
}
