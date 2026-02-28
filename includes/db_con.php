<?php

    $Host = "localhost";
    $user = "root";
    $password = "";
    $db = "my_db";
    $conn = mysqli_connect($Host,$user,$password,$db);

    if(!$conn){
        die("Connection Failed".Mysqli_connect_error());
        echo("Connection Failed");
    }
?>