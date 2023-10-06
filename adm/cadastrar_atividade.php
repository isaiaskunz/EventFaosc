<!DOCTYPE html>  
<html lang="en">
<?php
    include "../header.php";
    if (isset($_SESSION)) {
        if ($_SESSION['admin'] != TRUE){
            echo "você não tem permissão para acessar este recurso";
            exit();
        }
    }
    include_once('../tools.php'); 
    include_once('../db.php');
?>

<?php
            $nome_atividadeErr = $data_hora_inicioErr = $data_hora_fimErr = $valor_inscricaoErr = $lotacao_maximaErr = "";
            $nome_atividade = $data_hora_inicio = $data_hora_fim = $gender = $valor_inscricao = $lotacao_maxima  = "";
            $logradouroErr = $numeroErr = $complementoErr = $bairroErr = $cepErr = "";
            $id_pais = $id_uf = $id_municipio = $logradouro = $numero = $complemento = $bairro = $cep = "";
            $descricao_longaErr = $descricao_curtaErr = $id_tipo_atividadeErr = "";
            $descricao_longa = $descricao_curta = $id_tipo_atividade = "";
            $sala = $salaErr = $carga_horaria = $carga_horariaErr = "";
            
            
            $ok = TRUE;
            

            $id_pais = '27';
            $id_tipo_documento = '1';
            $numero_documento ='';

            if (isset($_GET['id_evento'])){
                $id_evento = $_GET['id_evento'];
            }

            if (isset($_GET['id']) == TRUE && isset($_SESSION['admin'])){
                $editando = TRUE;
                $sql_check = "SELECT * FROM atividade WHERE id_atividade = ".$_GET['id'];
                $result_check = $conn->query($sql_check);
                if ($result_check->num_rows > 0) {
                    $row = $result_check->fetch_assoc();
                    $id_tipo_atividade = $row["id_tipo_atividade"]; 
                    $nome_atividade = $row["nome_atividade"]; 
                    $data_hora_inicio = $row["data_hora_inicio"];
                    $data_hora_fim = $row["data_hora_fim"]; 
                    $lotacao_maxima = $row["lotacao_maxima"]; 
                    $valor_inscricao = $row["valor_inscricao"]; 
                    $descricao_curta = $row["descricao_curta"];
                    $descricao_longa = $row["descricao_longa"];
                    $id_endereco = $row["id_endereco"];  
                    $sala = $row["sala"]; 
                    $carga_horaria = $row["carga_horaria"]; 
                    $id_evento = $row["id_evento"]; 
                    $sql_check = "SELECT * FROM endereco WHERE id_endereco = $id_endereco";
                    $result_check = $conn->query($sql_check);
                    if ($result_check->num_rows > 0) {
                        $row = $result_check->fetch_assoc();
                        $id_uf= $row["id_uf"]; 
                        $id_pais= $row["id_pais"];
                        $id_municipio= $row["id_municipio"];
                        $logradouro= $row["logradouro"];
                        $numero= $row["numero"];
                        $bairro= $row["bairro"];
                        $complemento= $row["complemento"];
                        $cep= $row["cep"];
                    }                        
                } else {
                        echo "atividade não existe";
                        exit();
                }
            }
            else 
            {
                $editando = FALSE;
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {                
                if (empty($_POST["nome_atividade"])) {
                    $nome_atividadeErr = " Informe um nome válido";
                    $ok=FALSE;
                } else {
                    $nome_atividade = test_input($_POST["nome_atividade"]);
                    if ($nome_atividade == '') {
                        $nome_atividadeErr = " Nome Inválido";
                        $ok=FALSE;
                    }  
                }

                if (empty($_POST["data_hora_inicio"])) {
                    $data_hora_inicioErr = "Informe uma data válida";
                    $ok=FALSE;
                } else {
                    $data_hora_inicio = $_POST["data_hora_inicio"];
                }

                if (empty($_POST["data_hora_fim"])) {
                    $data_hora_fimErr = "Informe uma data válida";
                    $ok=FALSE;
                } else {
                    $data_hora_fim = $_POST["data_hora_fim"];
                }  
                

                if (empty($_POST["lotacao_maxima"])) {
                    $lotacao_maximaErr = "Informe uma lotação máxima válida";
                    $ok=FALSE;
                } else {
                    $lotacao_maxima = $_POST["lotacao_maxima"];
                    if (!preg_match("/^[0-9]+$/",$lotacao_maxima)) {
                        $lotacao_maximaErr = "A lotacão máxima informada é inválida";
                        $ok=FALSE;
                    }
                }  

                if (empty($_POST["carga_horaria"])) {
                    $carga_horariaErr = "Informe uma carga horária válida";
                    $ok=FALSE;
                } else {
                    $carga_horaria = $_POST["carga_horaria"];
                    if (!preg_match("/^[0-9]+$/",$carga_horaria)) {
                        $carga_horariaErr = "A carga horaria informada é inválida";
                        $ok=FALSE;
                    }
                }  

                if (empty($_POST["valor_inscricao"])) {
                    $valor_inscricaoErr = "Informe um valor";
                    $ok=FALSE;
                } else {
                    $valor_inscricao = $_POST["valor_inscricao"]; #str_replace(',','.', $_POST["valor_inscricao"]);
                    if (!preg_match('/^([1-9]{1}[\d]{0,2}(\.[\d]{3})*(\,[\d]{0,2})?|[1-9]{1}[\d]{0,}(\,[\d]{0,2})?|0(\,[\d]{0,2})?|(\,[\d]{1,2})?)$/',$valor_inscricao)) {
                        $valor_inscricaoErr = "O valor informado é inválido";
                        $ok=FALSE;
                    }
                }

                if ($_POST["id_endereco"] == 'novo_endereco') {
                    
                    if (empty($_POST["numero"])) {
                        $numeroErr = "Informe um número válido";
                        $ok=FALSE;
                    } else {
                        $numero= $_POST["numero"];
                        
                    } 

                    if (empty($_POST["logradouro"])) {
                        $logradouroErr = " Informe um logradouro válido";
                        $ok=FALSE;
                    } else {
                        $logradouro = test_input($_POST["logradouro"]);
                        if ($logradouro == '') {
                            $logradouroErr = " Logradouro Inválido";
                            $ok=FALSE;
                        }  
                    }

                    if (empty($_POST["bairro"])) {
                        $bairroErr = " Informe um bairro válido";
                        $ok=FALSE;
                    } else {
                        $bairro = test_input($_POST["bairro"]);
                        if ($bairro == '') {
                            $bairroErr = " Bairro Inválido";
                            $ok=FALSE;
                        }  
                    }
                    
                    if (empty($_POST["cep"])) {
                        $cepErr = "Informe um código postal válido";
                        $ok=FALSE;
                    } else {
                        $cep= $_POST["cep"];
                        if (!preg_match("/^[0-9]+$/",$cep)) {
                            $cepErr = "O código postal informado é inválido";
                            $ok=FALSE;
                        }
                    } 

                    $id_pais = $_POST["id_pais"];
                    $id_uf = $_POST["id_uf"];
                    $complemento = $_POST["complemento"];
                    $id_municipio = $_POST["cod_cidades"];
                    
                }
                else {
                    $id_endereco = $_POST["id_endereco"];
                }                 
                $id_tipo_atividade = $_POST["id_tipo_atividade"];
                $id_endereco = $_POST["id_endereco"];
                $descricao_longa = $_POST["descricao_longa"];
                $descricao_curta = $_POST["descricao_curta"];
                $sala = $_POST["sala"];

                #se tudo está ok, insere no db:
                
                if ($ok == TRUE){
                    include "../db.php";

                    if ($_POST["id_endereco"]=='novo_endereco') {
                        $sql = "INSERT INTO endereco (
                            id_pais,
                            id_uf, 
                            id_municipio, 
                            logradouro,
                            numero, 
                            bairro,
                            complemento,
                            cep) 
                        VALUES (
                            '$id_pais',
                            '$id_uf',
                            '$id_municipio',
                            '$logradouro',
                            '$numero',
                            '$bairro',
                            '$complemento',
                            '$cep')";
                        $result = $conn->query($sql);
                        if ($result == TRUE) {
                            $id_endereco = $conn->insert_id;
                        }else{
                            echo "Erro ao cadastrar o atividade - contate o administrador do sistema";
                        } 
                        $id_usuario = $_SESSION['id'];
                        $sql = "INSERT INTO usuario_endereco (
                            id_usuario,
                            id_endereco) 
                        VALUES (
                            '$id_usuario',
                            '$id_endereco')";
                        $result = $conn->query($sql);
                        if ($result == TRUE) {
                            
                        }else{
                            echo "Erro ao cadastrar o endereço/atividade - contate o administrador do sistema";
                        } 
                    }
                    
                    
                    if ($editando) {
                        $sql = "UPDATE atividade SET
                            nome_atividade = '$nome_atividade', 
                            id_tipo_atividade = '$id_tipo_atividade', 
                            data_hora_inicio = '$data_hora_inicio',
                            data_hora_fim = '$data_hora_fim', 
                            lotacao_maxima = '$lotacao_maxima',
                            valor_inscricao = '$valor_inscricao',
                            descricao_curta = '$descricao_curta',
                            descricao_longa = '$descricao_longa',
                            sala = '$sala',
                            carga_horaria = '$carga_horaria',
                            id_endereco = '$id_endereco' WHERE id_atividade=".$_GET['id'];
                        $result = $conn->query($sql);
                        if ($result == TRUE) {
                            header("Location: atividades.php?id=".$id_evento."&msg=atividade atualizada com sucesso");
                            exit();
                        }else{
                            echo "Erro ao atualizar o atividade - contate o administrador do sistema ";
                        } 
                        $conn->close();  

                    } else {
                        $sql = "INSERT INTO atividade (
                                    nome_atividade, 
                                    id_tipo_atividade, 
                                    data_hora_inicio,
                                    data_hora_fim, 
                                    lotacao_maxima,
                                    valor_inscricao,
                                    descricao_curta,
                                    descricao_longa,
                                    id_endereco,
                                    id_evento,
                                    sala,
                                    carga_horaria) 
                                VALUES (
                                    '$nome_atividade',
                                    '$id_tipo_atividade',
                                    '$data_hora_inicio',
                                    '$data_hora_fim',
                                    '$lotacao_maxima',
                                    '$valor_inscricao',
                                    '$descricao_curta',
                                    '$descricao_longa',
                                    '$id_endereco',
                                    '$id_evento',
                                    '$sala',
                                    '$carga_horaria')";
                        $result = $conn->query($sql);
                        if ($result == TRUE) {
                            header("Location: atividades.php?id=".$id_evento."&msg=atividade criada com sucesso");
                            exit();
                        }else{
                            echo "Erro ao cadastrar o atividade - contate o administrador do sistema ";
                        } 
                        $conn->close();  
                    }
                }
           }
        ?>

    <head>
        <meta charset=UTF-8>
        <title>Registrar</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="//code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <script src="js/tinymce/tinymce.min.js"></script>
        
       

        <style>
            .error {color: #FF0000;}
            .container {
                margin-top: 3%; 
                font-size: 15px; 
                font-family: sans-serif;

            }
            .event{
                margin-top: 2%;
                text-align: center;
                font-size: 18px; 
                font-family: sans-serif;
            }
            
        </style>
    </head>
    <body  class="container"> 
        

<?php 
                if ($editando){ ?>
                    <h2 class="event">Visualização ou Alteração de Atividade</h2>
                    <p><span class="error">* Campos obrigatórios</span></p>
                    <form class="container col-8" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?id=<?php echo $_GET['id'] ?>">
            <?php 
                } else { ?>
                    <h1 class="event">Cadastrar Atividade</h1>
                    <p><span class="error">* Campos obrigatórios</span></p>
                    <form class="container col-8" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?id_evento=<?php echo $_GET['id_evento'] ?>">
            <?php 
                } ?>
        
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Tipo: <span class="error">* </span></label>
                <select id="id_tipo_atividade" name="id_tipo_atividade" class="form-select" aria-label="Default select example">
                    <?php 
                        $sql = $conn->query("SELECT * FROM tipo_atividade");
                        while ($row = $sql->fetch_assoc()){
                            echo "<option value=\"". $row['id_tipo_atividade'] ."\">" . $row['descricao_tipo_atividade'] . "</option>";
                        }
                    ?>
                    <script>
                        window.addEventListener('DOMContentLoaded', (e) => {
                            var opt = document.getElementById("id_tipo_atividade");
                            opt.value = '1'
                            opt.selected = true;
                        });
                    </script>    
                </select>
            </div>


            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Nome: <span class="error">* <?php echo $nome_atividadeErr;?></span> </label>
                <input type="text" name="nome_atividade" value="<?php echo htmlentities( $nome_atividade ) ; ?>" id="form2Example1" class="form-control" placeholder="4ª Encontro A.P.P."/>                                    
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Data de Inicio: <span class="error">* no formato dd/mm/aaaa hh:mm<?php echo $data_hora_inicioErr;?></span> </label>
                <input type="datetime-local" name="data_hora_inicio" value="<?php echo htmlentities( $data_hora_inicio ) ; ?>" id="form2Example1" class="form-control" "/>                                    
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Data de Encerramento: <span class="error">* no formato dd/mm/aaaa hh:mm <?php echo $data_hora_fimErr;?></span> </label>
                <input type="datetime-local" name="data_hora_fim" value="<?php echo htmlentities( $data_hora_fim ) ; ?>" id="form2Example1" class="form-control" />                                    
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Valor da Inscrição: <span class="error">* <?php echo $valor_inscricaoErr;?></span> </label>
                <input type="number" name="valor_inscricao" value="<?php echo htmlentities( $valor_inscricao ) ; ?>" id="form2Example1" class="form-control" placeholder="50,00"/>                                    
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Carga Horária: <span class="error">* <?php echo $carga_horariaErr;?></span> </label>
                <input type="number" name="carga_horaria" value="<?php echo htmlentities( $carga_horaria ) ; ?>" id="form2Example1" class="form-control" placeholder="235"/>                                    
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Lotação Máxima: <span class="error">* <?php echo $lotacao_maximaErr;?></span> </label>
                <input type="number" name="lotacao_maxima" value="<?php echo htmlentities( $lotacao_maxima ) ; ?>" id="form2Example1" class="form-control" placeholder="235"/>                                    
            </div>

            <br><h5 class="form-label" for="form2Example1">Endereço:</h5><br>

            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Seleção: <span class="error">* </span></label>
                <select id="id_endereco" name="id_endereco" class="form-select" aria-label="Default select example" onchange="cadastrarEndereco()">
                    <?php 
                    $sql = $conn->query("SELECT * FROM usuario_endereco as u 
                    INNER JOIN endereco as e 
                    ON u.id_endereco=e.id_endereco
                    WHERE id_usuario < 3");
                    while ($row = $sql->fetch_assoc()){
                        echo "<option value=\"". $row['id_endereco'] ."\">" . 
                            $row['logradouro'] . ", ".
                            $row['numero'] . ", ".
                            $row['complemento'] . ", ".
                            $row['bairro'].
                            "</option>";
                    }
                        echo "<option value=\"novo_endereco\">Cadastrar novo endereço</option>";
                    ?>
                    <script>
                        function cadastrarEndereco() {
                            var end = document.getElementById("id_endereco").value;

                            if (end === 'novo_endereco') {
                                document.getElementById("form_novo_endereco").style.display = "block";
                            } else {
                                document.getElementById("form_novo_endereco").style.display = "none";
                            }
                        }
                        window.addEventListener('DOMContentLoaded', (e) => {
                            var opt = document.getElementById("id_endereco");
                            if (opt.length > 1) {
                              opt.value = <?php echo $id_endereco ?>;
                              opt.selected = true;
                            }
                        });
                    </script>
                </select>
                    
            </div>

            
            <div id="form_novo_endereco" name="form_novo_endereco" <?php
                             if (mysqli_num_rows($sql)>0){
                                echo 'style="display: none;"';
                                }?> >
                
                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example1">País:<span class="error">*</span></label>
                    <select id ="id_pais" name="id_pais" class="form-select" aria-label="Default select example" onchange="selectChange()">
                        <?php 
                            $sql = $conn->query("SELECT * FROM pais");
                            while ($row = $sql->fetch_assoc()){
                                echo "<option value=\"". $row['id_pais'] ."\">" . $row['pais'] . "</option>";
                            }
                        ?>
                        <script>
                            window.addEventListener('DOMContentLoaded', (e) => {
                                var opt = document.getElementById("id_pais");
                                opt.value = '27'
                                opt.selected = true;
                            });
                            function selectChange() {
                                var selectElement = event.target;
                                var value = selectElement.value;
                                if (value == '27') {
                                    document.getElementById("ifCountry_27").hidden = false;
                                    document.getElementById("ifCountry_others").hidden = true;
                                    /*document.getElementById("failedCountryCheck").hidden = false;
                                    document.getElementById("failedCountryCheck").hidden = false;
                                    document.getElementById("submit").hidden = false;*/
                                }
                                else {
                                    document.getElementById("ifCountry_27").hidden = true;
                                }
                            }
                        </script>    
                    </select>            
                </div>

                <div class="form-outline mb-4" id="ifCountry_27">
                    <div class="form-outline mb-4">
                        <!--   Usuario pd selecioanr qualquer pais, porem UFs sempre mostram do Brasil -->
                        <label class="form-label" for="form2Example1">UF:</label>
                        <select id="id_uf" name="id_uf" class="form-select" aria-label="Default select example">
                                <?php 
                                    $sql = $conn->query("SELECT * FROM uf");
                                    while ($row = $sql->fetch_assoc()){
                                    echo "<option value=\"". $row['id_uf'] ."\">" . $row['uf'] . "</option>";
                                }
                            ?>
                            <script>
                                window.addEventListener('DOMContentLoaded', (e) => {
                                    var opt = document.getElementById("id_uf");
                                    opt.value = '0'
                                    opt.selected = true;
                                });
                            </script>    
                         </select>            
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Municipio:</label>
                        <select name="cod_cidades" id="cod_cidades" class="form-select" aria-label="Default select example">
                            <option value="">-- Escolha um Estado/UF --</option>
                        </select>
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
                        <script type="text/javascript">
                            $(function(){
                                $('#id_uf').change(function(){
                                    if( $(this).val() ) {
                                    $('#cod_cidades').hide();
                                    $('.carregando').show();
                                    $.getJSON(
                                        'get_cidades.php?search=',
                                        {
                                        cod_estados: $("#id_uf option:selected").text(),
                                        ajax: 'true'
                                        }, function(j){
                                        var options = '<option value=""></option>';
                                        for (var i = 0; i < j.length; i++) {
                                            options += '<option value="' +
                                            j[i].cod_cidades + '">' +
                                            j[i].nome + '</option>';
                                        }
                                        $('#cod_cidades').html(options).show();
                                        $('.carregando').hide();
                                        });
                                    } else {
                                    $('#cod_cidades').html(
                                        '<option value="">-- Escolha um estado --</option>'
                                    );
                                    }
                                });
                                });                                    
                        </script>    
                        </select>             
                    </div>
                </div>  <!-- end div case country is 27-->
            
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Logradouro: <span class="error">* <?php echo $logradouroErr;?></span></label>
                <input type="text" name="logradouro" value="<?php echo htmlentities( $logradouro ) ; ?>" id="form2Example1" class="form-control" placeholder="rua"/>
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Número: <span class="error">* <?php echo $numeroErr;?></span></label>
                <input type="text" name="numero" value="<?php echo htmlentities( $numero ) ; ?>" id="form2Example1" class="form-control" placeholder="número"/>
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Complemento: <span class="error"> <?php echo $complementoErr;?></span></label>
                <input type="text" name="complemento" value="<?php echo htmlentities( $complemento ) ; ?>" id="form2Example1" class="form-control" placeholder="complemento"/>
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Bairro: <span class="error">* <?php echo $bairroErr;?></span></label>
                <input type="text"  name="bairro" value="<?php echo htmlentities( $bairro ) ; ?>" id="form2Example1" class="form-control" placeholder="bairro"/>
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Código Postal: <span class="error">* somente números <?php echo $cepErr;?></span></label>
                <input type="text" name="cep" value="<?php echo htmlentities( $cep ) ; ?>" id="form2Example1" class="form-control" placeholder="89887000"/>
            </div>
            
        </div>
        
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">Sala: <span class="error">* <?php echo $salaErr;?></span></label>
                <input type="text"  name="sala" value="<?php echo htmlentities( $sala ) ; ?>" id="form2Example1" class="form-control"/>
            </div>

            <div>
                <label class="form-label" for="form2Example1">Descrição Curta: </label>
                <br>
                <textarea name="descricao_curta" id="descricao_curta" cols="60" rows="10" value="<?php echo htmlentities( $descricao_curta) ; ?>"></textarea>
                <span class="error"> <?php echo $descricao_curtaErr;?></span>
               
                
            </div>
            <div>
                <label class="form-label" for="form2Example1">Descrição Longa: </label>
                <br>
                <textarea name="descricao_longa" id="descricao_longa" cols="60" rows="20" value="<?php echo htmlentities( $descricao_longa) ; ?>"></textarea>
                <span class="error"> <?php echo $descricao_longaErr;?></span>
            </div>

            <script type="text/javascript" >tinymce.init({
                selector: 'textarea',
                setup: function (editor) {
                    editor.on('init', function (e) {
                        if (editor.id == 'descricao_curta') {
                            editor.setContent("<?php echo $descricao_curta ?>");
                        }
                       
                        if (editor.id == 'descricao_longa') {
                            editor.setContent("insira novamente antes de salvar **** caso contrário ficará em branco *** esse erro será corrigido em uma futura versao");
                        }

                });
                }
            });
            </script>
            <?php 
                if ($editando) { ?>
                    <input type="submit" name="submit" value="Gravar">
            <?php 
                } else { ?>
                    <input type="submit" name="submit" value="Cadastrar">
            <?php 
                } ?>
                <a class="btn btn-secondary btn-block mb-4" href="javascript:javascript:history.go(-1)">Retornar</a> 
            <br><br>
        </form>

        
    </body>
</html>