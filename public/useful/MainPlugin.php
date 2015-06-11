<?php

namespace Home\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Config\Reader\Ini;
use Home\Models\Acl\AclClass;
use Event\Models\Store\StoreDesign;

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

        $sessionTable = $this->_sm->get('Home\Model\Session\SessionTable');
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
                $roleTable = $this->_sm->get('Home\Model\Role\RoleTable');
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

    public function cacheService($serviceId, $isStore = false) {
        $serviceTable = $this->_sm->get('Home\Model\Service\ServiceTable');
        $this->_cache = $this->_sm->get('cache');
        $service = $serviceTable->getService($serviceId);
        if (!$service) {
            return;
        }

        $this->_service = $service;
//        $flushTime = $this->_cache->getItem('flushTime' . $serviceId);
//        if ($flushTime == null) {
//            $this->flushServiceCache();
//        } else {
//            if ($service->getTimeUpdated() > $flushTime) {
//                $this->flushServiceCache();
//            }
//        }

        $storeTable = $this->_sm->get('Event\Model\Store\StoreTable');
        $storeDesignTable = $this->_sm->get('Event\Model\Store\StoreDesignTable');
        $this->_store = $storeTable->getStoreByServiceId($serviceId);


        $this->_storeDesign = $storeDesignTable->getStoreDesign($this->_store->getStoreDesign());
        if (!$this->_storeDesign) {
            $this->_storeDesign = new StoreDesign();
            $this->_storeDesign->setServiceId($serviceId);
            $this->_storeDesign->setLang($this->_store->getDefaultLang());
            $this->_storeDesign->_id = $storeDesignTable->saveStoreDesign($this->_storeDesign);
            $this->_store->setStoreDesign($this->_storeDesign->_id);
            $storeTable->saveStore($this->_store);
        }


        $this->_cache->setItem('domain' . $serviceId, $isStore);
        $this->_viewModel->storeDesign = $this->_storeDesign;
        $this->_viewModel->storeObj = $this->_store;
        $this->_viewModel->serviceObj = $service;
        $this->_cache->setItem('service' . $serviceId, $service);
        return $service;
    }

    private function flushServiceCache() {
        $this->_cache->setItem('flushTime' . $this->_service->_id, time());
    }

    private function defineServiceFromDomain() {

        $hostNameArray = explode('.', str_replace("www.", "", $_SERVER['HTTP_HOST']));
        /**
         * check if this service has separate domain name
         */
        $domainTable = $this->_sm->get('Event\Model\Domain\DomainTable');
        $domain = $domainTable->getDomainByDomainName($_SERVER['HTTP_HOST']);
        $serviceTable = $this->_sm->get('Event\Model\Service\ServiceTable');
        $sessionContainer = new Container('serviceId');
        if ($domain) {
            $domainService = $serviceTable->getService($domain->getServiceId());
            if ($domainService) {
                $this->cacheService($domainService->_id, true);
                $sessionContainer->serviceId = $domainService->_id;
                return;
            }
        }
        /**
         * get urlName from subdomain
         */
        $service = $serviceTable->getServiceByUrlName($hostNameArray[0]);
        // get servicetable
        if ($service) {
            $this->cacheService($service->_id);
            $sessionContainer->serviceId = $service->_id;
        }
        return;
    }

    private function defineModuleLangConstants($modName, $lang) {
        $reader = new Ini();
        if ($modName != 'home') {
            return;
        }

        $data = $reader->fromFile($_SERVER["DOCUMENT_ROOT"] . '/langs/module/' . $modName . "/" . $lang . '_constants.ini');
        foreach ($data as $constName => $constantVal) {
            if (!defined($constName)) {
                define($constName, $constantVal);
            }
        }
    }

    private function defineLangCostants($lang = null) {
        $serviceType = "store";
        if (!isset($_COOKIE['storelang']) && ($lang == null)) {
            if (isset($this->_store)) {
                $lang = $this->_store->getDefaultLang();
            } elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            } else {
                $lang = "en";
            }
        } else {
            $lang = $_COOKIE['storelang'];
        }

        if ($lang != 'en' &&
                $lang != 'ru' &&
                $lang != 'lt' &&
                $lang != 'lv' &&
                $lang != 'de' &&
                $lang != 'pl') {
            $lang = "en";
        }

        if (!isset($_COOKIE['storelang'])) {
            setcookie('storelang', $lang, time() + 360000000, '/');
        }

        if (isset($this->_store)) {
            switch ($this->_service->getType()) {
                case 1: $serviceType = 'store';
                    break;
                case 2: $serviceType = 'cafee';
                    break;
                default :
                    break;
            }
        }
        $reader = new Ini();
        $data = $reader->fromFile($_SERVER["DOCUMENT_ROOT"] . '/langs/' . $lang . '_constants.ini');
        $data = array_merge($data, $reader->fromFile($_SERVER["DOCUMENT_ROOT"] . '/langs/' . $lang . "_" . $serviceType . '_constants.ini'));
        foreach ($data as $constName => $constantVal) {
            if (!defined($constName)) {
                define($constName, $constantVal);
            }
        }
        $this->_viewModel->lang = $lang;
        /**
         * get store designs according to default lang
         */
        return $lang;
    }

    private function getStoreDesignLangFromCache($lang) {
        if (!is_object($this->_service) || !$this->_service)
            return;
        $sDLang = $this->_cache->getItem('storeDesign' . $lang . $this->_service->_id);

        if ($sDLang == null) {
            $storeDesignLangTable = null;
            switch ($lang) {
                case 'ru': $storeDesignLangTable = $this->_sm->get('Event\Model\Store\StoreDesignRuTable');
                    break;
                case 'en':$storeDesignLangTable = $this->_sm->get('Event\Model\Store\StoreDesignEnTable');
                    break;
                case 'lv':$storeDesignLangTable = $this->_sm->get('Event\Model\Store\StoreDesignLvTable');
                    break;
                case 'lt':$storeDesignLangTable = $this->_sm->get('Event\Model\Store\StoreDesignLvTable');
                    break;
                case 'pl':$storeDesignLangTable = $this->_sm->get('Event\Model\Store\StoreDesignLvTable');
                    break;
                case 'de':$storeDesignLangTable = $this->_sm->get('Event\Model\Store\StoreDesignLvTable');
                    break;

                default:
                    break;
            }
            $sDLang = $storeDesignLangTable->getSDForService($this->_storeDesign);
            $this->_cache->setItem('storeDesign' . $lang . $this->_service->_id, $sDLang);
        }
        $this->_storeDesign->setLanguageStoreDesign($sDLang);
        $this->_cache->setItem('storeDesign' . $this->_store->getServiceId(), $this->_storeDesign);
        $this->_store->setCurrentLang($lang);
        $this->_cache->setItem('store' . $this->_service->_id, $this->_store);
    }

}
