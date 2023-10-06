<?php
    $_GET['install']=TRUE;
    require 'db.php';
    $sql = "SHOW DATABASES LIKE 'eventosfaoscedubr'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    }
    else {    
        $query = file_get_contents('eventfaosc.sql');
        mysqli_multi_query($conn, $query);
        $_GET['install'] = FALSE;
    }
    $conn = mysqli_connect($server_addr, $user_name, $password, $db_name);
?>