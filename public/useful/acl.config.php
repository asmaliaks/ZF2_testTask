<?php

return array(
    'acl' => array(
        'roles' => array(
            'guest' => null,
            'user' => 'guest',
            'admin' => 'user',
            'superadmin' => 'admin'
        ),
        'resources' => array(
            /* permission is here */ 
            'allow' => array(
                /* controller */
                'Home\Controller\Ajax' => array(
                    /* action => role */
                    'editinfo' => 'user',
                    'editaddress' => 'user',
                    'contact' => 'guest',
                    'contact-via-form' => 'guest',
                    'changestorename' => 'user',
                    'subscr' => 'user'
                ),
                /* controller */
                'Home\Controller\Index' => array(
                    /* action => role */
                    'index' => 'guest',
                    'services' => 'guest',
                    'aboutus' => 'guest',
                    'contact' => 'guest',
                    'video' => 'guest',
                    'prices' => 'guest',
                    'blog' => 'guest',
                    'land' => 'guest'
                ),
                'Home\Controller\Office' => array(
                    /* action => role */
                    'index' => 'user',
                    'create-service' => 'user',
                    'list-services' => 'user',
                    'account' => 'user'
                ),
                /* controller */
                'Auth\Controller\Index' => array(
                    /* action => role */
                    'index' => 'guest',
                    'login' => 'guest',
                    'remind' => 'guest',
                    'remindajax' => 'guest',
                    'passrecovery' => 'guest',
                    'rmmarketing' => 'guest',
                    'logout' => 'guest'
                ),
                /* controller */
                'Control\Controller\Index' => array(
                    /* action => role */
                    'index' => 'superadmin',
                    'list-all-stores' => 'superadmin',
                    'list-all-users' => 'superadmin',
                    'list-all-admin' => 'superadmin',
                    'email-admin' => 'superadmin',
                    'email-admin-test' => 'superadmin'
                ),
                /* controller */
                'Control\Controller\Cron' => array(
                    /* action => role */
                    'index' => 'guest'
                ),
                /* controller */
                'Event\Controller\Index' => array(
                    /* action => role */
                    'index' => 'guest',
                    'main' => 'guest',
                    'home' => 'guest',
                    'checkout' => 'guest',
                    'policy' => 'guest'
                ),
                /* controller */
                'Event\Controller\Cpsettings' => array(
                    /* action => role */
                    'index' => 'admin',
                    'aboutus' => 'admin',
                    'ask' => 'admin',
                    'contact' => 'admin',
                    'home' => 'admin',
                    'design' => 'admin',
                    'google' => 'admin',
                    'land' => 'guest',
                    'units' => 'admin',
                    'unitstree' => 'admin',
                    'advancedsearch' => 'admin',
                    'paymentdesk' => 'admin',
                    'transactionlist' => 'admin',
                    'theme-list' => 'admin',
                    'terms' => 'admin'
                ),
                /* controller */
                'Event\Controller\Cpusers' => array(
                    /* action => role */
                    'index' => 'admin'
                ),
                /* controller */
                'Event\Controller\Cpajax' => array(
                    /* action => role */
                    'index' => 'guest',
                    'mkunit' => 'admin',
                    'mkunittree' => 'admin',
                    'rmunit' => 'admin',
                    'mkuser' => 'admin',
                    'rmuser' => 'admin',
                    'mkitem' => 'admin',
                    'updinfo' => 'admin',
                    'get-info' => 'admin',
                    'rmtheme' => 'admin',
                    'mktheme' => 'admin',
                    'edititem' => 'admin',
                    'setpaypalemail' => 'admin',
                    'setyandex' => 'admin',
                    'setliqpay' => 'admin',
                    'setwm' => 'admin',
                    'changepaymenttype' => 'admin',
                    'itemimage' => 'admin',
                    'assets' => 'admin',
                    'deleteasset' => 'admin',
                    'setanalytics' => 'admin',
                    'setgmail' => 'admin',
                    'settimezone' => 'admin',
                    'setlang' => 'admin',
                    'settype' => 'admin',
                    'add-langs' => 'admin',
                    'setcurrency' => 'admin',
                    'changepaidstatus' => 'admin',
                    'changedspstatus' => 'admin',
                    'sendemail' => 'admin',
                    'changerole' => 'admin',
                    'settax' => 'admin',
                    'savesg' => 'admin',
                    'changetheme' => 'admin',
                    'ask-support' => 'admin',
                    'setstoreaddr' => 'admin'
                ),
                /* controller */
                'Event\Controller\Auth' => array(
                    /* action => role */
                    'index' => 'guest',
                    'login' => 'guest',
                    'register' => 'guest',
                    'logout' => 'guest',
                    'ajaxlogin' => 'guest',
                    'ajaxregister' => 'guest',
                    'ajaxlogout' => 'guest',
                    'remindajax' => 'guest',
                    'passrecovery' => 'guest'
                ),
                /* controller */
                'Event\Controller\Cpitems' => array(
                    /* action => role */
                    'index' => 'admin',
                    'services' => 'admin',
                    'mkitemservice' => 'admin',
                    'rmitem' => 'admin',
                    'rmitemservice' => 'admin',
                    'finditems' => 'admin',
                    'mkinvoice' => 'admin',
                    'list-orders' => 'admin',
                    'checkservicedate' => 'admin',
                    'delete-order' => 'admin',
                    'get-order-info' => 'admin',
                    'get-item-info' => 'admin',
                    'savethreads' => 'admin'
                ),
                /* controller */
                'Event\Controller\Ajax' => array(
                    /* action => role */
                    'unititems' => 'guest',
                    'updatecart' => 'guest',
                    'createinvoice' => 'guest',
                    'userinfo' => 'guest',
                    'updaddress' => 'guest',
                    'rmaddress' => 'guest',
                    'checkdate' => 'guest',
                    'pdfinvoice' => 'guest',
                    'searchitems' => 'guest',
                    'getitems' => 'guest',
                    'editinfo' => 'guest',
                    'mkinvoice' => 'guest',
                    'uiemail' => 'guest',
                    'checkservicedate' => 'guest',
                    'blind' => 'guest',
                    'uicustomform' => 'guest'
                ),
                /* controller */
                'Event\Controller\Payment' => array(
                    /* action => role */
                    'paypalipn' => 'guest',
                    'wmipn' => 'guest',
                    'yandexipn' => 'guest',
                    'liqpayipn' => 'guest'
                )
            )
        )
    )
);
