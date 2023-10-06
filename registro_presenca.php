<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    $base_link = "https://eventos.faosc.edu.br/";
    if ($_SERVER['HTTP_HOST'] == "localhost"){
        $base_link = "http://localhost/eventfaosc/";
    }

    if (isset($_SESSION)) {
        if ($_SESSION['credenciador'] != TRUE){
            echo "Você não tem permissão para acessar este recurso";
            exit();
        } else { 
    
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Registrar entrada/saída na atividade</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js" ></script>	
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <base href="<?php echo $base_link; ?>">



  </head>




  <body class="container" style="float: center; text-align: center;">
    <header>
        <?php
            include $_SESSION['path']."header.php";
        ?>
    </header>

    <h4><b> Credenciamento</b></h4>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
            <div class="form-outline col-md-6" id="pesquisar_nome" >
                <label class="form-label" for="form2Example2">Nome:</label>
                <input type="text" id="inp_nome_pesquisar" class="form-control" name="nome_pesquisar" /> 
                <button style="margin-top: 5px;"  type="submit" class="btn btn-primary mb-4" onclick="pesquisar_nome()">Pesquisar Nome</button>
                <div class="form-outline" id="resultados_pesquisa_nomes">
                </div>
            </div>
            </div>
            <div class="row">
                <div class="form-outline col-md-6" id="pesquisar_id_usuario">
                    <label class="form-label" for="form2Example2">ID Usuário:</label>
                    <input type="text" id="inp_id_usuario_pesquisar" class="form-control" name="id_usuario_pesquisar" />        
                    <button style="margin-top: 5px;" type="submit" class="btn btn-primary mb-4" onclick="consultar_usuario('consulta')">Pesquisar id</button>
                </div>
            </div>
            <div class="form-outline" id="dados_encontrados"> <!--hidden> -->       
            <!--    Participante: jose <br>
                    tipo documento: 11111 <br>
                    Atividade do dia / noite <br>
                    Tipo do registro: entrada/saida<br> 
                    <button type="submit" class="btn btn-primary btn-block" >Registrar</button>
                    -->
            </div>
        </div>
        <div class="col-md-6">
            <video id="preview" class="container" style="width:100%; height:100%;">
            </video>
                <button style="margin-top: 5px;"  type="submit" class="btn btn-primary mb-4" onclick="mudar_espelhamento()">Mudar espelhamento</button>
        </div>
        
    </div>
    <br> 
        <script>
        var base_url = window.location.origin;
        if (base_url.search("localhost") > 0){
            base_url += "/eventfaosc";
        }
        console.log(base_url);
        let scanner = new Instascan.Scanner(
            {
                video: document.getElementById('preview'),
                mirror: true // prevents the video to be mirrored
            }
        );
        scanner.addListener('scan', function(id_participante) {
        //    alert('Escaneou id de usuário: ' + id_participante);       
            document.getElementById("inp_id_usuario_pesquisar").value = id_participante;
            consultar_usuario('consulta');
            //id_participante = 2;   // descomentar as duas linhas acima e comentar essa linha para usar o leitor do qrcode 
            //fazer_depois_da_leitura(id_participante); 

        }); // descomentar essa linha quando usar o leitor do qrcode
        Instascan.Camera.getCameras().then(cameras => 
        {
            if(cameras.length > 0){
                var id_camera = 0;
                if(cameras.length > 1){
                    id_camera = prompt("Você possui mais de uma camera, qual delas quer usar (0, 1)?", 0);
                }
                scanner.start(cameras[id_camera]);
            } else {
                console.error("Não existe câmera no dispositivo!");
            }
        });

        function consultar_usuario(tipo_registro){
            id_participante = document.getElementById("inp_id_usuario_pesquisar").value;
            $.ajax({
                url: base_url+"/receber_presenca.php",
                type: "POST",
                data: "id_participante="+id_participante+"&tipo_registro="+tipo_registro, 
                dataType: "html"
            }).done(function(resposta) {
                console.log(resposta);
                mostrar_texto_na_div_presenca(resposta);

            }).fail(function(jqXHR, textStatus ) {
                console.log("Request failed: " + textStatus);
            }).always(function() {
                console.log("completou");
            }); 
        }

        function mostrar_texto_na_div_presenca(resposta){
            var div_texto = document.getElementById("dados_encontrados");
            var resposta_array = resposta.split("-|-");
            if (resposta_array.length > 5){
                var texto_mostrar = "Participante: " + resposta_array[1] + " " + resposta_array[2];
                texto_mostrar += "<br>" + resposta_array[3] + ": " + resposta_array[4] + "<br>";
                
                var texto_botao = "Registrar ";
                var tipo_registro = "";
                var criar_botao = false;
                if(resposta_array[5] == 0){
                    texto_botao += "Entrada";
                    tipo_registro = "entrada";
                    criar_botao = true;
                } else if(resposta_array[5] == 1){
                    texto_botao += "Saída";
                    tipo_registro = "saida";
                    criar_botao = true;
                } else if(resposta_array[5] == 2){
                    texto_mostrar += "<b>Entrada e saída já registrados!</b>";
                    criar_botao = false;
                }

                div_texto.innerHTML = texto_mostrar;

                if (criar_botao){
                    var btn = document.createElement('BUTTON');
                    var lbl = document.createTextNode(texto_botao);        
                    btn.appendChild(lbl);   
                    btn.onclick = function()
                    {
                        consultar_usuario(tipo_registro);
                    }
                    div_texto.appendChild(btn); 
                }
            } else if (resposta_array.length === 2){
                div_texto.innerHTML = resposta_array[1];            
            } else if (resposta == "-1"){
                div_texto.innerHTML = "<b>Inscrição no envento não encontrada!</b>";
            } else {
                div_texto.innerHTML = "";
            }

            if (resposta_array[6] > 0) {
                document.getElementsByTagName("h4")[0].innerHTML = "<b> Credenciamento </b> (" + resposta_array[6] + ")";
            }
        }

        function pesquisar_nome(){
            nome_pesquisar = document.getElementById("inp_nome_pesquisar").value;
            document.getElementById("inp_id_usuario_pesquisar").value = "";
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
            document.getElementById("inp_id_usuario_pesquisar").value = id;
            document.getElementById("inp_nome_pesquisar").value = "";
            document.getElementById("resultados_pesquisa_nomes").innerHTML="";
            consultar_usuario('consulta');
        }

        function mudar_espelhamento(){
            scanner.mirror = !scanner.mirror;
        }

    </script>

 </body>
</html>

<?php 
    }
}
?>
