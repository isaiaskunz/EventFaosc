<!DOCTYPE HTML>  
<html>
    
    
     
        <?php
        
            if(!isset($_SESSION)) 
            { 
                session_start(); 
            }
            
            $_SESSION['path'] = $_SERVER['DOCUMENT_ROOT']."/";

            include $_SESSION['path'].'db.php';
            include $_SESSION['path'].'tools.php';

            $first_nameErr = $last_nameErr = $emailErr = $id_nacionalidadeErr = $id_estud_faoscErr = $passwordErr = $id_tipo_documentoErr = "";
            $first_name = $last_name = $email = $gender = $comment = $website = $password = "";
            $numero_documentoErr = $id_tipo_documento = "";
            $admin = $palestrante = $credenciador = false;
            $logradouroErr = $numeroErr = $complementoErr = $bairroErr = $cepErr = "";
            $id_nacionalidade = $id_estud_faosc = $id_pais = $id_uf = $id_municipio = $logradouro = $numero = $complemento = $bairro = $cep = "";
            $ok = TRUE;
            $editando = FALSE;
            

            $id_pais = '27';
            $id_tipo_documento = '1';
            $numero_documento ='';

            
            
            if (isset($_GET['id']) == TRUE){
                    if ($_SESSION['id'] == $_GET['id'] || $_SESSION['admin'] == TRUE){
                        $editando = TRUE;
                        $sql_check = "SELECT * FROM usuario WHERE id_usuario = ".$_GET['id'];
                        $result_check = $conn->query($sql_check);
                        if ($result_check->num_rows > 0) {
                        //check para ver se usuario ja existe
                            $row = $result_check->fetch_assoc();
                            $first_name = $row["nome"]; 
                            $last_name = $row["sobrenome"]; 
                            $id_nacionalidade = $row["id_nacionalidade"]; 
                            $id_estud_faosc = $row["id_tipo_aluno_faosc"]; 
                            $email = $row["email"]; 
                            $id_tipo_documento = $row["id_tipo_documento"];
                            $numero_documento = $row["numero_documento"];
                            $admin = $row["admin"];  
                            $eh_credenciador = $row["credenciador"];
                            $sql_check = "SELECT * FROM endereco WHERE id_endereco = 
                                                (select id_endereco from usuario_endereco where id_usuario=".$_GET['id']." LIMIT 1)";
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
                            echo "Usuário não existe";
                            exit();
                        }
                    } else {
                        header("Location: login.php");
                        exit();
                    }
            } else {
                $eh_credenciador = 0;
            }
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if ( !empty($_POST["id_tipo_perfil_acesso"])) {
                    if ($_POST["id_tipo_perfil_acesso"] == 4) {
                        $admin = true;
                    }
                    if ($_POST["id_tipo_perfil_acesso"] == 2) {
                        $palestrante = true;
                    }
                    if ($_POST["id_tipo_perfil_acesso"] == 3) {
                        $credenciador = true;
                    }  
                }

                if (empty($_POST["first_name"])) {
                    $first_nameErr = "Informe um nome válido";
                    $ok=FALSE;
                
                } else {
                    $first_name = test_input($_POST["first_name"]);
                    /*if (!preg_match("/^[a-zA-Z-' ]*$/",$first_name)) {
                        $first_nameErr = "Apenas letras e espaços são aceitos";
                        $ok=FALSE;
                    }  */
                }

                if (empty($_POST["last_name"])) {
                    $last_nameErr = "Informe um sobrenome válido";
                    $ok=FALSE;
                } else {
                    $last_name = test_input($_POST["last_name"]);
                    /* if (!preg_match("/^[a-zA-Z-' ]*$/",$last_name)) {
                        $last_nameErr = "Apenas letras e espaços são aceitos";
                        $ok=FALSE;
                    }  */
                }

                if (empty($_POST["email"]) && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                    $emailErr = "Informe um endereço de E-mail válido";
                    $ok=FALSE;
                } else {
                    $email = test_input($_POST["email"]);
                    if ($email !="") {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL) && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                            $emailErr = "O indereço de e-mail informado é inválido";
                            $ok=FALSE;
                        }
                        if (usuario_ja_existe_email($email) && !$editando){
                            $emailErr = " - Já existe um usuário cadastrado com este e-mail";
                            $ok=FALSE;
                        }
                    }
                }
                
                if (empty($_POST["id_tipo_documento"]) && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                    $id_tipo_documentoErr = "Informe uma tipo de documento";
                    $ok=FALSE;
                } else {
                    $id_tipo_documento = test_input($_POST["id_tipo_documento"]);
                    if ($id_tipo_documento == '') {
                        $id_tipo_documentoErr = " - O tipo de documento informado é inválido";
                        $ok=FALSE; 
                    }
                }
                
                if (empty($_POST["numero_documento"]) && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                    $numero_documentoErr = "Informe um número de documento";
                    $ok=FALSE;
                } else {
                    $numero_documento = test_input($_POST["numero_documento"]);
                    if ($_POST["id_tipo_documento"] == '1') { #cpf
                        if (verifica_CPF($numero_documento) == FALSE && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                            $numero_documentoErr = " - Digite um cpf válido";
                            $ok=FALSE;
                        }
                        else {
                            $numero_documento = preg_replace( '/[^0-9]/is', '', $numero_documento );
                        }
                    }
                    /*else {
                        if (!preg_match("[0-9]", $numero_documento)) {
                            $numero_documentoErr = " - O número de documento informado é inválido";
                            $ok=FALSE;
                        }
                    }*/
                    if ($email !="" && usuario_ja_existe_numero_documento($numero_documento) && !$editando){
                        $numero_documentoErr = " - Já existe um usuário cadastrado com este documento";
                        $ok=FALSE;
                    }
                }
                if (empty($_POST["id_nacionalidade"]) && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                    $id_nacionalidadeErr = "Informe uma nacionalidade válida";
                    $ok=FALSE;
                } else {
                    if (!isset($_POST['id_nacionalidade'])) { 
                        $id_nacionalidade = 27;
                    } else {
                    $id_nacionalidade = $_POST["id_nacionalidade"];
                    }
                }
                if (empty($_POST["id_estud_faosc"]) && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                    $id_estud_faoscErr = "Informe um tipo de estudante FAOSC válido";
                    $ok=FALSE;
                } else {
                    if (isset($_POST["id_estud_faosc"])) {
                        $id_estud_faosc = $_POST["id_estud_faosc"];
                    } else {
                        $id_estud_faosc = 'NULL';
                    }
                    if ($id_estud_faosc == 'Not') {
                        $id_estud_faosc = 'NULL';
                    }
                }
                
                if (empty($_POST["password"]) && !$editando) {
                    $password_Err = "Informe uma senha";
                    $ok=FALSE;
                } else {
                    if (!$editando){
                        $senha_cripto = md5($_POST["password"]);   
                    }
                }          
                
                if (empty($_POST["numero"])  && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                    $numeroErr = "Informe um número válido";
                    $ok=FALSE;
                } else {
                    $numero= $_POST["numero"];
                } 

                if (empty($_POST["logradouro"])  && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                    $logradouroErr = "Informe um logradouro válido";
                    $ok=FALSE;
                } else {
                    $logradouro = test_input($_POST["logradouro"]);
                    if ($logradouro == '' && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                        $logradouroErr = "Logradouro Inválido";
                        $ok=FALSE;
                    }  
                }

                if (empty($_POST["bairro"])  && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                    $bairroErr = " Informe um bairro válido";
                    $ok=FALSE;
                } else {
                    $bairro = $_POST["bairro"];
                    if ($bairro == '' && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                        $bairroErr = "Bairro Inválido";
                        $ok=FALSE;
                    }  
                }
                
                if (empty($_POST["cep"])  && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                    $cepErr = "Informe um código postal válido";
                    $ok=FALSE;
                } else {
                    $cep= $_POST["cep"];
                    if (!preg_match("/^[0-9]+$/",$cep) && ($_SESSION['admin']==FALSE || $_SESSION['credenciador']==FALSE)) {
                        $cepErr = "O código postal informado é inválido";
                        $ok=FALSE;
                    }
                } 

                if (isset($_POST["eh_credenciador"])) { $eh_credenciador = 1; } else { $eh_credenciador = 0; }
                
                $id_pais = $_POST["id_pais"];
                if (isset($_POST["cod_cidades"]) && $_POST["cod_cidades"] !== ''){ 
                    $id_municipio = $_POST["cod_cidades"]; 
                } else {
                    $id_municipio = 1; 
                }

                $complemento = $_POST["complemento"];

                if (isset($_POST["id_uf"])){ 
                    $id_uf = $_POST["id_uf"];
                } else {
                    $id_uf = 27;
                }
                if (isset($_POST["nacionalidade"])){ 
                    $id_nacionalidade = $_POST["nacionalidade"];
                }
                else {
                    $id_nacionalidade = "27";
                }

                $user_last_id = "";

                

                 #se tudo está ok, insere no db:
                if ($ok == TRUE){
                    
                    if ($editando){
                        $sql_update = "UPDATE usuario
                                        SET
                                            id_nacionalidade = $id_nacionalidade, 
                                            id_tipo_aluno_faosc = $id_estud_faosc, 
                                            nome = '$first_name', 
                                            sobrenome = '$last_name',  
                                            email = '$email', 
                                            id_tipo_documento = $id_tipo_documento, 
                                            numero_documento = $numero_documento,
                                            credenciador = $eh_credenciador
                                        WHERE id_usuario=".$_GET['id'];
                        $result_update = $conn->query($sql_update);
                        if ($result_update == true) {
                            if ($_SESSION['admin']==true){
                                
                                header("Location: ./adm/users.php?msg=cadastro alterado com sucesso");

                            } else {
                                header("Location: ./painel.php?msg=cadastro alterado com sucesso");
                            }
                            exit();
                        }
                    } else {
                        
                        //$sql_check = "SELECT id_usuario FROM usuario WHERE email = '$email' OR numero_documento = '$numero_documento'";
                        //$result_check = $conn->query($sql_check);
                        //if ($result_check->num_rows > 0) {
                            //check para ver se usuario ja existe
                          //  $row = $result_check->fetch_assoc();
                            //$user_last_id = $row["id_usuario"]; //salva id do usuario
                        //} else {
                            //se n, insere na db
                            $sql_insert = "INSERT INTO usuario (
                                            id_nacionalidade, 
                                            id_tipo_aluno_faosc, 
                                            nome, 
                                            sobrenome, 
                                            id_tipo_perfil_acesso, 
                                            email, 
                                            id_tipo_documento, 
                                            numero_documento, 
                                            senha, 
                                            credenciador) 
                                        VALUES (
                                            $id_nacionalidade, 
                                            $id_estud_faosc, 
                                            '$first_name', 
                                            '$last_name', 
                                            1,
                                            '$email', 
                                            $id_tipo_documento, 
                                            '$numero_documento', 
                                            '$senha_cripto', 
                                            '$eh_credenciador')";
                            $result_insert = $conn->query($sql_insert);
                            
                            if ($result_insert == true) {
                                $user_last_id = $conn->insert_id;
                                $end_last_id = "";
                                if ($cep == '') {$cep = 0;}
                                $sql_insert = "INSERT INTO endereco ( 
                                            id_uf, 
                                            id_pais, 
                                            id_municipio, 
                                            logradouro, 
                                            numero, 
                                            bairro, 
                                            complemento, 
                                            cep) 
                                            VALUES (
                                                $id_uf, 
                                                $id_pais, 
                                                $id_municipio, 
                                                '$logradouro', 
                                                '$numero', 
                                                '$bairro', 
                                                '$complemento', 
                                                $cep)";
                                $result_insert = $conn->query($sql_insert);
                               
                                if ($result_insert == true) {
                                    $end_last_id = $conn->insert_id;                                                            
                                    $sql = "INSERT INTO usuario_endereco (id_usuario, id_endereco) 
                                            VALUES ('$user_last_id', '$end_last_id');";
                                    $result_anex_addr = $conn->query($sql);
                                    if ($result_anex_addr == true) {
                                        if ($_SESSION['admin']==TRUE || $_SESSION['credenciador']==TRUE) {
                                            if (isset($_GET['voltar'])){
                                                header("Location: ".$_GET['voltar']."&msg=cadastro realizado com sucesso");
                                            } else {
                                                header("Location: ./adm/users.php?msg=usuario cadastrado com sucesso");
                                            }                                            
                                            exit();
                                        } else {
                                            $last_id = $user_last_id;
                                            $_SESSION['user_name'] = $row['id_usuario'];
                                            $_SESSION['name'] = $row['nome'];
                                            $_SESSION['sobrenome'] = $row['sobrenome'];
                                            $_SESSION['id'] = $row['id_usuario'];
                                            $_SESSION['admin'] = $row['admin'];
                                            $_SESSION['credenciador'] = $row['credenciador'];
                                            $_SESSION['palestrante'] = $row['palestrante'];
                                            $_SESSION['path'] = $_SERVER['DOCUMENT_ROOT']."/";
                                            if (isset($_GET['voltar'])){
                                                header("Location: ".$_GET['voltar']."?msg=cadastro realizado com sucesso");
                                            } else {
                                                header("Location: ./painel.php?msg=cadastro realizado com sucesso");
                                            }
                                            exit();
                                        }
                                    } else {
                                        print('erro ao cadastrar, contate o administrador 1');
                                    }
                                } else {
                                    print('erro ao cadastrar, contate o administrador 2'.$sql_insert);
                                }
                            } else {
                                print('erro ao cadastrar, contate o administrador 3');
                            }
                                 
                    }
                }
            }
        ?>


