<?php
    include "./header.php";    
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Programação - 3ª Semana Acadêmica</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="utf-8">
        <!-- ateração Kennya -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        <style>

        </style>

    </head>

    <body>

            <div class="header">
                <?php
                    include('template/header_evento.php');
                ?>
        </div>

        <!-- Informações -->
        <div class="container row " style="font-size: 15px; margin-top: 2%">
            <div class="col-12">
                <div class="cabeçalho" style="font-size: 20px; padding-top: 2%; text-align: center;">
                    <h4>3ª Semana Acadêmica Integrada FAOSC 2023/1º</h4>
                </div>
                <div class="container" style="font-size: 13px; padding: 2%; text-align: justify;">
                   
                    <div class="row">
                        <div class="col-md-12">
                            <h5 style="text-align: center;">...</h5>
                        </div>
                    </div>

                    <div class="row">
                            <div class="col-md-6" >
                                <div style="text-align: center;">
                                    <h5> 09/05/2023</h5>
                                    <h6><b>Tema: </b>Sustentabilidade e Empresa Rural: Desafios e possibilidades no Agronegócio </h6>
                                    <h6><b>Palestrantes: </b> Joselita Regina Tedesco Zanella e Lorival Zanluchi </h6>                         
                                </div>
                            </div>
                             <div class="col-md-6" >
                                <div style="text-align: center;">
                                    <h5> 10/05/2023</h5>
                                    <h6><b>Tema: </b>Diálogos sobre Educação, Cultura e Redes Sociais</h6>
                                    <h6><b>Palestrante: </b>Profa. Dra. Geselda Baratto </h6>

                                    <h6><b>Tema:</b>Edificações Sustentáveis: uma alternativa a partir da técnica da Taipa de Pilão   </h6>
                                    <h6><b>Palestrante: </b> Prof. Doutorando Anderson Renato Vobornik Wolenski </h6>             
                                </div>
                            </div>
                    </div>

                    <div class="row" style="margin-top:5%">
                            <div class="col-md-6" >
                                <div style="text-align: center;">
                                    <h5> 11/05/2023</h5>
                                    <h6><b>Tema: </b>Biotecnologia: Transgênicos para quê?</h6>
                                    <h6><b>Palestrante: </b>Dr. Arnildo Korb</h6>
                                    <h6><b>Tema: </b>Edificações Sustentáveis: uma alternativa a partir da técnica da Taipa de Pilão</h6>
                                    <h6><b>Palestrante: </b>Prof. Doutorando Anderson Renato Vobornik Wolenski
</h6>                         
                                </div>
                            </div>
                             <div class="col-md-6" >
                                <div style="text-align: center;">
                                    <h5> 12/05/2023</h5>
                                    <h6>Noite de Confraternizagao Universal dos Estudantes FAOSC (Reserva de espaço cultural e/ou de entretenimento) - Computação de horas Livre de participacao</h6>                       
                                </div>
                            </div>
                    </div>

                            
                   
                </div>
            </div>
        </div>

        <div class="increvase" style="margin-bottom: 5%; float: certer; text-align: center">
                <a class="btn btn-primary btn-lg" href="#">Increva-se</a>
        </div>

                
        <!-- rodapé -->
        <footer class="container">
            <?php
               require('template/footer.html');
            ?>
        </footer>
      
    </body>

</html>

