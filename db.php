<?php
    $server_addr= "localhost";
    $user_name= "root";
    $password = "";
    $db_name = "eventosfaoscedubr";
    if (isset($_GET['install']) and $_GET['install'] ==TRUE) {
        $conn = mysqli_connect($server_addr, $user_name, $password);
    } 
    else {
        $conn = mysqli_connect($server_addr, $user_name, $password, $db_name);
    }
    if (!$conn) {
        echo "DB Connection failed!";
        }
?>