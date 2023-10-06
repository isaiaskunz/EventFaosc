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

<!DOCTYPE HTML>  
<html>
    <head>
        <style>
            <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
            .error {color: #FF0000;}
        </style>
    </head>
    <body>  
        <?php
            $nome_atividadeErr = $data_inicioErr = $data_fimErr = $valor_inscricaoErr = $lotacao_maximaErr = "";
            $nome_atividade = $id_atividade = $sala =  $id_evento= $id_endereco = $id_tipo_atividade = $id_apresentador = $data_hora_inicio = $data_hora_fim = $gender = $valor_inscricao = $lotacao_maxima  = "";
            $logradouroErr = $numeroErr = $complementoErr = $bairroErr = $cepErr = "";
            $id_pais = $numero =$id_uf = $id_municipio = $logradouro = $carga_horaria = $complemento = $bairro = $cep = "";
            $descricao_longaErr = $id_tipo_atividadeErr = "";
            $descricao_longa = $id_tipo_atividade = "";
            
            $ok = TRUE;

            $id_pais = '27';
            $id_tipo_documento = '1';
            $numero_documento ='';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
/*                if (empty($_POST["nome_atividade"])) {
                    $nome_atividadeErr = " Informe um nome válido";
                    $ok=FALSE;
                } else {
*/                    $nome_atividade = test_input($_POST["nome_atividade"]);
/*                    if ($nome_atividade == '') {
                        $nome_atividadeErr = " Nome Inválido";
                        $ok=FALSE;
                    }  
                }

                if (empty($_POST["data_hora_inicial"])) {
                    $data_inicioErr = "Informe uma data válida";
                    $ok=FALSE;
                } else {
*/                    $data_hora_inicial = $_POST["data_hora_inicial"];
/*                    
                    if (!preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/",$data_inicio)) {
                        $data_inicioErr = " Data Inválida".$data_inicio;
                        $ok=FALSE;
                    }  
                }

                if (empty($_POST["data_hora_final"])) {
                    $data_fimErr = "Informe uma data válida";
                    $ok=FALSE;
                } else {
*/                    $data_hora_final = $_POST["data_hora_final"];
/*                    if (!preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/",$data_fim)) {
                        $data_fimErr = " Data Inválida";
                        $ok=FALSE;
                    }  
                }

               /* if (empty($_POST["lotacao_maxima"])) {
                    $lotacao_maximaErr = "Informe uma lotação máxima válida";
                    $ok=FALSE;
                } else {
*/                    $sala = $_POST["sala"];
/*                    if (!preg_match("/^[0-9]+$/",$lotacao_maxima)) {
                        $lotacao_maximaErr = "A lotacão máxima informada é inválida";
                        $ok=FALSE;
                    }
                }  
                if (empty($_POST["valor_inscricao"])) {
                    $valor_inscricaoErr = "Informe uma valor";
                    $ok=FALSE;
                } else {
*/                    $carga_horaria = $_POST["carga_horaria"]; #str_replace(',','.', $_POST["valor_inscricao"]);
                    if (!preg_match('/^([1-9]{1}[\d]{0,2}(\.[\d]{3})*(\,[\d]{0,2})?|[1-9]{1}[\d]{0,}(\,[\d]{0,2})?|0(\,[\d]{0,2})?|(\,[\d]{1,2})?)$/',$valor_inscricao)) {
                        $valor_inscricaoErr = "O valor informado é inválido";
                        $ok=FALSE;
                    }
                }

                if ($_POST["id_endereco"]=='novo_endereco') {
                    
                    if (empty($_POST["numero"])) {
                        $numeroErr = "Informe um número válido";
                        $ok=FALSE;
                    } else {
                        $numero= $_POST["numero"];
                        if (!preg_match("/^[0-9]+$/",$numero)) {
                            $numeroErr = "O número informado é inválido";
                            $ok=FALSE;
                        }
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

                #se tudo está ok, insere no db:
                if ($ok == TRUE){

                    include "../db.php";

                    if ($_POST["id_endereco"]=='novo_endereco1') { 
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
                            echo "Erro ao cadastrar a atividade - contate o administrador do sistema";
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
                            echo "Erro ao cadastrar a atividade - contate o administrador do sistema";
                        } 
                    }
                   
                    $sql = "INSERT INTO atividade (
                                id_atividade
                                id_apresentador
                                id_evento
                                nome_atividade, 
                                id_tipo_atividade, 
                                data_hora_inicio,
                                data_hora_final, 
                                sala,
                                carga_horaria,
                                descricao_longa,
                                id_endereco) 
                            VALUES (                                
                                '$id_atividade',
                                 $id_apresentador,
                                '$id_evento',                                
                                '$nome_atividade',
                                '$id_tipo_atividade',
                                '$data_hora_inicio',
                                '$data_hora_final',
                                '$sala',
                                '$carga_horaria',
                                '$descricao_longa',
                                '$id_endereco')";
                    $result = $conn->query($sql);
                    if ($result == TRUE) {
                        header("Location: ./events.php");
                        exit();
                    }else{
                        echo "Erro ao cadastrar a
                        
                        atividade - contate o administrador do sistema ".$sql;
                    } 
                    $conn->close();  
                }
                      
        ?>

        <h2>Cadastrar Atividade</h2>
        <p><span class="error">* Campos obrigatórios</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
            Tipo: <select id = "id_tipo_atividade" name="id_tipo_atividade">
                <?php 
                    $sql = $conn->query("SELECT * FROM tipo_atividade");
                    while ($row = $sql->fetch_assoc()){
                        echo "<option value=\"". $row['id_tipo_atividade'] ."\">" . $row['descricao'] . "</option>";
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
            <span class="error">* </span>
            <br><br>
            Nome da Atividade: <input type="text" name="nome_atividade">
            <span class="error">* <?php echo $nome_atividadeErr;?></span>
            <br><br>
            Data de Inicio da Atividade: <input type="date" name="data_hora_inicial" placeholder="dd/mm/yyyy" value="">
            <span class="error">* no formato dd/mm/aaaa <?php echo $data_inicioErr;?></span>
            <br><br>
            Data de Fim da Atividade: <input type="date" name="data_hora_final" placeholder="dd/mm/yyyy" value="">
            <span class="error">* no formato dd/mm/aaaa <?php echo $data_fimErr;?></span>
            <br><br>
            Tempo da Atividade: <input type="text" name="carga_horaria">
            <span class="error">* <?php echo $carga_horaria;?></span>
            <br><br>
            Sala da Atividade: <input type="text" name="sala">
            <span class="error">* <?php echo $sala;?></span>
            <br><br>
            Local de Atividade: <select id = "id_endereco" name="id_endereco" onchange="cadastrarEndereco()">
                <?php 
                    $sql = $conn->query("SELECT * FROM usuario_endereco as u 
                    INNER JOIN endereco as e 
                    ON u.id_endereco=e.id_endereco
                    WHERE id_usuario=1");
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
                        //opt.value = '1'
                        //opt.selected = true;
                    });
                </script>    
            </select>
            <span class="error">* </span>
            <br><br>
            <div id = "form_novo_endereco" name = "form_novo_endereco" <?php
                             if (mysqli_num_rows($sql)>0){
                                echo 'style="display: none;"';
                                }?> >
                Pais: <select id = "id_pais" name="id_pais">
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
                    </script>    
                </select>
                <span class="error">* </span>
                <br><br>
                UF: <select id = "id_uf" name="id_uf"">
                    <?php 
                        $sql = $conn->query("SELECT * FROM uf");
                        while ($row = $sql->fetch_assoc()){
                            echo "<option value=\"". $row['id_uf'] ."\">" . $row['uf'] . "</option>";
                        }
                    ?>
                    <script>
                        window.addEventListener('DOMContentLoaded', (e) => {
                            var opt = document.getElementById("id_uf");
                            //opt.value = '1'
                            //opt.selected = true;
                        });
                    </script>    
                </select>
                <span class="error">* </span>
                <br><br>
                Municipio:
                        <select name="cod_cidades" id="cod_cidades">
                        <option value="">-- Escolha um estado --</option>
                        </select>
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	                    <script type="text/javascript">
                            $(function(){
                                $('#id_uf').change(function(){
                                    if( $(this).val() ) {
                                    $('#cod_cidades').hide();
                                    $('.carregando').show();
                                    $.getJSON(
                                        '../get_cidades.php?search=',
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
                <span class="error">* </span>
                <br><br>
                Logradouro: <input type="text" name="logradouro" value="<?php echo htmlentities( $logradouro) ; ?>">
                <span class="error">* <?php echo $logradouroErr;?></span>
                <br><br>
                Numero: <input type="text" name="numero" value="<?php echo htmlentities( $numero ) ; ?>">
                <span class="error">* <?php echo $numeroErr;?></span>
                <br><br>
                Complemento: <input type="text" name="complemento" value="<?php echo htmlentities( $complemento ) ; ?>">
                <span class="error"> <?php echo $complementoErr;?></span>
                <br><br>
                Bairro: <input type="text" name="bairro" value="<?php echo htmlentities( $bairro ) ; ?>">
                <span class="error">* <?php echo $bairroErr;?></span>
                <br><br>
                Código Postal: <input type="text" name="cep" value="<?php echo htmlentities( $cep) ; ?>">
                <span class="error">* somente números <?php echo $cepErr;?></span>
                <br><br>
            </div>
            Descrição Longa da Atividade: <textarea rows="5" cols="60" id="descricao_longa" name="descricao_longa" value="<?php echo htmlentities( $descricao_longa) ; ?>"></textarea>
            <span class="error"> <?php echo $descricao_longaErr;?></span>
            <br><br>
            <input type="submit" name="submit" value="Cadastrar">  
        </form>
    </body>
</html>