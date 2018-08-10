<?php
// namespace auth;
include "database.php";

class auth{

  public function login(){
    $query = "SELECT * FROM users";
    $db = new db($query);
    return $db->get();
    // print_r($db->get());

  }



}
