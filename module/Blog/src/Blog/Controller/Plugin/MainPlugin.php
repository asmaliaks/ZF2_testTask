<?php

namespace Blog\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Config\Reader\Ini;
use Blog\Models\Acl\AclClass;
//use Event\Models\Store\StoreDesign;

class MainPlugin extends AbstractPlugin {
     
    /**
     * @var AclClass
     */
    protected $_aclClass = null;

    /**
     *
     * @var type 
     */
    protected $_event;

    /**
     *
     * @var ViewModel 
     */
    protected $_viewModel;

    /**
     *
     * @var EventManager 
     */
    protected $em;

    /**
     *
     * @var ServiceManager 
     */
    protected $_sm;

    /**
     *
     * @var Service 
     */
    protected $_service;

    /**
     *
     * @var Store 
     */
    protected $_store;

    /**
     *
     * @var StoreDesign 
     */
    protected $_storeDesign;

    /**
     *
     * @var Cache 
     */
    protected $_cache;

    /**
     * 
     * @return MainPlugin
     */
    public function preDispatch() {
        return $this;
    }

    public function defineACL(MvcEvent $event, $config) {


        $this->_event = $event;
        /**
         * put some code if needed
         */
        $this->_viewModel = $event->getViewModel();
        $modNameArray = explode('\\', $event->getRouteMatch()->getParam('controller'));
        $modName = strtolower($modNameArray[0]);
        if ($modName != 'event') {
            $this->_viewModel->setTemplate($modName . '/layout');
        }
        //@todo - Should we really use here and Controller Plugin?
        $userAuth = new AuthenticationService();
        $this->_aclClass = $this->getAclClass($config);
        $role = $this->defineRole($userAuth, $modName);
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');
        if (!$this->_aclClass->hasResource($controller)) {
            throw new \Exception('Resource ' . $controller . ' not defined');
        }
        if (!$this->_aclClass->isAllowed($role, $controller, $action)) {
            $url = $event->getRouter()->assemble(array(), array('name' => 'login'));
            $resp = $this->_event->getResponse();
            $resp->getHeaders()->addHeaderLine('Location', $url);
            $resp->setStatusCode(302);
            $resp->sendHeaders();
            var_dump($resp->getHeaders()); //->plugin('redirect')->toUrl($url);
            exit;
        }
    }

    public function getUserAuthenticationPlugin() {
        if ($this->_userAuth === null) {
            $this->_userAuth = new AuthPlugin();
        }
        return $this->_userAuth;
    }

    public function getAclClass($config) {
        if ($this->_aclClass === null) {
            $this->_aclClass = new AclClass($config['acl-config']["parameters"]['config']);
        }
        return $this->_aclClass;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager() {
        return $this->_sm->getServiceLocator();
    }

    private function defineRole($userAuth, $modName) {
        $this->_sm = $this->_event->getApplication()->getServiceManager();

        $sessionTable = $this->_sm->get('Blog\Model\Session\SessionTable');
        $sessionTable->processSession();
        /*
         * get serviceId from the session
         */
        $sessionContainer = new Container('serviceId');
        $serviceId = $sessionContainer->serviceId;

        if (isset($serviceId) && $serviceId != 0) {
            $this->cacheService($serviceId);
        } else {
            //get urlName from domain
            $this->defineServiceFromDomain();
        }
        $lang = $this->defineLangCostants();
        $this->getStoreDesignLangFromCache($lang);
        $this->_sm->setService('lang', $lang);
        /*
         * module specific constants
         */
        $this->defineModuleLangConstants($modName, $lang);

        if ($userAuth->hasIdentity()) {
            $role = 'user';
            $user = $userAuth->getIdentity();
            if ($user->login == 'shopizator')
                return 'superadmin';
            if (isset($serviceId) && $serviceId != 0 && $modName != 'home' && $modName != 'auth') {
                /**
                 * define role here
                 */
                $roleTable = $this->_sm->get('Blog\Model\Role\RoleTable');
                $roles = $roleTable->getServicesForUser($user->id);
                foreach ($roles as $roleObj) {
                    if ($roleObj->getServiceId() == $serviceId) {
                        $role = $roleObj->getRole();
                    }
                }
            }
        } else {
            $role = AclClass::DEFAULT_ROLE;
        }

        return $role;
    }

}
