<?php


namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Form\LoginForm;


class AuthController extends AbstractActionController {
    
    
  public function indexAction() {
      $form = new LoginForm();   
      return array('form' => $form);
      
}

}

