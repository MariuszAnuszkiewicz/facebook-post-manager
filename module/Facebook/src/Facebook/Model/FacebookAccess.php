<?php

namespace Facebook\Facebook\Model;


class FacebookAccess
{
    public $id;
    public $name;
    public $access_value;

    public function exchangeArray($data)
    {
         $this->id = (!empty($data['id'])) ? $data['id'] : null;
         $this->name = (!empty($data['name'])) ? $data['name'] : null;
         $this->access_value = (!empty($data['access_value'])) ? $data['access_value'] : null;
    }
}