<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Auth\Models\User\User;
use Auth\Models\User\UserTable;
use Auth\Models\Session\Session;
use Auth\Models\Session\SessionTable;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
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
                'AuthDataMapper' => 'Auth\Models\AuthDataMapper',
                'registerForm' => 'Auth\Forms\Register',
                'loginForm' => 'Auth\Forms\LoginForm'
            ),
            'factories' => array(
                'Auth\Model\User\UserTable' => function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                'Auth\Model\Session\SessionTable' => function($sm) {
                    $tableGateway = $sm->get('Auth\SessionTableGateway');
                    $table = new SessionTable($tableGateway, $sm);
                    return $table;
                },
                'Auth\SessionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Session());
                    return new TableGateway('session', $dbAdapter, null, $resultSetPrototype);
                },
            )
        );
    }

}
