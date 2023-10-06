<?php
    if (isset($_SESSION['palestrante']) != TRUE){
        echo "você não tem permissão para acessar este recurso";
        exit();
    }
?>
<div class="painel-admin">
        <div class="row">
            <div class="col-md-12" style="text-align:center;"> 
                <h5 style="color: black;"><b>PAINEL DO PALESTRANTE</b></h5>
            </div>
            
        </div>
        <div class="row">
            
            <div class="col">
                <a href="#">Palestras Ministradas Anteriormente</a><br>
            </div>
             <div class="col">
                <a href="#">Palestras em Próximos Eventos</a><br>
            </div>

        </div>
</div>               
