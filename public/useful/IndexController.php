<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container;
use Auth\Models\User\User;
use Auth\Forms\Filters\RegFilter;
use Auth\Forms\Filters\LoginFilter;
use Auth\Forms\Filters\ErrorFilter;
use Auth\Models\Authentication;
use Auth\Models\Email\Email;
use Auth\Models\Session\Session;

class IndexController extends AbstractActionController {

    public function indexAction() {
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $this->redirect()->toRoute('home');
        }

        $user = new User();
        $form = $this->getServiceLocator()->get('registerForm');
        $form->bind($user);
        $formValid = true;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $regFilter = new RegFilter($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
            $form->setInputFilter($regFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $userTable = $this->getServiceLocator()->get('Auth\Model\User\UserTable');
                $bcrypt = new Bcrypt();
                $user->setPass($bcrypt->create($form->getInputFilter()->getValue('pass')));
                if ($userTable->saveUser($user)) {
                    $userObj = $userTable->getUserByLogin($form->getInputFilter()->getValue('login'));
                    $authObject = new Authentication($userTable, $userObj, $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
                    $login = $form->getInputFilter()->getValue('login');
                    $pass = $form->getInputFilter()->getValue('pass');
                    if ($authObject->authenticate($login, $pass)) {
                        $this->writeIdentityInSession($userObj);
                        $this->redirect()->toRoute('home');
                    }
                }
            } else {
                $formValid = false;
            }
        }


        $viewObj = new ViewModel(array(
            'content' => "Actual Content",
            'title' => 'Actual title',
            'registerForm' => $form,
            'errorFilter' => new ErrorFilter()
        ));

        return $viewObj;
    }

    public function loginAction() {
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $this->redirect()->toRoute('home');
        }


        $request = $this->getRequest();
        $form = $this->getServiceLocator()->get('loginForm');
        $cache = $this->getServiceLocator()->get('cache');
        $loginAttempts = $cache->getItem('loginAttempts' . str_replace('.', '-', $_SERVER['REMOTE_ADDR']));
        if (!$loginAttempts || $loginAttempts < 5) {
            $form->remove('loginCaptcha');
        }
        $errorMessage = false;
        if ($request->isPost()) {
            $loginAttempts = (!$loginAttempts) ? 1 : $loginAttempts + 1;
            $cache->setItem('loginAttempts' . str_replace('.', '-', $_SERVER['REMOTE_ADDR']), $loginAttempts);
            $form->setData($request->getPost());
            $loginFilter = new LoginFilter();
            $form->setInputFilter($loginFilter->getInputFilter());
            if ($form->isValid()) {

                $bcrypt = new Bcrypt();
                $userTable = $this->getServiceLocator()->get('Auth\Model\User\UserTable');
                $userObj = $userTable->getUserByLogin($form->getInputFilter()->getValue('login'));

                if ($userObj) {
                    if ($bcrypt->verify($form->getInputFilter()->getValue('pass'), $userObj->getPass())) {
                        $authObj = new Authentication($userTable, $userObj, $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
                        $login = $form->getInputFilter()->getValue('login');
                        $pass = $form->getInputFilter()->getValue('pass');
                        if ($authObj->authenticate($login, $pass)) {
                            $cache->setItem('loginAttempts' . str_replace('.', '-', $_SERVER['REMOTE_ADDR']), false);
                            $this->writeIdentityInSession($userObj, $this->getRequest()->getPost('remember'));
                            $this->redirect()->toRoute('office-list');
                        } else {
                            echo 'could not authenticate';
                        }
                    } else {
                        $errorMessage = true;
                    }
                } else {
                    $errorMessage = true;
                }
            }
        }
        $viewObj = new ViewModel(array(
            'content' => "Actual Content",
            'title' => 'Actual title',
            'registerForm' => $form,
            'errorMessage' => $errorMessage,
            'errorFilter' => new ErrorFilter()
        ));

        return $viewObj;
    }

