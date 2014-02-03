<?php
namespace Blog\Forms;

use Zend\Form\Form;
use Zend\Form\Element;

class PostForm extends Form {
    
  public function __construct($name = null){
        parent::__construct('postform');
        
        $title = new Element('title');
        $title->setLabel('Название');
        $title->setAttributes(array(
            'type' => 'text',
            'class' => 'form-control'
        ));
        
        $body = new Element('body');
        $body->setLabel('Текст вашей статьи');
        $body->setAttributes(array(
            'type' => 'textarea',
            'class' => 'form-control'
        ));
        
        $submit = new Element('submit');
        $submit->setValue('Отправить');
        $submit->setAttributes(array(
            'type' => 'submit',
            'class' => 'btn regular-btn'
        ));
        
        $this->add($title);
        $this->add($body);
        $this->add($submit);
}
}