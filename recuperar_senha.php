<?php 
    session_start(); 
    include('db.php');
    $error = "";
    $enviado ="";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['email_recuperacao'])) {
            function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
            }
            $email_recuperacao = validate($_POST['email_recuperacao']);
            if (empty($email_recuperacao)) {
                $error= "Informe um e-mail ou numero do documento";
            } 
            else{
                    $sql = "SELECT * FROM usuario WHERE (email='$email_recuperacao' OR numero_documento='$email_recuperacao')";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) === 1) {
                        $row = mysqli_fetch_assoc($result);
                        include "./tools.php";
                        $hash_rec = generateRandomString(25);
                        if (($row['email'] === $email_recuperacao || $row['numero_documento'] === $email_recuperacao)) {
                            $sql = "UPDATE usuario SET hash_recuperacao_senha ='".$hash_rec."' WHERE id_usuario = ".$row['id_usuario'].";";
                            $result = $conn->query($sql);
                            if ($result == TRUE) {
                                $msg_email = "<br> Acesse o link abaixo para recuperar sua senha: <br><br>"
                                ."<a href='https://eventos.faosc.edu.br/nova_senha.php?email=".$email_recuperacao."&hash=".$hash_rec
                                ."'>https://eventos.faosc.edu.br/nova_senha.php?email=".$email_recuperacao."&hash=".$hash_rec."</a>"  
                                ."<br><br> Se você não solicitou a recuperação da senha, favor desconsiderar este e-mail";
                                mail($email_recuperacao,"Eventos FAOSC - Recuperação de Senha", $msg_email);
                                echo $msg_email;
                                $enviado = $email_recuperacao;
                            }else{
                                $error = "Ocorreu um erro ao recuperar a senha. Contate o Administrador";
                            } 
                        }else{
                            $error = "Ocorreu um erro ao recuperar a senha. Contate o Administrador";
                        }
                    }else{
                        $error = "Email ou numero de documento não pertence a um usuário registrado";
                    }
                }
            }else{
                $error= "Informe um e-mail ou numero do documento";
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
            <h2 class="event">Recuperar Senha</h2>
            <?php 
                if ($enviado == "") { ?>

                <form action="./recuperar_senha.php" method="post" class="container col-8">
                    <div class="form-outline mb-4">
                        <input type="text" id="form2Example1" name="email_recuperacao"  class="form-control" placeholder=""/>
                        <label class="form-label" for="form2Example1">Email ou numero do documento</label>
                        <span class="error">* <?php echo $error;?></span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mb-4" >Recuperar Senha</button>      
                    <a class="btn btn-secondary btn-block mb-4" href="javascript:javascript:history.go(-1)">Retornar</a>                 
                </form>
            <?php
                }
                else { ?>
                    <br>
                    <h4><?php echo "Um email com as instruções de recuperação de senha foi enviado para ".$enviado ?></h4>
                    <h5>Se você não tiver mais acesso a este email, entre em contato com o Administrador</h5>
                    <br>
                    <a class="btn btn-secondary btn-block mb-4" href="./index.php">Retornar</a>
            <?php
                } ?>    
        </main>

        <footer class="container">
            <?php
                include('template/footer.html');
            ?>
        </footer>
            
    </body>
</html> 
