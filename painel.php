
<?php 
$base_link = "https://eventos.faosc.edu.br/";
if ($_SERVER['HTTP_HOST'] == "localhost"){
    $base_link = "http://localhost/eventfaosc/";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>PAINEL</title>
        <link rel="stylesheet" type="text/css" href="style.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <base href="<?php echo $base_link; ?>">
    </head>
    <body>
        <?php

            if(!isset($_SESSION)) 
            { 
                session_start(); 
            } 

            include $_SESSION['path']."header.php";
        ?>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php
                    if ($_SESSION['admin'] == TRUE){
                        include $_SESSION['path']."adm/painel_adm.php";                        
                    }
                    if ($_SESSION['credenciador'] == TRUE){
                        include $_SESSION['path']."credenciador/painel_credenciador.php";
                    } 
                    if ($_SESSION['palestrante'] == TRUE){
                        include $_SESSION['path']."palestrante/painel_palestrante.php";
                    } 
                    include $_SESSION['path']."participante/painel_participante.php";
                ?>

                </div>            
            </div>         
        </div>
        
    </body>
</html>
