<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Forms\RegistrationForm;
use Blog\Forms\Filters\RegistrationFilter;

class RegistrationController extends AbstractActionController {
    
    public function indexAction(){
        $form = new RegistrationForm();
        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            $regFilter = new RegistrationFilter();
            $form->setInputFilter($regFilter->getInputFilter());
            
            if($form->isValid()){
                
            }else{
                
            }
        }
        $viewObj = new ViewModel(array(
            'form' => $form
            
        ));
//        $form = $this->getServiceLocator()->get('registrationform');
        return $viewObj;
    }
}
