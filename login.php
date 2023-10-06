<?php 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    

    include $_SESSION['path'].'db.php';     
    $_SESSION['path'] = $_SERVER['DOCUMENT_ROOT']."/";    
    $base_link = "https://eventos.faosc.edu.br/";
    if ($_SERVER['HTTP_HOST'] == "localhost"){
        $base_link = "http://localhost/eventfaosc/";
        $_SESSION['path'] .= "eventfaosc/";
    }

    if (isset($_POST['uname']) && isset($_POST['password'])) {
        function validate($data){
           $data = trim($data);
           $data = stripslashes($data);
           $data = htmlspecialchars($data);
           return $data;
        }
        $uname = validate($_POST['uname']);
        $pass = validate($_POST['password']);
        if (empty($uname)) {
            header("Location: login.php?error=Informe o nome de usuário");
            exit();
        }else if(empty($pass)){
                header("Location: login.php?error=Informe a senha");
                exit();
            }else{
                $senha_cripto=md5($pass);
                $sql = "SELECT * FROM usuario WHERE (email='$uname' OR numero_documento='$uname') AND senha='$senha_cripto'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) === 1) {
                    $row = mysqli_fetch_assoc($result);
                    if (($row['email'] === $uname || $row['numero_documento'] === $uname) && $row['senha'] === $senha_cripto) {
                        $_SESSION['user_name'] = $row['id_usuario'];
                        $_SESSION['name'] = $row['nome'];
                        $_SESSION['sobrenome'] = $row['sobrenome'];
                        $_SESSION['id'] = $row['id_usuario'];
                        $_SESSION['admin'] = $row['admin'];
                        $_SESSION['credenciador'] = $row['credenciador'];
                        $_SESSION['palestrante'] = $row['palestrante'];
                //        $_SESSION['path'] = $_SERVER['DOCUMENT_ROOT']."/";
                        if (isset($_GET['voltar'])){
                            header("Location: ".$_GET['voltar']); 
                        } else {
                            header("Location: painel.php");
                        }
                        exit();
                    }else{
                        header("Location: login.php?error=Usuário ou senha Incorreta");
                        exit();
                    }
                }else{
                    header("Location: login.php?error=Usuário ou senha Incorreta");
                    exit();
                } 
            }
        }else{
        ?>
<!DOCTYPE html>
<html>
    <head>
        <title>LOGIN</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <base href="<?php echo $base_link; ?>">

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
            <h2 class="event">Entrar</h2>
           <form action="login.php<?php if (isset($_GET['voltar'])){echo "?voltar=".$_GET['voltar'];} ?>" method="post" class="container col-8">

                <div class="form-outline mb-4">
                    <input type="text" id="form2Example1" name="uname"  class="form-control" placeholder="User Name"/>
                    <label class="form-label" for="form2Example1">Email ou CPF</label>
                </div>

                   <!-- Password input -->
                <div class="form-outline mb-4">
                    <input type="password" id="form2Example2" class="form-control" name="password" placeholder="Password" />
                    <label class="form-label" for="form2Example2">Senha</label>
                </div>
                <?php if (isset($_GET['error'])) { ?>
                    <p class="error"><?php echo $_GET['error']; ?></p>
                <?php } ?>

                <button type="submit" class="btn btn-primary btn-block mb-4" >Entrar</button>
                <a class="btn btn-secondary btn-block mb-4" href="javascript:javascript:history.go(-1)">Retornar</a>  
                <div class="text-center">
                    <?php 
                    if (isset($_GET['voltar'])) { ?>
                        <p>Ainda não é cadastrado?  <a class="btn btn-outline-secondary" href="registro.php?voltar=<?php echo $_GET['voltar']; ?>">Cadastre-se aqui</a></p>    
                    <?php
                    } else { ?>
                        <p>Ainda não é cadastrado?  <a class="btn btn-outline-secondary" href="registro.php<?php echo $_GET['voltar']; ?>">Cadastre-se aqui</a></p>    
                    <?php 
                    } ?>
                    
                </div>

                <div class="text-center">
                    <p>Esqueceu a senha?  <a class="btn btn-outline-secondary" href="recuperar_senha.php">Recupere aqui</a></p>
                </div>
            
               <!-- <input type="text" name="uname" placeholder="User Name"><br><br>
                <label>Senha</label>
                <input type="password" name="password" placeholder="Password"><br><br> 
                <?php //if (isset($_GET['error'])) { ?>
                    <p class="error"><?php //echo $_GET['error']; ?></p>
                <?php } ?>
                <button type="submit">Login</button><br><br> -->
                    
            </form>

        </main>

        <footer class="container">
            <?php
                include('template/footer.html');
            ?>
        </footer>
            
    </body>
</html> 
<?php
        exit();
 ?>


