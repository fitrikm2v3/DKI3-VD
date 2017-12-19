<?php
    define("HOSTNAME","localhost");
    define("USERNAME","root");
    define("PASSWORD","");
    define("DATABASE","db_datakota");

    $db = "db_datakota";
    $host = "localhost";
    $user = "root";
    $pw = "";
    $msg=" ";

    $connect=mysqli_connect($host,$user,$pw,$db);
    if (mysqli_connect_errno())
      {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
      }
      else{  }
  ?>