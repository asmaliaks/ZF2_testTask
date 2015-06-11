<?php

namespace Auth\Controller;

use Auth\Forms\LoginForm;
use Auth\Forms\Filters\LoginFilter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class IndexController extends AbstractActionController
{


    public function loginAction() {
        $auth = new AuthenticationService();
        if($auth->hasIdentity()){
            $this->redirect()->toRoute('success-page');
        }

        $form = new LoginForm();
        $request = $this->getRequest();
        if($request->isPost()){
            
            $loginFilter = new LoginFilter();
            $form->setInputFilter($loginFilter->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid()){
                $data = $form->getData();
                $sm = $this->getServiceLocator();
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                $authAdapter = new AuthAdapter($dbAdapter,
                        'users',
                        'email',
                        'pass'
                        );
                $authAdapter->setIdentity($data['login'])
                            ->setCredential($data['pass']);
                $auth = new AuthenticationService();
                $result = $auth->authenticate($authAdapter);

                switch ($result->getCode()) {

                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        $authError = 'Указанный E-mail не зарегистрирован';
                        break;

                    case Result::FAILURE_CREDENTIAL_INVALID:
                        $authError = 'Неверный пароль';
                        break;

                    case Result::SUCCESS:
                        $this->redirect()->toRoute('success-page');
                        break;

                    default:
                        $authError = null;
                        break;
                }
            }
        }
        return new ViewModel(array(
            'form'       => $form,
            'authError' => $authError ,
        ));
                
        
    }
    
    public function successPageAction(){
        
    }
    
    public function logoutAction(){
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
	    $auth->clearIdentity();
	}
        $this->redirect()->toRoute('auth');
    }
}