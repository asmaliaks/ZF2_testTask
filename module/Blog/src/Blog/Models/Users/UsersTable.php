<?php

namespace Blog\Models\Users;


use Zend\Db\TableGateway\TableGateway;

class UsersTable {
    
    protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function addUser(Users $user)
     {
         $data = array(
             'full_name' => $user->full_name,
             'surname'  => $user->surname,
             'email'    => $user->login,
             'pass'     => $user->pass,
         );


        return $this->tableGateway->insert($data);

     }


 }