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
    $sql = "SELECT e.nome_evento, c.nome_completo, c.cpf, c.carga_horaria_certificado 
                FROM certificado as c
                INNER JOIN evento e using (id_evento)
                WHERE  c.ativo = 1
                ORDER BY 1,2 ASC;";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Certificados</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

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
        
        <?php
            include $_SESSION['path']."header.php";
        ?>
    
    </head>
    <body>
        



        <div class="container row-12" style="margin-top:2%">
            <div class="col-md-6">
                 <h4>PAINEL ADMINISTRATIVO / Lista de Certificados</h4>
            </div>

            <div class="row-12" style="margin-top:5%">
                 <table class="table">
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>CH</th>
                        <th>Emitido</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $row['nome_evento']; ?></td>
                                <td><?php echo $row['nome_completo']; ?></td>
                                <td><?php echo $row['cpf']; ?></td>
                                <td><?php echo $row['carga_horaria_certificado']; ?>h</td>
                                <td>Sim</td>
                            </tr>   
                    <?php 
                        }
                    }
                             
                
                    $sql2 = "SELECT 
                                e.nome_evento,
                                (select concat(nome, ' ', sobrenome) from usuario where id_usuario = p.id_usuario) as nome_completo, 
                                (select numero_documento from usuario where id_usuario = p.id_usuario) as cpf,
                                (SELECT SUM(a.carga_horaria) as ch_total FROM presenca as p2
                                                        INNER JOIN atividade as a ON DATE_FORMAT(p.data_horario_entrada, '%Y-%m-%d') = DATE_FORMAT(a.data_hora_inicio, '%Y-%m-%d')
                                                        WHERE p2.id_usuario = p.id_usuario) as carga_horaria_certificado
                            FROM presenca as p
                            INNER JOIN atividade a using (id_atividade)
                            INNER JOIN evento e using (id_evento)
                            WHERE e.id_evento not in (select id_evento from certificado where id_usuario = p.id_usuario and ativo = 1) and p.id_usuario not in (select id_usuario from certificado where id_evento = e.id_evento and ativo = 1)
                            GROUP BY 1,2,3,4  
                            ORDER BY 1,2 ASC;";
                    $result2 = $conn->query($sql2);

                    if ($result2->num_rows > 0) {
                        while ($row = $result2->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $row['nome_evento']; ?></td>
                                <td><?php echo $row['nome_completo']; ?></td>
                                <td><?php echo $row['cpf']; ?></td>
                                <td><?php echo $row['carga_horaria_certificado']; ?>h</td>
                                <td>Não</td>
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