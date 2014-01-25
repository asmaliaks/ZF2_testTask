<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Form\RegistrationForm;

class RegistrationController extends AbstractActionController {
    
    public function indexAction(){
        $form = new RegistrationForm();
        //$form = $this->getServiceLocator()->get('registrationform');
        return array('form' => $form);
    }
}