    public function logoutAction() {
        $auth = new AuthenticationService();
        $auth->clearIdentity();
        $sessionTable = $this->getServiceLocator()->get('Home\Model\Session\SessionTable');
        $sessionContainer = new Container('sessionId');
        $sessionId = $sessionContainer->sessionId;
        $sessionTable->deleteSession($sessionId);
        $this->redirect()->toRoute('home');
    }

    public function remindAction() {
        $this->getServiceLocator()->get('viewhelpermanager')
                ->get('HeadScript')->appendFile('/js/office/auth.js');
        $viewObj = new ViewModel(array(
        ));

        return $viewObj;
    }

    public function remindajaxAction() {
        $login = $this->getRequest()->getPost('login');
        $userTable = $this->getServiceLocator()->get('Auth\Model\User\UserTable');

        $user = false;
        if ($login != null) {
            $user = $userTable->getUserByLogin($login);
            if ($user) {
                $this->createRemindPassEmail($user);
            }
        } else {
            $email = $this->getRequest()->getPost('email');
            $users = $userTable->getUserByEmail($email);
            /**
             * send emails
             */
            if (count($users) > 0) {
                foreach ($users as $user) {
                    $this->createRemindPassEmail($user);
                }
            }
        }


        $viewObj = new ViewModel(array(
            'user' => $user
        ));
        $viewObj->setTerminal(true);
        return $viewObj;
    }

    public function passrecoveryAction() {
        $this->getServiceLocator()->get('viewhelpermanager')
                ->get('HeadScript')->appendFile('/js/office/auth.js');
        $hash = $this->getEvent()->getRouteMatch()->getParam('hash');
        $sessionTable = $this->getServiceLocator()->get('Auth\Model\Session\SessionTable');
        $sessionObj = $sessionTable->getSessionByHash($hash);
        if ($sessionObj) {
            $userTable = $this->getServiceLocator()->get('Auth\Model\User\UserTable');
            $user = $userTable->getUser($sessionObj->getUserId());
            if ($user) {
                $authObj = new Authentication($userTable, $user, $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
                if ($authObj->authenticateInApp($user->getLogin(), $user->getPass())) {
                    $this->writeIdentityInSession($user);
                    $this->redirect()->toUrl('/home/office/account');
                }
            }
        }
        $viewObj = new ViewModel(array(
        ));
        return $viewObj;
    }
    
    public function rmmarketingAction(){
        echo 'rm';
        exit;
    }

    private function writeIdentityInSession($userObj, $remember = null) {
        /**
         * write all db session
         */
        $sessionTable = $this->getServiceLocator()->get('Auth\Model\Session\SessionTable');
        $sessionContainer = new Container('sessionId');
        $sessionId = $sessionContainer->sessionId;
        $session = $sessionTable->getSession($sessionId);
        $session->setUserId($userObj->_id);
        $session->setUserIp($_SERVER['REMOTE_ADDR']);
        $session->setRemember(($remember == null) ? 0 : 1);
        $sessionTable->saveSession($session);
        if ($remember != null) {
            setcookie('sessionHash', $session->getHash(), time() + 60 * 60 * 24 * 30, '/');
        }
    }

    private function createRemindPassEmail(User $user) {
        $emailObj = new Email();
        $sessionTable = $this->getServiceLocator()->get('Auth\Model\Session\SessionTable');
        $session = new Session();
        $session->setUserId($user->_id);
        $session->setUserIp($_SERVER['REMOTE_ADDR']);
        $sessionId = $sessionTable->saveSession($session);
        $sessionObj = $sessionTable->getSession($sessionId);
        $hash = $sessionObj->getHash();
        $message = PASSWORD_RECOVERY_TKN_1 . "http://" . $_SERVER['HTTP_HOST'] . "/auth/passrecovery/" . $hash . "/" . PASSWORD_RECOVERY_TKN_2;
        $emailObj->sendMessage($user->getEmail(), $message, PASSWORD_RECOVERY_SUBJ_TKN);
    }

}
