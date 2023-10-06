<?php
    session_start();
     if ($_SESSION['credenciador'] != TRUE){
        echo "você não tem permissão para acessar este recurso";
        exit();
    }
    include('../tools.php');
?>

<!DOCTYPE HTML>  
<html>
    <head>
        <style>
            .error {color: #FF0000;}
        </style>
    </head>
    <body>  
        <?php
            $first_nameErr = $last_nameErr = $emailErr = $genderErr = $passwordErr =  "";
            $first_name = $last_name = $email = $gender = $comment = $website = $password =  $numero_documento = $telefone = "";
            $ok = TRUE;
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($_POST["first_name"])) {
                   $first_nameErr = "Informe um nome válido";
                   $ok=FALSE;
                } else {
                    $first_name = test_input($_POST["first_name"]);
                    if (!preg_match("/^[a-zA-Z-' ]*$/",$first_name)) {
                       $first_nameErr = "Apenas letras e espaços são aceitos";
                        $ok=FALSE;
                    }  
                }
                if (empty($_POST["last_name"])) {
                    $last_nameErr = "Informe um sobrenome válido";
                    $ok=FALSE;
                } else {
                    $last_name = test_input($_POST["last_name"]);
                    if (!preg_match("/^[a-zA-Z-' ]*$/",$last_name)) {
                        $nameErr = "Apenas letras e espaços são aceitos";
                       $ok=FALSE;
                    }  
                }
                if (empty($_POST["email"])) {
                    $emailErr = "Informe um endereço de E-mail válido";
                    $ok=FALSE;
                } else {
                    $email = test_input($_POST["email"]);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "O indereço de e-mail informado é inválido";
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
               
                #if (empty($_POST["comment"])) {
                #    $comment = "";
                #} else {
                #    $comment = test_input($_POST["comment"]);
                #}        
                #if (empty($_POST["gender"])) {
                #    $genderErr = "Please select a gender";
                #    $ok=FALSE;
                #} else {
                #    $gender = test_input($_POST["gender"]);
                #}

                #Exemplo de verificação
                #if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
                #    $websiteErr = "Informe um website válido";
                #    $ok=FALSE;
                #   }
                

                #se tudo está ok, insere no db:
                if ($ok == TRUE){
                    include "../db.php";
                    $sql = "INSERT INTO usuario (
                                nome, 
                                sobrenome, 
                                id_tipo_perfil_acesso,
                                email, 
                                senha,
				                numero_documento) 
                            VALUES (
                                '$first_name',
                                '$last_name',
                                3,
                                '$email',
                                '$senha_cripto',
                                '$numero_documento')";
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
                } else {
                    echo "Não entrou na inserção do banco de dados";
                }
           }
        ?>

        <h2>Cadastrar Credenciador</h2>
        <p><span class="error">* Campos obrigatórios</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
        <label for="foto">Foto:</label>
		<input type="file" id="foto" name="foto"><br><br>
        <label for="nome">Nome:</label>
        <input type="text" id="first_name" name="first_name"><br><br>
        
        <label for="sobrenome">Sobrenome:</label>
        <input type="text" id="last_name" name="last_name"><br><br>
        
        <label for="cpf">CPF:</label>
        <input type="text" id="numero_documento" name="numero_documento"><br><br>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email"><br><br>
        
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone"><br><br>
        
        <label for="senha">Senha:</label>
        <input type="password" id="password" name="password"><br><br>
            
            <input type="submit" name="submit" value="Cadastrar">  
        </form>
    </body>
</html>