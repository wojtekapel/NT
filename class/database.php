<?php


class db {




  public function get($query){

    $connect = @new mysqli('mysql.cba.pl', 'x', 'x', 'x');
    // $connect = @new mysqli('localhost', 'root', '', 'navi');



      if($connect->connect_errno > 0 ){
        echo 'błąd połączenia z bazą danych';
      }
      else{

        $result = $connect->query($query);

        $connect->close();
        return $result;
       }

  }





  }
