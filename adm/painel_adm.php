<?php
    if (isset($_SESSION)) {
        if ($_SESSION['admin'] != TRUE){
            echo "você não tem permissão para acessar este recurso";
            exit();
        }
    }
    else {
        echo "você não tem permissão para acessar este recurso";
        echo "Logue em seu usuário novamente:";
        //echo "<meta http-equiv = 'refresh' content = '5; url='../login.php'/>"
        /*include('../login.php')
        echo <header="../login";></header>*/
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
                <h5 style="color: black;"><b>PAINEL ADMINISTRATIVO</b></h5>
            </div>
            
        </div>
        <div class="row">
            <div class="col">
                <a  href="adm/users.php">Usuários</a><br>
            </div>
            <div class="col">
                <a  href="registro.php">Cadastro Usuários</a><br>
            </div>
            <div class="col">
                <a  href="adm/events.php">Cadastro de Eventos</a><br>
            </div>
            <div class="col">
                <a  href="adm/listar_certificados.php">Listar Certificados</a><br>
            </div>
            <div class="col">
                <a  href="adm/conf_pagamentos.php">Confirmação de Pagamentos</a><br>
            </div>
            <div class="col">
                <a  href="adm/relatorio_avaliacao2.php">Relatório da Avaliação</a><br>
            </div>      
        </div>
    </div>
    
<?php

?>