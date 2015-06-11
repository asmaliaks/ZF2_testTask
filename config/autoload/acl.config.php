<?php

return array(
    'acl' =>array(
        'roles' => array(
            'guest' => null,
            'user'  => 'guest',
            'admin' => 'user',
        ),
        'resources' => array(
              'allow' => array(

                  'Blog\Controller\Post' => array(
                      'list' => 'guest',
                      'view-post' => 'user',
                      'add-post'  => 'admin',
                      'edit-post' => 'admin',
                      'remove-post' => 'admin'
                  ),
                  'Blog\Controller\Registration' => array(
                      'index' => 'guest',
                      'user-list' => 'admin'
                  ),
                  'Auth\Controller\Index' => array(
                      'login' => 'guest',
                      'logout' => 'user'
                  ),
              )  
        ),
    ),
);