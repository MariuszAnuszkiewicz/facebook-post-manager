<?php

namespace Facebook\Facebook\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;


class AccessTable
{
   protected $tableGateway;

   public function __construct(TableGateway $tableGateway)
   {
       $this->tableGateway = $tableGateway;
   }

   public function fetchAll()
   {
       $results = $this->tableGateway->select();
       return $results;
   }

   public function getAccesss($id)
   {
       $id = (int) $id;
       $rows = $this->tableGateway->select(array('id' => $id));
       $row = $rows->current();
       if (!$row) {
           throw new \Exception('Brak elementu z takim id');
       }
       return $row;
   }

   public function saveAccess(FacebookAccess $fbAccess)
   {
       $data = array(
           'name' => $fbAccess->name,
           'access_value' => $fbAccess->access_value
       );
       $id = (int) $id;
       if ($id == 0) {
          $this->tableGateway->insert($data);
       } else {
           if ($this->getAccesss($id)) {
               $this->tableGateway->update($data, array('id' => $id));
           } else {
               throw new \Exception('Brak id');
           }
       }
   }

   public function deleteAccess($id)
   {
       $this->tableGateway->delete(array('id' => $id));
   }
}