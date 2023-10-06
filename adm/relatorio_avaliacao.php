<?php
    
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if ($_SESSION['admin'] != TRUE){
        echo "você não tem permissão para acessar este recurso";
        exit();
    }
?>

<?php 

    include $_SESSION['path']."db.php";
    $sql = "SELECT * FROM usuario as u 
            INNER JOIN tipo_perfil_acesso as t 
            ON u.id_tipo_perfil_acesso=t.id_tipo_perfil_acesso ";
            $result = $conn->query($sql);


?>

<!DOCTYPE html>
<html>
    <head>
        <title>Usuarios</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

                
        <?php
            include $_SESSION['path']."header.php";
        ?>
    
    </head>
    <body>


        
        <div class="container row-12" style="margin-top:2%">
            <div class="col-md-12" style="text-align:center;">
                 <h4>RESULTADO NPS DA 3ª SEMANA ACADÊMICA INTEGRADA FAOSC 2023</h4>
                 <h5>RESULTADOS OBTIDOS: </h5>
                 <?php 
                        include "../db.php";
                        $sql = "SELECT count(id_avaliacao) as total FROM avaliacao;";
                        $result = $conn->query($sql);
                        if (mysqli_num_rows($result) === 1) {
                            $row = mysqli_fetch_assoc($result);
                            echo "Total avaliações: " . $row['total'];
                        }
                     ?>

            </div>
            
            <div class="row-12" style="margin-top:5%">

                <div class="container">
                     <label class="form-label">Baseado em SUAS EXPERIÊNCIAS recentes e utilizando uma ESCALA DE O A 10, qual a CHANCE de você RECOMENDAR a Semana Acadêmica Integrada FAOSC para um amigo ou familiar?</label>

                     

                     <!-- mostrar aqui um grafico com a quantidade de respostas de 1 a 10 conforme arquivo word -->
                </div>

                <div class="container" style="margin-top: 2px;">
                     <label class="form-label">A RAZÃO ATRIBUÍDA A NOTA:</label>

                     <?php 
                       
                     ?>

                     <!-- mostrar aqui um grafico de pizza com a porcetagem conforme arquivo word -->
                </div>
                            
            </div>    
            <a class="btn btn-secondary btn-block mb-4" href="painel.php">Retornar</a>        
      </div>
    </body>
</html>