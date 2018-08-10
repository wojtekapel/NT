<?php

// print_r($_GET);

include "../class/check_target.php";
$data = new target($_GET['lat'], $_GET['lon']);
$data->check();
