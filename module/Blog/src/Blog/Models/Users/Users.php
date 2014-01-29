<?php
namespace Blog\Models\Users;

class Users {
    
    public $id;
    public $username;
    public $surname;
    public $email;
    public $pass;
    public $role;
    
    public function exchangeArray($data){
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->surname = (isset($data['surname'])) ? $data['surname'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->pass = (isset($data['pass'])) ? $data['pass'] : null;
        $this->role = (isset($data['role'])) ? $data['role'] : null;
    }
}
