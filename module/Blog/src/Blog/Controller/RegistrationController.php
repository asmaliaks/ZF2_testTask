<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Forms\RegistrationForm;
use Blog\Forms\Filters\RegistrationFilter;
use Blog\Models\Users\Users;
use Blog\Models\Users\UsersTable;

class RegistrationController extends AbstractActionController {
    
    protected $usersTable;
    
    public function indexAction(){
        $form = new RegistrationForm();
        $request = $this->getRequest();
        if($request->isPost()){
            $newUser = new Users();
          
            $regFilter = new RegistrationFilter();
            $form->setInputFilter($regFilter->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid()){
                $newUserData = $form->getData();
                $newUserData['role'] = 0;
                $newUser->exchangeArray($newUserData);
                $this->getUsersTable()->addUser($newUser);
                
                return $this->redirect()->toRoute('index');
            }else{
                
            }
        }
        $viewObj = new ViewModel(array(
            'form' => $form
        ));
//        $form = $this->getServiceLocator()->get('registrationform');
        return $viewObj;
    }
    
    public function registerNewUserAction(){

    }
    public function editUserDataAction(){
        
    }
    
    public function removeUserAction(){
        
    }
    
    public function userListAction(){
        return new ViewModel(array(
            'users' => $this->getUsersTable()->fetchAll(),
        ));
    }
    
    private function getUsersTable(){
         if (!$this->usersTable) {
             $sm = $this->getServiceLocator();
             $this->usersTable = $sm->get('Blog\Models\Users\UsersTable');
         }
         return $this->usersTable;        
    }
}
