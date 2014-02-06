<?php

namespace Auth\Controller;


use Auth\Forms\LoginForm;
use Auth\Forms\Filters\LoginFilter;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

use Zend\Db\Adapter\Adapter as DbAdapter;

use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class IndexController extends AbstractActionController
{

    public function simauthAction(){
        
        $auth = new AuthenticationService();
        //echo '<h1> hasIdentity = '. print_r($auth->hasIdentity()).'</h1>';
        if(!$auth->hasIdentity()){
           $this->redirect()->toRoute('blogPost');
        }   

        
    }
    public function loginAction() {
        $auth = new AuthenticationService();
        if($auth->hasIdentity()){
            $this->redirect()->toRoute('blogPost');
        }
       // $user = $this->identity();
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
                
                $config = $this->getServiceLocator()->get('Config');
                
                
                
                $authAdapter = new AuthAdapter($dbAdapter,
                        'users',
                        'email',
                        'pass'
                       // "MD5(CONCAT('$static_salt', ?, pass_salt)) AND usr_active = 1"
                        );
                $authAdapter->setIdentity($data['login'])
                            ->setCredential($data['pass']);
                $auth = new AuthenticationService();
                $result = $auth->authenticate($authAdapter);

switch ($result->getCode()) {

    case Result::FAILURE_IDENTITY_NOT_FOUND:
        /** do stuff for nonexistent identity **/
        break;

    case Result::FAILURE_CREDENTIAL_INVALID:
        /** do stuff for invalid credential **/
        break;

    case Result::SUCCESS:
        /** do stuff for successful authentication **/
        
        $this->redirect()->toRoute('blogPost');
        break;

    default:
        /** do stuff for other failure **/
        break;
}
            }
        }
                
        return new ViewModel(array(
            'form' => $form
        ));
    }
    public function logoutAction(){
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
	    $auth->clearIdentity();
	}
        $this->redirect()->toRoute('auth');
    }
}