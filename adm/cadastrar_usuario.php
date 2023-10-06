<?php
    session_start();
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
    <title>Cad Usuário ADM - EVENTFAOSC</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        <style>
            .error {color: #FF0000;}
            .container {
                margin-top: 3%; 
                font-size: 15px; 
                font-family: sans-serif;

            }
            .event{
                text-align: center;
                font-size: 18px; 
                font-family: sans-serif;
            }
        </style>
    </head>
    <header class="container col-12">
        <div class="container row" >
            <div class="event-logo col-2">
                <a href="index.php">
                    <img src="../img/logofaosc.png" class="img-responsive img-fluid">
                </a>
            </div>
        </div>  
    </header>


    <body>  
        <?php
            $first_nameErr = $last_nameErr = $emailErr = $passwordErr = "";
            $first_name = $last_name = $email = $password = "";
            $ok = TRUE;
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($_POST["first_name"])) {
                    $first_nameErr="Informe um nome válido";
                    $ok=FALSE;
                } else {
                    $first_name = test_input($_POST["first_name"]);
                    if (!preg_match("/^[a-zA-Z-' ]*$/",$first_name)) {
                        $first_nameErr="Apenas letras e espaços são aceitos";
                        $ok=FALSE;
                    }  
                }
                if (empty($_POST["last_name"])) {
                    $last_nameErr="Informe um sobrenome válido";
                    $ok=FALSE;
                } else {
                    $last_name = test_input($_POST["last_name"]);
                    if (!preg_match("/^[a-zA-Z-' ]*$/",$last_name)) {
                        $nameErr="Apenas letras e espaços são aceitos";
                        $ok=FALSE;
                    }  
                }
                if (empty($_POST["email"])) {
                    $emailErr="Informe um endereço de E-mail válido";
                    $ok=FALSE;
                } else {
                    $email = test_input($_POST["email"]);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErr="O indereço de e-mail informado é inválido";
                        $ok=FALSE;
                    }
                }
                if (empty($_POST["password"])) {
                    $password_Err = "Informe uma senha";
                    $ok=FALSE;
                } else {
                    $senha_cripto = md5(test_input($_POST["password"]));
                    #if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
                       # $websiteErr = "Informe um website válido";
                       # $ok=FALSE;
                    #}    
                }
                if (empty($_POST["id_tipo_perfil_acesso"])) {
                    $emailErr="Informe um endereço de E-mail válido";
                    $ok=FALSE;
                }
                

                #se tudo está ok, insere no db:
                if ($ok == TRUE){
                    include "../db.php";
                    $sql = "INSERT INTO usuario (
                                nome, 
                                sobrenome, 
                                id_tipo_perfil_acesso,
                                email, 
                                senha) 
                            VALUES (
                                '$first_name',
                                '$last_name',
                                1,
                                '$email',
                                '$senha_cripto')";
                    $result = $conn->query($sql);
                    if ($result == TRUE) {
                        $last_id = $conn->insert_id;
                        $_SESSION['user_name'] = $row['email'];
                        $_SESSION['name'] = $row['nome'];
                        $_SESSION['id'] = $last_id;
                        $_SESSION['id_tipo_perfil_acesso'] = $row['id_tipo_perfil_acesso'];
                        header("Location: painel_adm.php");
                        exit();
                    }else{
                        echo "Erro ao cadastrar o usuário - fale com o administrador do sistema";
                    } 
                    $conn->close();  
                }
           }
        ?>

        <main class="container">

            <h2 class="event">CADASTRO</h2>
            <p><span class="error">* Campos obrigatórios</span></p>
            <form class="container col-8" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

                <div class="form-outline mb-4">
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
                                <label class="form-label" for="form2Example1">Senha: <span class="error">* <?php echo $passwordErr;?></span></label>
                                <input type="password"name="password" id="form2Example1" class="form-control" placeholder="999999999999"/>
                        </div>

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
                                    console.log(selecValue);
                                }
                            </script>    
                         </select>            
                        </div>
                        
                        <input id="submit" type="submit" class="btn btn-primary btn-block mb-4" name="submit" value="Cadastrar">  
                        <br><br>
                </div>
            </form>
        </main>
    </body>
</html>