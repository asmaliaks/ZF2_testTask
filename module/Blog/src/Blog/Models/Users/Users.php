<?php
namespace Blog\Models\Users;

class Users {
    
    
    public $full_name;
    public $surname;
    public $login;
    public $pass;
    
    public function exchangeArray($data){
       
        $this->full_name = (isset($data['full_name'])) ? $data['full_name'] : null;
        $this->surname = (isset($data['surname'])) ? $data['surname'] : null;
        $this->login = (isset($data['login'])) ? $data['login'] : null;
        $this->pass = (isset($data['pass'])) ? $data['pass'] : null;
    }
}
