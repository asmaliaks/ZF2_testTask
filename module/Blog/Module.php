<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog;

use Blog\Models\Users\Users;
use Blog\Models\Users\UsersTable;
use Blog\Models\Posts\Posts;
use Blog\Models\Posts\PostsTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
         public function getServiceConfig(){
         return array(
             'factories' => array(
                 'Blog\Models\Users\UsersTable' =>  function($sm) {
                     $tableGateway = $sm->get('BlogTableGateway');
                     $table = new UsersTable($tableGateway);
                     return $table;
                 },
                 'BlogTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Users());
                     return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                 },
                 'Blog\Models\Posts\PostsTable' => function($sm) {
                     $tableGateway = $sm->get('PostTableGateway');
                     $table = new PostsTable($tableGateway);
                     return $table;
                 },
                  'PostTableGateway' => function($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Posts());
                     return new TableGateway('posts', $dbAdapter, null, $resultSetPrototype);
                  }      
             ),
         );
     }
}