<head>
<body>
    <footer class="container col-12">
            <div class="container row" >
                <div class="event-logo col-2">
                    <a href="index.php">
                        <img src="img/logofaosc.png" class="img-responsive img-fluid">
                    </a>
                </div>

                <div class="col-12">
                    
                </div>
            </div>        
        </footer>    
        <title>Cadastro de Usuário - EVENTFAOSC</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <base href="https://eventos.faosc.edu.br/">
        <style>
            .error {color: #FF0000;}
           
            .event{
                text-align: center;
                font-size: 18px; 
                font-family: sans-serif;
            }
        </style>
    </head>

    <header class="container col-12">
        <!--<div class="container row" >
            <div class="event-logo col-2">
                <a href="index.php">
                    <img src="img/logofaosc.png" class="img-responsive img-fluid">
                </a>
            </div>
        </div> -->

        
    </header>

        <main class="container">
            <?php 
                if ($editando){ ?>
                    <h2 class="event">Visualização ou Alteração de Cadastro</h2>
                    
                    <form class="container col-8" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?id=<?php echo $_GET['id'] ?>">
            <?php 
                } else { ?>
                    <h2 class="event">Cadastro de Usuário</h2>
                    
                    <form class="container col-8" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?><?php if (isset($_GET['voltar'])){echo "?voltar=".$_GET['voltar'];} ?>">
            <?php 
                } ?>

            <div class="form-outline mb-4">
                <p><span class="error">* Campos obrigatórios</span></p>
                
                <?php 
                /*
                    if (isset($_SESSION['admin']) == TRUE){
                    if ($_SESSION['admin'] !== true) {
                ?>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form2Example1">Tipo de Usuário: </label>
                            <select id="id_tipo_perfil_acesso" name="id_tipo_perfil_acesso" class="form-select" aria-label="Default select example" onchange="id_tipoU()">
                                    <?php 
                                        $sql = $conn->query("SELECT * FROM tipo_perfil_acesso");
                                        while ($row = $sql->fetch_assoc()){
                                        echo "<option value=\"". $row['id_tipo_perfil_acesso'] ."\">" . $row['descricao_tipo_perfil_acesso'] . "</option>";
                                    }
                                ?>
                                <script>
                                    window.addEventListener('DOMContentLoaded', (e) => {
                                        var opt = document.getElementById("id_tipo_perfil_acesso");
                                        opt.value = '1'
                                        opt.selected = true;
                                    });
                                    function id_tipoU() {
                                        var opt = document.getElementById("id_tipo_perfil_acesso");
                                        var selecValue = opt.value;
                                        //console.log(selecValue);
                                    }
                                </script>    
                            </select>            
                            </div>
                            <?php
                    }}
                */
                ?>
                

                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example1">Nome: <span class="error">* <?php echo $first_nameErr;?></span> </label>
                    <input type="text" name="first_name" value="<?php echo htmlentities( $first_name ) ; ?>" id="form2Example1" class="form-control" placeholder="Nome"/>                                    
                </div> 

                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example1">Sobrenome: <span class="error">* <?php echo $last_nameErr;?></span></label>
                    <input type="text" name="last_name" value="<?php echo htmlentities( $last_name ) ; ?>" id="form2Example1" class="form-control" placeholder="Sobrenome"/>                                       
                </div>

                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example1">E-mail: <span class="error">* <?php echo $emailErr;?></span></label>
                    <input type="email" name="email" value="<?php echo htmlentities( $email ) ; ?>" id="form2Example1" class="form-control" placeholder="email@email.com"/>
                </div>

                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example1">Tipo Documento: <span class="error">* </span></label>
                    <select id = "id_tipo_documento" name="id_tipo_documento" class="form-select" aria-label="Default select example">
                    <?php 
                        $sql = $conn->query("SELECT * FROM tipo_documento");
                        while ($row = $sql->fetch_assoc()){
                            echo "<option value=\"". $row['id_tipo_documento'] ."\">" . $row['descricao'] . "</option>";
                        }
                    ?> 
                    </select>
                </div>
                    </script>    
                </select>
                </div>

                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example1">Número do Documento: <span class="error">* somente números<?php echo $numero_documentoErr;?></span></label>
                    <input type="text" name="numero_documento" value="<?php echo htmlentities( $numero_documento ) ; ?>" id="form2Example1" class="form-control" placeholder="999999999999"/>
                </div>

                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example1">Nacionalidade: <span class="error">* <?php echo $id_nacionalidadeErr;?></span> </label>
                    <select value="<?php echo htmlentities( $id_nacionalidade ) ; ?>" id ="id_nacionalidade" name="id_nacionalidade" class="form-select" aria-label="Default select example" onchange="userNasciona()">
                        <?php 
                            $sql = $conn->query("SELECT * FROM nacionalidade");
                            while ($row = $sql->fetch_assoc()){
                                echo "<option value=\"". $row['id_nacionalidade'] ."\">" . $row['nacionalidade'] . "</option>";
                            }
                        ?>
                        <script>
                            window.addEventListener('DOMContentLoaded', (e) => {
                                var opt = document.getElementById("id_nacionalidade");
                                opt.value = '27'
                                opt.selected = true;
                            });
                            function userNasciona() {
                                var selectElement = event.target;
                                var value = selectElement.value;
                                console.log('usarNasciona');
                            }
                    </select>                    
                </div>

                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example1">Já é estudante FAOSC? <span class="error">* <?php echo $id_estud_faoscErr;?> </label>
                    <select value="<?php echo htmlentities( $id_estud_faosc ) ; ?>"id="id_estud_faosc" name="id_estud_faosc" class="form-select" aria-label="Default select example" onchange="userFaosc()">
                        <?php 
                            $sql = $conn->query("SELECT * FROM aluno_faosc");
                                echo "<option value='Not'>Não</option>";
                            while ($row = $sql->fetch_assoc()){
                                echo "<option value=\"". $row['id_tipo_aluno_faosc'] ."\">" . $row['descricao_tipo_aluno_faosc'] . "</option>";
                            }
                        ?>
                        <script>
                            window.addEventListener('DOMContentLoaded', (e) => {
                                var opt = document.getElementById("id_estud_faosc");
                                opt.value = 'Não';
                                opt.selected = true;
                            });
                        </script>
                    </select>
                </div>

            <div name ="address" class="form-outline mb-4">
                <br><h5 class="form-label" for="form2Example1">Endereço:</h5><br>
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
                                    /*document.getElementById("ifCountry_others").hidden = true;
                                    document.getElementById("failedCountryCheck").hidden = false;
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
                                    var opt = document.getElementById("id_pais");
                                    opt.value = '27'
                                    opt.selected = true;
                                    var opt2 = documend.getElementById("id_uf");
                                    opt2 = '24';
                                    opt2.selected = true;
                                });
                                function selectChange() {
                                    var selectElement = event.target;
                                    var value = selectElement.value;
                                    if (value == '27') {
                                        document.getElementById("ifCountry_27").hidden = false;
                                        /*document.getElementById("ifCountry_others").hidden = true;
                                        document.getElementById("failedCountryCheck").hidden = false;
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
                                        './get_cidades.php?search=',
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
                                        var opt3 = document.getElementById("cod_cidades");
                                        opt3.value = '<?php if ($id_municipio != "") {echo htmlentities( $id_municipio );} else {echo '';}?>'
                                        opt3.selected = true;
                                        var evt = document.createEvent("HTMLEvents");
                                            evt.initEvent("change", false, true);
                                            opt3.dispatchEvent(evt);  
                                        });
                                        
                                    } else {
                                        
                                        $('#cod_cidades').html('<option value="">-- Escolha um estado --</option>');
                                    }
                                });
                                });
                                
                        </script>    

                        <script>
                            window.addEventListener('DOMContentLoaded', (e) => {

                                    var opt1 = document.getElementById("id_tipo_documento");
                                        opt1.value = '<?php echo htmlentities( $id_tipo_documento );?>'
                                        opt1.selected = true;            
                                        
                                        var opt2 = document.getElementById("id_uf");
                                        opt2.value = '<?php echo htmlentities( $id_uf);?>'
                                        opt2.selected = true;
                                    
                                        var evt = document.createEvent("HTMLEvents");
                                            evt.initEvent("change", false, true);
                                            opt2.dispatchEvent(evt);   
                                    
                                    var opt3 = document.getElementById("cod_cidades");
                                        opt3.value = '<?php if ($id_municipio != "") {echo htmlentities( $id_municipio );} else {echo '';}?>'
                                        opt3.selected = true;
                                        var evt = document.createEvent("HTMLEvents");
                                            evt.initEvent("change", false, true);
                                            opt3.dispatchEvent(evt);  
                                    
                                                        
                                    var opt4 = document.getElementById("id_nacionalidade");
                                        opt4.value = '<?php echo htmlentities( $id_nacionalidade );?>'
                                        opt4.selected = true;
                                        var evt = document.createEvent("HTMLEvents");
                                            evt.initEvent("change", false, true);
                                            opt4.dispatchEvent(evt);  
                            
                                    var opt5 = document.getElementById("id_estud_faosc");
                                        opt5.value = '<?php if ($id_estud_faosc != "") {echo htmlentities( $id_estud_faosc );} else {echo 'Not';}?>'
                                        opt5.selected = true;
                                        var evt = document.createEvent("HTMLEvents");
                                            evt.initEvent("change", false, true);
                                            opt5.dispatchEvent(evt);  
                            });
                                
                                function userFaosc() {
                                    var selectElement = event.target;
                                    var value = selectElement.value;
                                    
                                }
                                function userNasciona() {
                                    var selectElement = event.target;
                                    var userNasciona = selectElement.value;
                                    
                                }

                        </script>

                        </select>             
                    </div>
                </div>  <!-- end div case country is 27-->

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Logradouro:<span class="error">* <?php echo $logradouroErr;?></span></label>
                        <input type="text" name="logradouro" value="<?php echo htmlentities( $logradouro ) ; ?>" id="form2Example1" class="form-control" placeholder="rua"/>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Número:<span class="error">* <?php echo $numeroErr;?></span></label>
                        <input type="text" name="numero" value="<?php echo htmlentities( $numero ) ; ?>" id="form2Example1" class="form-control" placeholder="número"/>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Complemento:<span class="error"> <?php echo $complementoErr;?></span></label>
                        <input type="text" name="complemento" value="<?php echo htmlentities( $complemento ) ; ?>" id="form2Example1" class="form-control" placeholder="complemento"/>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Bairro:<span class="error">* <?php echo $bairroErr;?></span></label>
                        <input type="text"  name="bairro" value="<?php echo htmlentities( $bairro ) ; ?>" id="form2Example1" class="form-control" placeholder="bairro"/>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Código Postal:<span class="error">* somente números <?php echo $cepErr;?></span></label>
                        <input type="text" name="cep" value="<?php echo htmlentities( $cep ) ; ?>" id="form2Example1" class="form-control" placeholder="89887000"/>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Foto: <span class="error"></label>
                        <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg"  class="form-control"/>
                    </div>
                <?php
                    if (isset($_SESSION['admin']) && $_SESSION['admin']) { 
                        if ($eh_credenciador){?>
                            <div>
                                <input type="checkbox" id="eh_credenciador" name="eh_credenciador" checked>
                                <label for="eh_credenciador">Credenciador</label>
                            </div>
                <?php
                        } else { ?>
                            <div>
                                <input type="checkbox" id="eh_credenciador" name="eh_credenciador">
                                <label for="eh_credenciador">Credenciador</label>
                            </div>
                <?php
                        }
                    } ?>
                    
            </div>      <!-- end div address-->  
            
            <?php 
            
                if ($editando === FALSE) { ?>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Senha:<span class="error">* <?php echo $passwordErr;?></span></label>
                        <input type="password"name="password" id="form2Example1" class="form-control" placeholder="999999999999"/>
                    </div>

                    <input id="submit" type="submit" class="btn btn-primary btn-block mb-4;" style="margin-left: 35%;" name="submit" value="Cadastrar">  
            <?php
                } else { ?>

                    <input id="submit" type="submit" class="btn btn-primary btn-block mb-4; ms-200;" name="submit" value="Gravar Alterações">  
            <?php 
                } ?>    
            <a class="btn btn-secondary btn-block mb-4" href="javascript:javascript:history.go(-1)">Retornar</a>  
            <br><br>
        </form>

        <?php 
            if ($editando){ ?>
                <a class="btn btn-outline-secondary" href="alterar_senha.php?id=<?php echo $_GET['id'];?>">Alterar senha</a>
        <?php    
            } else { ?>
                <div class="text-center">
                    <p>Já é cadastrado? <a class="btn btn-outline-secondary" href="login.php">Faça login aqui:</a></p>
                </div>     
        <?php           
            } ?>
            
        </main>

        <footer class="container" style="font-size:8px;">
            <?php
                include('template/footer.html');
            ?>
        </footer>
        
    </body>

</html>