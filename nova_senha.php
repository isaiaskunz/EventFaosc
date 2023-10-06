<?php 
    session_start(); 
    include('db.php');
    $error = "";
    $enviado ="";
    
        if (isset($_GET['email']) && isset($_GET['hash'])) {
            $email_recuperacao = $_GET['email'];
            $hash_recuperacao = $_GET['hash'];
            $sql = "SELECT * FROM usuario WHERE (email='$email_recuperacao' AND hash_recuperacao_senha='$hash_recuperacao')";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) === 1) {
                echo "";
            }
            else {
                $error = "Link Invalido";
                header("Location: nova_senha.php?error=invalido");
                exit();
            }
        }
    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($email_recuperacao) && isset($hash_recuperacao)) {
            $senha_nova = $_POST['senha_nova'];
            if (empty($senha_nova)) {
                $error= "Informe uma NOVA senha";
            } 
            else {
                $sql = "UPDATE usuario SET senha ='".md5($senha_nova)."', hash_recuperacao_senha=NULL WHERE email = '".$email_recuperacao."';";
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    $enviado = $email_recuperacao;
                } else {
                    $error = "Ocorreu um erro ao registrar a nova senha. Contate o Administrador";
                } 
            }
        }
    }
    
    ?>

<!DOCTYPE html>
<html>
    <head>
        <title>Recuperação de Senha</title>
        
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
            }

        </style>
    </head>

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
        
        <main class="container">
            <h2 class="event">Recuperação de Senha</h2>
            <?php 
                if (isset($_GET['error'])) { ?>
                    <h3> Este link de recuperação de senha é inválido. Contate o Administrador </h3>
                    <a class="btn btn-secondary btn-block mb-4" href="./index.php">Voltar para a página inicial</a> 
            <?php        
                }
                else {
                if ($enviado == "") { 
                    if (isset($email_recuperacao) && isset($hash_recuperacao)) { ?>
                        <form action="./nova_senha.php?email=<?php echo $email_recuperacao?>&hash=<?php echo $hash_recuperacao?>" method="post" class="container col-8">
            <?php   } else { ?>
                        <form action="./nova_senha.php" method="post" class="container col-8">
            <?php   } ?>
                    <div class="form-outline mb-4">
                        <input type="text" id="form2Example1" name="senha_nova"  class="form-control" placeholder=""/>
                        <label class="form-label" for="form2Example1">Informe uma NOVA senha</label>
                        <span class="error">* <?php echo $error;?></span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mb-4" >Enviar</button>      
                    <a class="btn btn-secondary btn-block mb-4" href="javascript:javascript:history.go(-1)">Retornar</a>                  
                </form>
            <?php
                }
                else { ?>
                    <br>
                    <h4><?php echo "A nova senha foi registrada para o usuário de e-mail ".$enviado ?></h4>
                    <h5>Clique no botão abaixo para entrar</h5>
                    <br>
                    <a class="btn btn-secondary btn-block mb-4" href="./login.php">Entrar</a>
            <?php
                }}?>    
        </main>

        <footer class="container">
            <?php
                include('template/footer.html');
            ?>
        </footer>
            
    </body>
</html> 
