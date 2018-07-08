<?php

namespace Facebook;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Facebook\Facebook\Model\AccessTable;
use Facebook\Facebook\Model\FacebookAccess;

 return array(
     'invokables' => array(
         'send_post' => 'Facebook\Model\SendPost',
         'delete_post' => 'Facebook\Model\DeletePost',
         'edit_post' => 'Facebook\Model\EditPost'
     ),
     'factories' => array(
         'Facebook\Facebook\Model\AccessTable' => function($sm) {
             $tableGateway = $sm->get('AccessTableGateway');
             $table = new AccessTable($tableGateway);
             return $table;
         },
         'AccessTableGateway' => function($sm) {
             $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
             $resultSetPrototype = new ResultSet();
             $resultSetPrototype->setArrayObjectPrototype(new FacebookAccess());
             return new TableGateway('access_keys', $dbAdapter, null, $resultSetPrototype);
         },
     ),
 );