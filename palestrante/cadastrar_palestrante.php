<?php
    session_start();
    if ($_SESSION['admin'] != TRUE){
        echo "você não tem permissão para acessar este recurso";
        exit();
    }

?>

<?php 
    include "../db.php";
    $sql = "SELECT * FROM usuario as u 
            INNER JOIN tipo_perfil_acesso as t 
            ON u.id_tipo_perfil_acesso=t.id_tipo_perfil_acesso ";
            $result = $conn->query($sql);
?>

<!DOCTYPE HTML>  
<html>
    <head>
        <title>Registrar Palestrante - EVENTFAOSC</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
     <base href="http://localhost/eventfaosc/">



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
                        .event{
                text-align: center;
                vertical-align: middle;
            }

            ul {
              list-style-type: none;
              margin: 0;
              padding: 0;
              overflow: hidden;
              /*background-color: #333;*/
            }

            .home{
              float: left;
            }

            li{
                float: right;
            }

            li a {
              display: block;
              color: black;
              text-align: right;
              padding: 14px 16px;
              text-decoration: none;
            }

            li texto {
              display: block;
              color: black;
              text-align: right;
              padding: 14px 16px;
              text-decoration: none;
            }

            li a:hover {
              /*background-color: #111;*/
              background-color: #A9E2F3;
            }

            nav{
                margin-top: 2%;
                margin-bottom: 2%;
            }

            * {box-sizing: border-box}
            body {font-family: Verdana, sans-serif; margin:0}
            .mySlides {display: none}
            img {vertical-align: middle;}

            /* Slideshow container */
            .slideshow-container {
              max-width: 100%;
              position: relative;
              margin: auto;
            }
        </style>
    </head>



    <body>  

 <header class="container" style="margin-top: 1%; font-size: 15px; font-family: sans-serif;">
            <div class="container row" >
                <div class="event-logo col-2">
                    <a href="../index.php">
                        <img src="./img/logofaosc.png" class="img-responsive img-fluid" alt="logo Faosc">
                    </a>
                </div>
                <div class="event col-10">
                    <h3> Event Faosc </h3>
                </div>
            </div>
            <div class="nav row">
                <nav class="col-12 navbar-light bg-light sticky-top" style="margin-top: 2%;">
                        <ul>
                            <li class="home">
                                <a  href="../index.php"><span class="glyphicon glyphicon-home"></span> Home </a>
                            </li>
                            <?php 
                                if (isset($_SESSION['id']) && isset($_SESSION['user_name'])){ ?>
                                    <li>
                                        <a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Sair</a>
                                    </li>
                                    <li>
                                        <a href="#"><span class="glyphicon glyphicon-user"></span> Perfil</a>
                                    </li>
                                    <li>
                                        <a  href="painel.php"><span class="glyphicon glyphicon-cog"></span> Painel</a>
                                    </li>
                                    <li>
                                        <texto>
                                            <?php echo "Olá ".$_SESSION['name']." ".$_SESSION['sobrenome']; }?>
                                            
                                        </texto>
                                    </li>
                        </ul>
                </nav>
            </div>
        </header>
        <?php
            $first_nameErr = $last_nameErr = $emailErr = $genderErr = $passwordErr = $titulacaoErr = $lattesErr = "";
            $first_name = $last_name = $email = $gender = $comment = $website = $password = $titulacao = $lattes = $numero_documento = $telefone = "";
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
                if (empty($_POST["titulacao"])) {
                   $titulacaoErr = "Informe uma titulação";
                    $ok=FALSE;
                } else {
                    $titulacao = test_input($_POST["titulacao"]);
                }
                if (empty($_POST["lattes"])) {
                    $lattesErr = "Informe um endereço de currículo lattes";
                    $ok=FALSE;
                } else {
                    $lattes = test_input($_POST["lattes"]);
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
                                email, 
                                senha,
				                numero_documento,
				                id_titulacao,
				                lattes,
                                admin,
                                credenciador,
                                palestrante) 
                            VALUES (
                                '$first_name',
                                '$last_name',
                                '$email',
                                '$senha_cripto',
                                '$numero_documento',
                                '$titulacao',
                                '$lattes',
                                '0',
                                '0'.
                                '1')";
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
        <main class="container">
            <h2 class="event">Cadastrar Palestrante</h2>
            <h2></h2>
            <p><span class="error">* Campos obrigatórios</span></p>
            <form class="container col-8" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            
            <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Foto:<span class="error"></label>
                        <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg"  class="form-control"/>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Nome:<span class="error">* <?php echo $first_nameErr;?></span> </label>
                        <input type="text" name="first_name" value="<?php echo htmlentities( $first_name ) ; ?>" id="form2Example1" class="form-control" placeholder="Nome"/>                                    
                    </div> 

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Sobrenome:<span class="error">* <?php echo $last_nameErr;?></span></label>
                        <input type="text" name="last_name" value="<?php echo htmlentities( $last_name ) ; ?>" id="form2Example1" class="form-control" placeholder="Sobrenome"/>                                       
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">E-mail:<span class="error">* <?php echo $emailErr;?></span></label>
                        <input type="email" name="email" value="<?php echo htmlentities( $email ) ; ?>" id="form2Example1" class="form-control" placeholder="email@email.com"/>
                    </div>
                
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Senha:<span class="error">* <?php echo $passwordErr;?></span></label>
                        <input type="password"name="password" id="form2Example1" class="form-control" placeholder="999999999999"/>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Titulação:<span class="error">* <?php/* echo $titulacaoErr;*/?></span></label> 
                        <input type="text"name="titulacao" id="form2Example1" class="form-control" placeholder=""/> <!-- oq eu boto aq?? ^ n temos verificacao pra isso ainda -->
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Telefone:<span class="error">* <?php/* echo $titulacaoErr;*/?></span></label> 
                        <input type="text"name="telefone" value="<?php echo htmlentities( $telefone ) ; ?>" pattern="[0-9]{3} [0-9]{3} [0-9]{4}" id="form2Example1" class="form-control" placeholder="49 99988887777"/> <!-- oq eu boto aq?? ^ n temos verificacao pra isso ainda -->
                    </div>

                    <div>
                        <label class="form-label" for="form2Example1">Lattes:</label>
                        <input type="url" id="lattes" name="lattes" value="<?php echo htmlentities( $lattes ) ; ?>" class="form-control">
                    </div>

                    <div>
                        <label class="form-label" for="form2Example1">Endereço Completo:</label>
                        <br>
                        <textarea name="endereco" id="endereco" cols="50" rows="5" value="<?php/* echo htmlentities( $descricao_longa) ; */?>"></textarea>
                        <span class="error"> <?php/* echo $descricao_longaErr;*/?></span>
                    </div>

                    <br>
                    <input id="submit" type="submit" class="btn btn-primary" name="submit" value="Cadastrar">  
                    <a class="btn btn-secondary" href="javascript:javascript:history.go(-1)">Retornar</a>  
                    <br><br>
        </form>
        <div class="text-center">
             

        </main>

        <footer class="container" style="font-size:8px;">
            <?php
                include('../template/footer.html');
            ?>
        </footer>
    </body>
</html>