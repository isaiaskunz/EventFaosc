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
    if (isset($_GET['id']) == TRUE && isset($_SESSION['admin'])){
    $sql = "SELECT * FROM atividade as a
            INNER JOIN tipo_atividade as t 
            ON a.id_tipo_atividade=t.id_tipo_atividade 
            WHERE a.id_evento=".$_GET['id'];
            $result = $conn->query($sql);
    }
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
            <header class="container" style="margin-top: 1%; font-size: 15px; font-family: sans-serif;">
            <div class="container row" >
                <div class="event-logo col-2">
                    <a href="../index.php">
                        <img src="./img/logofaosc.png" class="img-responsive img-fluid" alt="logo Faosc">
                    </a>
                </div>
                <div class="event col-10">
                    <h3> Event Faosc </h3>
                </div>
            </div>
            <div class="nav row">
                <nav class="col-12 navbar-light bg-light sticky-top" style="margin-top: 2%;">
                        <ul>
                            <li class="home">
                                <a  href="index.php"><span class="glyphicon glyphicon-home"></span> Home </a>
                            </li>
                            <?php 
                                if (isset($_SESSION['id']) && isset($_SESSION['user_name'])){ ?>
                                    <li>
                                        <a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Sair</a>
                                    </li>
                                    <li>
                                        <a href="#"><span class="glyphicon glyphicon-user"></span> Perfil</a>
                                    </li>
                                    <li>
                                        <a  href="painel.php"><span class="glyphicon glyphicon-cog"></span> Painel</a>
                                    </li>
                                    <li>
                                        <texto>
                                            <?php echo "Olá ".$_SESSION['name']." ".$_SESSION['sobrenome']; }?>
                                            
                                        </texto>
                                    </li>
                        </ul>
                </nav>
            </div>
        </header>

    <body>
        <div class="container row-12" style="margin-top:2%">
            <div class="col-md-6">
                 <h4>PAINEL ADMINISTRATIVO / Cadastro de Atividades</h4>
            </div>
            <div class="col-md-6">
                <p style="text-align:right;">
                   <a href="adm/cadastrar_atividade.php?id_evento=<?php echo $_GET['id']?>"> <span class="glyphicon glyphicon-plus-sign"> </span> Cadastrar nova Atividade</a>
                </p>
            </div>

            <div class="row-12" style="margin-top:5%">

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Tipo de atividade</th>
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
                                    <td><?php echo $row['id_atividade']; ?></td>
                                    <td><?php echo $row['nome_atividade']; ?></td>
                                    <td><?php echo $row['descricao_tipo_atividade']; ?></td>
                                    <td><?php echo $row['data_hora_inicio']; ?></td>
                                    <td><?php echo $row['data_hora_fim']; ?></td>
                                    <td>
                                        <a class="btn btn-info" href="atividade.php?id=<?php echo $row['id_atividade']; ?>">Visualisar</a>&nbsp;
                                        <a class="btn btn-danger" href="adm/cadastrar_atividade.php?id=<?php echo $row['id_atividade']; ?>">Editar</a>&nbsp;
                                        <a class="btn btn-info" href="adm/palestrantes.php?id=<?php echo $row['id_atividade']; ?>&id_evento=<?php echo $_GET['id']; ?>">Palestrantes</a>&nbsp;
                                    </td>
                                </tr>                       

                        <?php 
                            }
                        }
                        ?>                
                    </tbody>
                </table>
            </div>
            <a class="btn btn-secondary btn-block mb-4" href="adm/events.php">Retornar</a> 
      </div>
      
    </body>
</html>