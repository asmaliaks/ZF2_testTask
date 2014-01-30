<?php
namespace Blog\Models\Users;

class Users {
    
    
    public $username;
    public $surname;
    public $email;
    public $pass;
    public $role;
    
    public function exchangeArray($data){
       
        $this->username = (isset($data['full_name'])) ? $data['full_name'] : null;
        $this->surname = (isset($data['surname'])) ? $data['surname'] : null;
        $this->email = (isset($data['login'])) ? $data['login'] : null;
        $this->pass = (isset($data['pass'])) ? $data['pass'] : null;
        $this->role = (isset($data['role'])) ? $data['role'] : null;
    }
}
