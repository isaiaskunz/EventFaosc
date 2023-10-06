<?php
    if ($_SESSION['credenciador'] != TRUE){
        echo "você não tem permissão para acessar este recurso";
        exit();
    }

?>

<style type="text/css">
    
    .painel-admin{
        background-color: #F2F2F2;
        margin-bottom: 5%;
        padding-top: 5%;
        padding-bottom: 2%;
        padding-left: 2%;
        padding-right: 1%;
    }

    a{
        text-decoration-color: dimgrey;
        color: #0B0B3B;
    }
    a:hover{
        color: darkgreen;
    }
</style>

 <div class="painel-admin">
        <div class="row">
            <div class="col-md-12" style="text-align:center;"> 
                <h5 style="color: black;"><b>PAINEL DO CREDENCIADOR</b></h5>
            </div>
            
        </div>
        <div class="row">
            <div class="col">
                <a href="./credenciador/relatorio_presencas.php">Relatório de Presença</a><br>
            </div>
            <div class="col">
                <a href="registro.php">Cadastro de Usuários</a><br>
            </div>
            <div class="col">
                <a href="./credenciador/cadastrar_participante_no_evento.php">Inscrição de Participante em Evento</a>
            </div>
            <div class="col">
                 <a href="registro_presenca.php">Registro de Presença</a><br>
            </div>          
        </div>
    </div>
<?php

?>