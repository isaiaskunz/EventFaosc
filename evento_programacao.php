<?php
  include "./header.php";   
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

    </head>

    
        <!-- Cabeçalho  -->
        <?php
            include('./header_evento.php');

            include "db.php";
            $sql_evento = "SELECT * FROM evento WHERE id_evento=".$_GET['id'];
            $result = $conn->query($sql_evento);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            }
        ?>

         <!-- Informações -->
         <div class="container row " style="font-size: 15px; margin-top: 2%">
            <div class="col-12">
                <div class="cabeçalho" style="font-size: 20px; padding-top: 2%; text-align: center;">
                    <h4><?php echo $row['nome_evento'];?></h4>
                </div>
                <div class="conteudo" style="font-size: 13px; padding: 2%; text-align: justify;">                 
                <?php 
                    
                    $sql = "SELECT * FROM atividade as a
                            INNER JOIN evento as e ON e.id_evento = a.id_evento
                            WHERE a.id_evento=".$_GET['id'];
                            $result = $conn->query($sql);
            
                            if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                            <div class="row">                                
                                <div style="text-align: center; margin-top:5%">
                                    <h5> <?php echo (new DateTime($row['data_hora_inicio']))->format('d/m/Y H:i'); ?></h5>
                                    <h6><b>Tema: </b><?php echo $row['nome_atividade'];?> </h6>
                                    <?php
                                        $sql2 = "SELECT * FROM atividade_palestrante as a
                                        INNER JOIN usuario as u ON a.id_usuario = u.id_usuario
                                        WHERE a.id_atividade=".$row['id_atividade'];
                                        $result_palestrante = $conn->query($sql2);
                                        if ($result_palestrante->num_rows > 1) { ?>
                                            <h6><b>Palestrantes : 
                                    <?php
                                            while ($row_palestrante = $result_palestrante->fetch_assoc()) { ?>
                                                 </b> - <?php echo $row_palestrante['nome']." ".$row_palestrante['sobrenome'];?>
                                    <?php  
                                            } ?> 
                                        </h6>
                                                
                                    <?php
                                        } else { if ($result_palestrante->num_rows == 1) { ?>
                                            <h6><b>Palestrante :</b> - <?php while ($row_palestrante = $result_palestrante->fetch_assoc()) {
                                                echo $row_palestrante['nome']." ".$row_palestrante['sobrenome'];
                                            }}?> 
                                          
                                    <?php 
                                        } ?>
                                    </h6>   
                                </div>
                            </div>
                            
                <?php 
                            }
                        }?>       
                        
                </div>
            </div>
        </div>
                        <br>
                        <br>
                        <br>
        <div class="increvase" style="margin-bottom: 5%; float: certer; text-align: center">
                <a class="btn btn-primary btn-lg" href="participante/cadastrar_no_evento.php">Increva-se</a>
        </div>

                
        <!-- rodapé -->
        <footer class="container">
            <?php
               require('template/footer.html');

            ?>
        </footer>

    </body>

</html>



