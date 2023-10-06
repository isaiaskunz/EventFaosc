<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if (!isset($_SESSION['id'])){
        header("location: login.php?voltar=participante/cadastrar_no_evento.php");
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
                
/*                if (empty($_POST["nome_atividade"])) {
                    $nome_atividadeErr = " Informe um nome válido";
                    $ok=FALSE;
                } else {
                    $id_inscricao = test_input($_POST["id_inscricao"]);
                    if ($id_inscricao == '') {
                        $id_inscricao = " Inscricao Inválido";
                        $ok=FALSE;
                    }  
                }                             
*/                    $id_usuario = $_POST["id_usuario"];
/*                    

               /* if (empty($_POST["lotacao_maxima"])) {
                    $lotacao_maximaErr = "Informe uma lotação máxima válida";
                    $ok=FALSE;
                } else {
*/                    $id_forma_pagamento = $_POST["id_forma_pagamento"];
/*                    if (!preg_match("/^[0-9]+$/",$lotacao_maxima)) {
                        $lotacao_maximaErr = "A lotacão máxima informada é inválida";
                        $ok=FALSE;
                    }
                    
                }  
                if (empty($_POST["valor_inscricao"])) {
                    $valor_inscricaoErr = "Informe uma valor";
                    $ok=FALSE;
                } else {
                    
*/
                $id_evento = $_POST["id_evento"];   
                if (strlen($_FILES['arquivo']['name']) > 3){  
                    $arquivo = $_FILES['arquivo']; 
                    $nome_arquivo_comprovante = "comprovante_" . $id_evento . "_" . $id_usuario . "." . strtolower(pathinfo($_FILES['arquivo']['name'])['extension']);  
                    receber_arquivo($arquivo, $nome_arquivo_comprovante);   
                } else {
                    $nome_arquivo_comprovante = null;
                }
//                }                  
                    $msg_alerta = "";
                    $sql = "SELECT * from inscricao_evento where id_usuario = " . $_SESSION['id'] . " and id_evento = " . $id_evento;
                    $result = mysqli_query($conn, $sql);
//                    $inscricao_realizada = "";
                    if (mysqli_num_rows($result) === 1) { // se encontrou registro, então deve atualiza-lo
                        $row = mysqli_fetch_assoc($result);
                        $id_inscricao = $row['id_inscricao'];
                        $sql = "UPDATE inscricao_evento 
                            SET arquivo_comprovante = '$nome_arquivo_comprovante', id_forma_pagamento = '$id_forma_pagamento'
                            WHERE id_inscricao = $id_inscricao";
                        $result = $conn->query($sql);            
                        if ($result == TRUE) {
                            $msg_alerta = "Inscrição em evento atualizada com sucesso!";
                        } else {
                            $msg_alerta = "Houve algum erro ao atualizar a inscrição no evento!";
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
                                    '$nome_arquivo_comprovante')";
                        $result = $conn->query($sql);
                        if ($result == TRUE) {
                            $msg_alerta =  "Inscrição no evento realizada com sucesso.";
                  //          exit();
                        }else{
                            $msg_alerta =  "Erro ao concluir inscrição no evento - contate o administrador do sistema ".$sql;
                        } 
                    }
 //                   $conn->close();  
                    if (strlen($msg_alerta) > 2){
                        echo "<script> alert('".$msg_alerta."'); </script>";
                    }
            }

        function receber_arquivo($arquivo, $nome_arquivo){
            // Pasta onde o arquivo vai ser salvo
            $_UP['pasta'] = 'comprovantes_pagamento/';
            
            // Tamanho máximo do arquivo (em Bytes)
            $_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
            
            // Array com as extensões permitidas
            $_UP['extensoes'] = array('jpeg', 'jpg', 'png', 'gif');
            
            // Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
            $_UP['renomeia'] = true;
            
            // Array com os tipos de erros de upload do PHP
            $_UP['erros'][0] = 'Não houve erro';
            $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
            $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
            $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
            $_UP['erros'][4] = 'Não foi feito o upload do arquivo';
            
            // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
            if ($arquivo['error'] != 0) {
                die("Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$arquivo['error']]);
                return false;
                exit; // Para a execução do script
            }
            
            // Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
            
            // Faz a verificação da extensão do arquivo
            $extensao = strtolower(pathinfo($arquivo['name'])['extension']);
            if (array_search($extensao, $_UP['extensoes']) === false) {
                echo "Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif";
                return false;
            }
            
            // Faz a verificação do tamanho do arquivo
            else if ($_UP['tamanho'] < $arquivo['size']) {
                echo "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
                return false;
            }
            
            // O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
            else {
                // Primeiro verifica se deve trocar o nome do arquivo
                if ($_UP['renomeia'] == true) {
                    // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
                    $nome_final = $nome_arquivo;
                } else {
                    // Mantém o nome original do arquivo
                    $nome_final = $_FILES['arquivo']['name'];
                }
                
                // Depois verifica se é possível mover o arquivo para a pasta escolhida
                if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
                    // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
                //    echo "Upload efetuado com sucesso!";
                    return true;
                } else {
                    // Não foi possível fazer o upload, provavelmente a pasta está incorreta
                    echo "Não foi possível enviar o arquivo, tente novamente";
                }            
            }
        }

        $sql = "SELECT * from inscricao_evento where id_usuario = " . $_SESSION['id'] . " and id_evento = 1";
        $result = mysqli_query($conn, $sql);
        $inscricao_ja_existe = "";
        if (mysqli_num_rows($result) === 1) { // se encontrou registro, então existe inscrição no evento
            $row = mysqli_fetch_assoc($result);
            $inscricao_ja_existe = "<h3>Inscrição já realizada neste evento!</h3>";
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
                
            <?php echo $inscricao_ja_existe; ?>
         
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  enctype="multipart/form-data">  
                          
                    <input class="form-control" type="hidden" name="id_usuario" value= <?php echo $_SESSION['user_name'];?>>
                    <br>

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
                    <label class="form-label">
                        Comprovante de pagamento (jpg, png ou gif): 
                    </label>
                    <input class="form-control" type="file" name="arquivo">
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