<?php
 
namespace Blog\Models;

abstract class DomainModelAbstract{
   
    protected $_id;
    
    public function getId(){
        return $this->_id;
    }
    
}

