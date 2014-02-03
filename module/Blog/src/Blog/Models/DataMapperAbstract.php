<?php

namespace Blog\Models;

use Zend\Db\Adapter\Adapter;

abstract class DataMapperAbstract{
    
    protected $_dbCon;
    /**
     * 
     * @param \Zend\Db\Adapter\Adapter $dbAdapter
     */
    public function __construct(Adapter $dbAdapter){
        $this->dbCon = $dbAdapter;
    }
}