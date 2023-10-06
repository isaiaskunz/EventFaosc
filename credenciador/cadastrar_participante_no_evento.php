<?php
    session_start();
    if (!isset($_SESSION['id'])){
        echo "você não tem permissão para acessar este recurso";
        exit();
    }
    include_once('../tools.php');
    include_once("../db.php");
?>

<!DOCTYPE HTML>  
<html>
    <head>
        <style>
            <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
            .error {color: #FF0000;}
        </style>
        <link rel="stylesheet" type="text/css" href="style.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        <style type="text/css">
        </style>
    </head>
    <body>  
        <?php
            $id_inscricao = $id_usuario = $id_forma_pagamento = $id_evento = $arquivo_comprovante = "";
                                 
            $ok = TRUE;

            $id_pais = '27';
            $id_tipo_documento = '1';
            $numero_documento ='';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {           
                    $msg_alerta = "";                
                    $id_usuario = $_POST["id_usuario"];
                    $id_forma_pagamento = $_POST["id_forma_pagamento"];
                    $id_evento = $_POST["id_evento"];    
                    $sql = "SELECT * from inscricao_evento where id_usuario = " . $id_usuario . " and id_evento = " . $id_evento;
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) === 1) { // se encontrou registro, então deve atualiza-lo          
                        if ($result == TRUE) {
                            $msg_alerta = "Este participante já está inscrito no evento!";
                        }
                    } else {
                        $sql = "INSERT INTO inscricao_evento (
                                    id_usuario,
                                    id_forma_pagamento,
                                    id_evento, 
                                    arquivo_comprovante
                                    )
                                VALUES (            
                                    $id_usuario,
                                    '$id_forma_pagamento',                                
                                    '$id_evento',
                                    '')";
                        $result = $conn->query($sql);
                        if ($result == TRUE) {
                            $msg_alerta =  "Inscrição no evento realizada com sucesso.";
                        }else{
                            $msg_alerta =  "Erro ao concluir inscrição no evento - contate o administrador do sistema ".$sql;
                        } 
                    }
                    if (strlen($msg_alerta) > 2){
                        echo "<script> alert('".$msg_alerta."'); </script>";
                    }
            }

 //       $conn->close();  

                      
        ?>

        <?php 
            include "../header.php";


        ?>


        <div class="container row-12" style="margin-top:5%;">
            <div class="col-md-12">
                <h4>Inscrição</h4>
                <p><span class="error">* Campos obrigatórios</span></p>
            </div>
            
            <div class="col-md-2">                
            </div>
            <div class="col-md-8">
                         
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  enctype="multipart/form-data">  
                             
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form-outline col-md-6" id="pesquisar_nome" >
                                    <label class="form-label" for="form2Example2">Nome:</label>
                                    <input type="text" id="inp_nome_pesquisar" class="form-control" name="nome_pesquisar" /> 
                                    <!--<button style="margin-top: 5px;"  type="submit" class="btn btn-primary mb-4" onclick="pesquisar_nome()">Pesquisar Nome</button> -->
                                    <p class="btn btn-primary mb-4" onclick="pesquisar_nome()">Pesquisar Nome</p>
                                    <div class="form-outline" id="resultados_pesquisa_nomes">

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-outline col-md-6" id="pesquisar_id_usuario">
                                    <label class="form-label" for="form2Example2">ID Usuário:</label>
                                    <input type="text" id="id_usuario" class="form-control" name="id_usuario" />        
                                </div>
                            </div>
                            <div class="form-outline" id="dados_encontrados"> <!--hidden> -->    
                            </div>
                        </div>                    
                    </div>

                    <label class="form-label" for="form2Example1">Forma de Pagamento: <span class="error"> </span></label>
                        <select id = "id_forma_pagamento" name="id_forma_pagamento" class="form-select" aria-label="Default select example">
                            <?php 
                                $sql = $conn->query("SELECT * FROM forma_pagamento");
                                while ($row = $sql->fetch_assoc()){
                                    echo "<option value=\"". $row['id_forma_pagamento'] ."\">" . $row['descricao_forma_pagamento'] . "</option>";
                                }
                            ?>
                            <script>
                                window.addEventListener('DOMContentLoaded', (e) => {
                                    var opt = document.getElementById("id_tipo_documento");
                                    opt.value = '<?php echo htmlentities( $id_tipo_documento );?>'
                                    opt.selected = true;
                                });
                            </script>    
                        </select> *

                    <br><br>
                    
                    <label class="form-label" for="form2Example1">Evento: <span class="error"> </span></label>
                        <select id = "id_evento" name="id_evento" class="form-select" aria-label="Default select example">
                            <?php 
                                $sql = $conn->query("SELECT * FROM evento");
                                while ($row = $sql->fetch_assoc()){
                                    echo "<option value=\"". $row['id_evento'] ."\">" . $row['nome_evento'] . "</option>";
                                }
                            ?>
                            <script>
                                window.addEventListener('DOMContentLoaded', (e) => {
                                    var opt = document.getElementById("id_tipo_documento");
                                    opt.value = '<?php echo htmlentities( $id_tipo_documento );?>'
                                    opt.selected = true;
                                });
                            </script>    
                        </select> *
                            
                    <br><br>
                    <div class="text-center">
                        <input id="submit" type="submit" class="btn btn-primary mb-4" name="submit" value="Cadastrar">
                        <a class="btn btn-secondary  mb-4" href="javascript:javascript:history.go(-1)">Retornar</a>   
                    </div>                                
                </form>

            </div>
            <div class="col-md-2">                
            </div>
        </div>
        
    </body>

    <footer  class="container" style="margin-top:5%;">
        <?php 
            include "../template/footer.html";
        ?>

    </footer>
</html>
<script>

    var base_url = window.location.origin;
    if (base_url.search("localhost") > 0){
        base_url += "/eventfaosc";
    }
    console.log(base_url);
    
    function pesquisar_nome(){
            nome_pesquisar = document.getElementById("inp_nome_pesquisar").value;
            document.getElementById("id_usuario").value = "";
            document.getElementById("dados_encontrados").innerHTML = "";
            console.log(nome_pesquisar);
            $.ajax({
                url: base_url+"/receber_presenca.php",
                type: "POST",
                data: "nome_pesquisar="+nome_pesquisar, 
                dataType: "html"
            }).done(function(resposta) {
                console.log(resposta);
                mostrar_texto_na_div_resultados_nomes(resposta);
            }).fail(function(jqXHR, textStatus ) {
                console.log("Request failed: " + textStatus);
            }).always(function() {
                console.log("completou");
            }); 
        }

        function mostrar_texto_na_div_resultados_nomes(resposta){
            var div_texto = document.getElementById("resultados_pesquisa_nomes");
            var resposta_array = resposta.split("-|-");
            var text = "";
            
            if (resposta_array.length > 2){
                for (let i = 0; i < resposta_array.length-1; i+=3) {
                    text += "<p onclick= 'set_user_id("+resposta_array[i]+")'>" + resposta_array[i+1] + " " + resposta_array[i+2]+"</p><br>";
                }
                div_texto.innerHTML = text;
            } else {
                div_texto.innerHTML = "Não foram encontrados resultados para o nome buscado.";
            }
            //mostrar_texto_na_div(resposta);resultados_pesquisa_nomes
        }

        function set_user_id(id){
            document.getElementById("id_usuario").value = id;
            document.getElementById("inp_nome_pesquisar").value = "";
            document.getElementById("resultados_pesquisa_nomes").innerHTML="";
        }
        </script>