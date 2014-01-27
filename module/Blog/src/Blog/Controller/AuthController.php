<?php


namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Forms\LoginForm;
use Blog\Forms\Filters\LoginFilter;


class AuthController extends AbstractActionController {
    
    
  public function indexAction() {
      $form = new LoginForm();   
      $request = $this->getRequest();
      if($request->isPost()){
          $form->setData($request->getPost());
          $loginFilter = new LoginFilter();          
          $form->setInputFilter($loginFilter->getInputFilter());
          
          if($form->isValid()){
             $errorMessage = false;
          }else{
             $errorMessage = true;
          }
          
          
      }
      return array(
          'form' => $form
          );
      
}

}

