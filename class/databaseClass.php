<?php
// namespace sqlBase;

class data{
  public $rowCnt;

 // function __construct($host, $user, $pass, $base){
 //
 //       $this->dbHost = $host;
 //       $this->dbUser = $user;
 //       $this->dbPass = $pass;
 //       $this->dbBase = $base;
 //
 //   }

   public function set($query){//.......................zabezpiecz....................................

        // $polaczenie = new mysqli('localhost', 'root', 'titop630', 'log');
        $polaczenie = new mysqli('mysql.cba.pl', 'radiogielda', 'Stefan1234', '4ham');
        mysqli_query($polaczenie, "SET CHARSET utf8");
        mysqli_query($polaczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
        $query = str_replace('drop','', $query);
        $polaczenie->query($query);
        $polaczenie->close();
        return;

   }
   public function get($query){

        // $polaczenie = new mysqli('localhost', 'root', 'titop630', 'log');
        $polaczenie = new mysqli('mysql.cba.pl', 'radiogielda', 'Stefan1234', '4ham');
        mysqli_query($polaczenie, "SET CHARSET utf8");
        mysqli_query($polaczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
        $query = str_replace('drop','', $query);
        $result = $polaczenie->query($query);
        $polaczenie->close();

        return $result;


   }

   public function create($user, $email, $password, $json, $json_table){
     $query = "INSERT INTO users VALUES";
     $_query = "(NULL, '$user', '$password', '$email', '$json', '$json_table' )";
     $query_ = $query.$_query;
     $this->set($query_);
     // echo $query_;
     return;
   }

   public function insert_log($user, $call, $json, $import){
     date_default_timezone_set("UTC");
     $temp = json_decode($json);

     if($import){
       $czas = $temp->time_on;
       $data = $temp->qso_date;
     }
     else{
       $czas = date('H:i:s/Z');
       $data = date('Y-m-d ');
     }

     $temp->qsl_rcvd = strtoupper($temp->qsl_rcvd);
     $temp->qsl_sent = strtoupper($temp->qsl_sent);
     $temp->mode = strtoupper($temp->mode);
     $temp->band = strtolower($temp->band);
     if(!is_numeric($temp->freq)) $temp->freq = '';
     if(!is_numeric($temp->rst_rcvd)) $temp->rst_rcvd = '';
     if(!is_numeric($temp->rst_sent)) $temp->rst_sent = '';

     $temp->time_on = $czas;
     $temp->qso_date = $data;
     $json = json_encode($temp);


     $query = "INSERT INTO ".$user."_LOG VALUES";
     $_query = "(NULL, '$call', '$data', '$czas', '$json', '$json' )";
     $query_ = $query.$_query;
     $this->set($query_);
     // echo $query_;
     return;
   }

   // public function text_(){
     // $text = "działa test z databaseClass.php";
     // return $text;
   // }


   public function drawTab($user){

      $query = "SELECT * FROM users WHERE call_ = '$user'";
      // $result = $baza->get($query)->fetch_assoc();
      $row = $this->get($query)->fetch_assoc();
      $data = $row['table_'];
      $data = json_decode($data);
      // print_r($data);
      // die;
      return $data;
  }

  public function contentTab($user, $find, $page){

     if($find) $finder = "WHERE call_ LIKE '%".$find."%' ";
     else $finder = '';

     if($page == 10000) $query = "SELECT * FROM ".$user."_LOG ".$finder;
     else $query = "SELECT * FROM ".$user."_LOG ".$finder." ORDER BY id DESC LIMIT 10 OFFSET ".$page." ";
     // $result = $baza->get($query)->fetch_assoc();
     $row = $this->get($query);

     // print_r($data);
     // die; W4R1AT
     return $row;
 }

  public function valid_call($call, $email){

       if($email) $key = 'email';
       else $key = 'call_';

     $query = "SELECT * FROM users WHERE $key = '".$call."'";
     $text = 'ok';

     $row = $this->get($query);
     if($row->num_rows) $text = 'busy';

     return $text;
 }

 public function update_def($user, $json){

    $query = "UPDATE users SET table_ = '".$json."' WHERE call_ = '".$user."'";
    $this->set($query);
}

public function getAvailableCels(){
    $query = "SELECT json FROM setings WHERE name = 'cols'";
    return $this->get($query);


}

public function getUserSetings($user){

    $query = "SELECT setings FROM users WHERE call_ = '".$user."'";
    $result = $this->get($query)->fetch_assoc();
    return json_decode($result['setings']);


}

public function updateUserSetings($user, $obj){

    $query = "UPDATE users SET setings = '".json_encode($obj)."' WHERE call_ ='".$user."'";
    $this->set($query);
    return 'ok';

}

}
