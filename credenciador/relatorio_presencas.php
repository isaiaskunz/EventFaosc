<?php
    $path = dirname(__DIR__);
    session_start();
    if ($_SESSION['credenciador'] != TRUE){
        echo "Você não tem permissão para acessar este recurso";
        exit();
    }

?>
<?php
    include "../header.php";
?>

<?php 
    include "../db.php";
    $sql = "SELECT *, TIMEDIFF(p.data_horario_saida, p.data_horario_entrada) as diferenca_tempo 
            FROM presenca as p 
            INNER JOIN usuario as u using(id_usuario) 
            LEFT JOIN atividade as a using (id_atividade)
            LEFT JOIN evento as e using (id_evento)
            ORDER BY 4";
            $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Relatório de presenças</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <base href="http://localhost/eventfaosc/">

        <style>
            .event{
                text-align: center;
                vertical-align: middle;
            }

            ul {
              list-style-type: none;
              margin: 0;
              padding: 0;
              overflow: hidden;
              /*background-color: #333;*/
            }

            .home{
              float: left;
            }

            li{
                float: right;
            }

            li a {
              display: block;
              color: black;
              text-align: right;
              padding: 14px 16px;
              text-decoration: none;
            }

            li texto {
              display: block;
              color: black;
              text-align: right;
              padding: 14px 16px;
              text-decoration: none;
            }

            li a:hover {
              /*background-color: #111;*/
              background-color: #A9E2F3;
            }

            nav{
                margin-top: 2%;
                margin-bottom: 2%;
            }

            * {box-sizing: border-box}
            body {font-family: Verdana, sans-serif; margin:0}
            .mySlides {display: none}
            img {vertical-align: middle;}

            /* Slideshow container */
            .slideshow-container {
              max-width: 100%;
              position: relative;
              margin: auto;
            }


        </style>
    </head>
    <body>
        <?php
            include $path."..\header.php";
        ?>



        <div class="container row-12" style="margin-top:2%">
            <div class="col-md-6">
                 <h4>PAINEL CREDENCIADOR / Relatório de presenças</h4>
            </div>

            <div class="row-12" style="margin-top:5%">
                 <table class="table">
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Atividade</th>
                        <th>Participante</th>
                        <th>Data entrada</th>
                        <th>Data saída</th>
                        <th>Tempo permanência</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php
                        if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $row['nome_evento']; ?></td>
                                <td><?php echo $row['nome_atividade']; ?></td>
                                <td><?php echo $row['nome'] . " " . $row['sobrenome']; ?></td>
                                <td><?php echo $row['data_horario_entrada']; ?></td>
                                <td><?php echo $row['data_horario_saida']; ?></td>
                                <td><?php echo $row['diferenca_tempo']; ?></td>

                            </tr>                              

                    <?php 
                        }
                    }
                    ?>                
                </tbody>
            </table>
            </div>    
            <a class="btn btn-secondary btn-block mb-4" href="javascript:javascript:history.go(-1)">Retornar</a>        
      </div>
    </body>
</html>
