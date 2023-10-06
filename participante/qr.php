<?php

    include('phpqrcode.php'); 
    $param = $_GET['id'];
    ob_start("callback");
    $codeText = $param;
    $debugLog = ob_get_contents();
    ob_end_clean();
//    QRcode::png($codeText);
    QRcode::png($codeText,false,'L',13,1);

?>