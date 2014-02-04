<?php /**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/blog',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'index' => array(
                'type'    => 'Segment',
                'options' => array(
                  'route'    => '/index/index',
                  'defaults' => array(
                      'controller' => 'Blog\Controller\Index',
                      'action'     => 'index'
                  ),  
                ),
            ),
            'blogPost' => array(
                'type'     => 'Segment',
                 'options' => array(
                     'route'    => '/post',
                     'defaults' => array(
                         'controller' => 'Blog\Controller\Post',
                         'action'     => 'list',
                         
                     )
                 ),
                'may_terminate' => true,
                'child_routes' => array(
                    'add-post' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route'      => '/add-post',
                            'defaults' => array(
                                'action' => 'add-post'
                            )
                        )
                    ),
                    'edit-post' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit-post[/:postId]',
                            'defaults' => array(
                                'controller' => 'Blog\Controller\Post',
                                'action'     => 'edit-post'
                            )
                        )
                    ),
                    'view-post' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/view-post[/:postId]',
                            'defaults' => array(
                                'controller' => 'Blog\Controller\Post',
                                'action'     => 'view-post',
                            )
                        )
                    ),
                    'remove-post' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/remove-post[/:postId]',
                            'defaults' => array(
                                'controller' => 'Blog\Controller\Post',
                                'action'     => 'remove-post',
                                'postId'     => 1
                            )
                        )
                    )
                )
            ),
//            'auth' => array(
//                'type'    => 'Segment',
//                'options' => array(
//                    'route'    => '/auth/index',
//                    'defaults' => array(
//                        'controller' => 'Blog\Controller\Auth',
//                        'action'     => 'index'
//                    )
//                )
//            ),
            'registration' => array(
                'type'     => 'Segment',
                'options'  => array(
                    'route'    => '/registration',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Registration',
                        'action'     => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                        'register-new-user' => array(
                        'type'              => 'literal',
                        'options'           => array(
                                    'route'      => '/register-new-user',
                            'defaults' => array(
                                'action' => 'register-new-user'
                            )
                         )
                    ),
                    'user-list' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'       => '/user-list',
                            'defaults' => array(
                                'action' => 'user-list'
                            )
                        )
                    )
                )
            )
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\Index' => 'Blog\Controller\IndexController',
            'Blog\Controller\Post'  => 'Blog\Controller\PostController',
            'Blog\Controller\Registration' => 'Blog\Controller\RegistrationController',
            //'Blog\Controller\Auth'  => 'Blog\Controller\AuthController'
            
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'blog/post/view'      => __DIR__ . '/../view/blog/post/view.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'post/widget'             => __DIR__ . '/../view/blog/post/widget.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
