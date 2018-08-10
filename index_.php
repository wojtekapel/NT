<?php
// namespace main_space;
include "class/auth.php";

// use auth\auth;
$user = new auth();

$result = $user->login();

while($row = $result->fetch_assoc()){

  echo '<br/>'.$row['email'];
}
