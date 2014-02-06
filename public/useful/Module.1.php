<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Home;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Home\Models\Service\Service;
use Home\Models\Service\ServiceTable;
use Home\Models\Role\Role;
use Home\Models\Role\RoleTable;
use Home\Models\Session\Session;
use Home\Models\Session\SessionTable;
use Home\Models\User\User;
use Home\Models\User\UserTable;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach('route', array($this, 'attachPlugin'));


        /*
         * pass nesseccary info to the layout
         */
        $userAuth = new AuthenticationService();
        $application = $e->getParam('application');
        $viewModel = $application->getMvcEvent()->getViewModel();
        $viewModel->loggedIn = $userAuth->hasIdentity();
    }

    public function attachPlugin(MvcEvent $e) {
        $config = $this->getConfig();
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        $pluginManager = $serviceManager->get('ControllerPluginManager');
        $pluginManager->get('MainPlugin')->preDispatch($e)
                ->defineACL($e, $config);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /* my functions */

    public function getServiceConfig() {
        return array(
            'invokables' => array(
                'HomeDataMapper' => 'Home\Models\HomeDataMapper'
            ),
            'factories' => array(
                'cache' => function() {
                    $cache = \Zend\Cache\StorageFactory::factory(array(
                                'adapter' => array(
                                    'name' => 'filesystem',
                                    'options' => array(
                                        'namespace' => 'serviceCache'
                                    ),
                                ),
                                'plugins' => array(
                                    // Don't throw exceptions on cache errors
                                    'exception_handler' => array(
                                        'throw_exceptions' => false
                                    ),
                                    // We store database rows on filesystem so we need to serialize them
                                    'Serializer'
                                )
                    ));

                    return $cache;
                },
                'Home\Model\Service\ServiceTable' => function($sm) {
                    $tableGateway = $sm->get('ServiceTableGateway');
                    $serviceTable = new ServiceTable($tableGateway);
                    return $serviceTable;
                },
                'ServiceTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Service());
                    return new TableGateway('service', $dbAdapter, null, $resultSetPrototype);
                },
                'Home\Model\Role\RoleTable' => function($sm) {
                    $tableGateway = $sm->get('RoleTableGateway');
                    $table = new RoleTable($tableGateway);
                    return $table;
                },
                'RoleTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Role());
                    return new TableGateway('roles', $dbAdapter, null, $resultSetPrototype);
                },
                'Home\Model\Session\SessionTable' => function($sm) {
                    $tableGateway = $sm->get('SessionTableGateway');
                    $table = new SessionTable($tableGateway, $sm);
                    return $table;
                },
                'SessionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Session());
                    return new TableGateway('session', $dbAdapter, null, $resultSetPrototype);
                },
                'Home\Model\User\UserTable' => function($sm) {
                    $tableGateway = $sm->get('Home\UserTableGateway');
                    $table = new UserTable($tableGateway, $sm);
                    return $table;
                },
                'Home\UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                }
            )
        );
    }

    public function activateCache() {
        $cache = \Zend\Cache\StorageFactory::factory(array(
                    'adapter' => array(
                        'name' => 'filesystem',
                        'options' => array(
                            'namespace' => 'serviceCache'
                        ),
                    ),
                    'plugins' => array(
                        // Don't throw exceptions on cache errors
                        'exception_handler' => array(
                            'throw_exceptions' => false
                        ),
                        // We store database rows on filesystem so we need to serialize them
                        'Serializer'
                    )
        ));

        return $cache;
    }

}
