<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
$_SESSION['path'] = $_SERVER['DOCUMENT_ROOT']."/"; 
$base_link = "https://eventos.faosc.edu.br/";
if ($_SERVER['HTTP_HOST'] == "localhost"){
    $base_link = "http://localhost/eventfaosc/";
    $_SESSION['path'] .= "eventfaosc/";
}

  include $_SESSION['path']."header.php";   
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Informações - 3ª Semana Acadêmica</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="utf-8">
        <!-- ateração Kennya -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <base href="<?php echo $base_link; ?>">
    </head>

    <body>
        <!-- Cabeçalho  -->
        <?php
            include $_SESSION['path'].'header_evento.php';
        ?>

        <!-- Informações -->
        <div class="container" style="font-size: 15px; margin-top: 2%">
            <div class="col-12">
                <div class="cabeçalho" style="font-size: 20px; padding-top: 2%; text-align: center;">
                    <h4>3ª Semana Acadêmica Integrada FAOSC 2023/1º</h4>
                </div>
                <div class="conteudo" style="font-size: 13px; padding: 2%; text-align: justify;">
                    <?php echo $row['descricao_longa']; ?>

                </div>
                
            </div>
            
        </div>

        <div class="increvase" style="margin-bottom: 5%; float: certer; text-align: center">
            <a class="btn btn-primary btn-lg" href="participante/cadastrar_no_evento.php">Increva-se</a>
        </div>


                
        <!-- rodapé -->
        <?php
           require('template/footer.html');

        ?>
    </body>

</html>



