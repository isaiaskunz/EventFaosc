<?php
    session_start();
    if ($_SESSION['admin'] != TRUE){
        echo "você não tem permissão para acessar este recurso";
        exit();
    }
    $base_link = "https://eventos.faosc.edu.br/";
    if ($_SERVER['HTTP_HOST'] == "localhost"){
        $base_link = "http://localhost/eventfaosc/";
    }
?>

<?php 
    include $_SESSION['path']."db.php";
    $sql = "SELECT * FROM evento as e
            INNER JOIN tipo_evento as t 
            ON e.id_tipo_evento=t.id_tipo_evento ";
            $result = $conn->query($sql);
    include $_SESSION['path']."header.php";   
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
        <base href="<?php echo $base_link; ?>">
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
    <?php
            
        ?>

    <body>
        <div class="container row-12" style="margin-top:2%">
            <div class="col-md-6">
                 <h4>PAINEL ADMINISTRATIVO / Cadastro de Eventos</h4>
            </div>
            <div class="col-md-6">
                <p style="text-align:right;">
                   <a href="adm/cadastrar_evento.php"> <span class="glyphicon glyphicon-plus-sign"> </span> Cadastrar novo Evento</a>
                </p>
            </div>

            <div class="row-12" style="margin-top:5%">

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Tipo de evento</th>
                            <th>Data de Inicio</th>
                            <th>Data de Fim</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                            if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td><?php echo $row['id_evento']; ?></td>
                                    <td><?php echo $row['nome_evento']; ?></td>
                                    <td><?php echo $row['descricao']; ?></td>
                                    <td><?php echo $row['data_inicio']; ?></td>
                                    <td><?php echo $row['data_fim']; ?></td>
                                    <td>
                                        <a class="btn btn-info" href="evento.php?id=<?php echo $row['id_evento']; ?>">Visualisar</a>&nbsp;
                                        <a class="btn btn-danger" href="adm/cadastrar_evento.php?id=<?php echo $row['id_evento']; ?>">Editar</a>&nbsp;
                                        <a class="btn btn-info" href="adm/atividades.php?id=<?php echo $row['id_evento']; ?>">Atividades</a>&nbsp;
                                        <a class="btn btn-info" href="gerar_todos_certificados.php?id=<?php echo $row['id_evento']; ?>">Gerar Certificados</a>&nbsp;
                                    </td>
                                </tr>                       

                        <?php 
                            }
                        }
                        ?>                
                    </tbody>
                </table>
            </div>
            <a class="btn btn-secondary btn-block mb-4" href="painel.php">Retornar</a> 
      </div>
    </body>
</html>