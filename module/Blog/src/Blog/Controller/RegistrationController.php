<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Forms\RegistrationForm;
use Blog\Forms\Filters\RegistrationFilter;
use Blog\Models\Users\Users;

class RegistrationController extends AbstractActionController {
    
    protected $usersTable;
    
    public function indexAction(){
        $form = new RegistrationForm();
        $request = $this->getRequest();
        if($request->isPost()){
            $usersObj = new Users();
          
            $regFilter = new RegistrationFilter();
            $form->setInputFilter($regFilter->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid()){
                $newUserData = $form->getData();
                $usersObj->exchangeArray($newUserData);
                $result = $this->getUsersTable()->addUser($usersObj);
                if($result){
                    return $this->redirect()->toRoute('register-success');
                }
            }
        }
        $viewObj = new ViewModel(array(
            'form' => $form
        ));

        return $viewObj;
    }
    
    public function successPageAction(){
        
    }
    
    private function getUsersTable(){
         if (!$this->usersTable) {
             $sm = $this->getServiceLocator();
             $this->usersTable = $sm->get('Blog\Models\Users\UsersTable');
         }
         return $this->usersTable;        
    }
}
