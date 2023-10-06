<!DOCTYPE html>
<html>
    <head>
        <title>PAINEL</title>
        <link rel="stylesheet" type="text/css" href="style.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <title>LOGIN</title>

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
        <?php
            include "./header.php";
            include "./db.php";

                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                } else {
                    header("Location: alterar_senha.php?error=informe o usuario");
                    exit();
                }
            
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (!$_SESSION['admin']===false && $id != $_SESSION['id']) {
                        header("Location: index.php");
                        exit();
                }
                function validate($data){
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = htmlspecialchars($data);
                    return $data;
                }
                $password_atual = validate($_POST['password_atual']);
                $password_novo = validate($_POST['password_novo']);
                if (empty($password_atual)) {
                     header("Location: alterar_senha.php?id=$id&error=Informe a senha atual");
                     exit();
                }else {
                    if(empty($password_novo)){
                         header("Location: alterar_senha.php?id=$id&error=Informe a nova senha");
                         exit();
                    }else{
                         $senha_cripto=md5($password_atual);
                         $sql = "SELECT id_usuario FROM usuario WHERE id_usuario=$id AND senha='$senha_cripto';";
                         $result = $conn->query($sql);
                         
                         if ($result == true) {
                            $senha_cripto=md5($password_novo);
                            $sql = "UPDATE usuario SET senha = '$senha_cripto' WHERE id_usuario ='$id'";
                            $result = $conn->query($sql);
                            if ($result == true) {
                                header("Location: painel.php?msg=senha alterada com sucesso");
                                exit();
                            } else {
                                header("Location: alterar_senha.php?id=$id&error=erro ao alterar a senha. contate o administrador");
                                exit();
                            }
                        }else{
                                header("Location: alterar_senha.php?id=$id&error=Senha atual incorreta");
                                exit();
                        }
                    }
                    
                }
            }
        ?>
 <main class="container">
            <h2 class="event">Alterar senha</h2>
            <form action="alterar_senha.php?id=<?php echo $id; ?>" method="post" class="container col-8">

                   <!-- Password input -->
                <div class="form-outline mb-4">
                    <input type="password" id="form2Example2" class="form-control" name="password_atual" placeholder="Password" />
                    <label class="form-label" for="form2Example2">Senha atual</label>
                </div>
                   <!-- Password input -->
                <div class="form-outline mb-4">
                    <input type="password" id="form2Example2" class="form-control" name="password_novo" placeholder="Password" />
                    <label class="form-label" for="form2Example2">Nova Senha</label>
                </div>
                <?php if (isset($_GET['error'])) { ?>
                    <p class="error"><?php echo $_GET['error']; ?></p>
                <?php } ?>

                <button type="submit" class="btn btn-primary  mb-4" >Alterar</button>
                <a class="btn btn-secondary mb-4" href="javascript:javascript:history.go(-1)">Retornar</a>         
            </form>

        </main>
        

        <footer class="container">
            <?php
                include('template/footer.html');
            ?>
        </footer>
    </body>
</html>