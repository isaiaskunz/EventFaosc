<?php 
    if (isset($_SESSION['id']) != TRUE){
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
                <h5 style="color: black;"><b>PAINEL DO USUARIO/PARTICIPANTE</b></h5>
            </div>
            
        </div>
        <div class="row">
            <div class="col">
                <a href="registro.php?id=<?php echo $_SESSION['id']; ?>">Perfil</a><br>
            </div>
            <div class="col">
                <a href="participante/cadastrar_no_evento.php">Inscrições em evento</a><br>
            </div>
            <div class="col">
                <a href="participante/certificado.php">Certificados</a>
            </div>
            <div class="col">
                 <a href="alterar_senha.php?id=<?php $_SESSION['user_name']; ?>">Alterar Senha</a><br>
            </div>          
        </div>
</div>

<?php
    echo '<p><img src="participante/qr.php?id='.$_SESSION['id'].'" /></p>';
 
?>