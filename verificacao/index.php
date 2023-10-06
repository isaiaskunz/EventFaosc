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

    if (isset($_POST['codigo']) && isset($_POST['cpf'])) {
            function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
            }
            $codigo = validate($_POST['codigo']);
            $cpf = validate($_POST['cpf']);

            $sql = "SELECT c.*, e.nome_evento 
                    FROM certificado as c 
                    INNER JOIN usuario as u using(id_usuario)
                    INNER JOIN evento as e using(id_evento)
                    WHERE c.cpf='$cpf' AND c.codigo_de_validacao='$codigo'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                echo "<div class='container alert alert-success' role='alert' >";
                echo "<h5>Informações encontradas:</h5>";
                echo "<br><b>Nome do participante: </b>" . $row['nome_completo'];
                echo "<br><b>Nome do evento: </b>" . $row['nome_evento'];
                echo "<br><b>Carga horária: </b>" . $row['carga_horaria_certificado'] . "h";
                echo "</div>";
            } else {
                echo "<div class=' container alert alert-warning' role='alert'>";
                echo "Não foram encontradas informações para os dados informados.";
                echo "</div>";
            }
        }


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Validade do Certificado</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
        
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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
                    <img src="../img/logofaosc.png" class="img-responsive img-fluid">
                </a>
            </div>

            <div class="col-12">
                
            </div>
        </div>        
    </footer>

    <main class="container col-8">
        <h2 class="event">Verificar validade do certificado</h2>
        <form action="verificacao/index.php" method="post" class="container col-8">

            <div class="form-outline mb-4">
                <label class="form-label" for="codigo">Código de verificação</label>
                <input type="text" id="codigo" name="codigo"  class="form-control"/>
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="cpf">CPF (Sem pontos)</label>
                <input type="text" id="cpf" class="form-control" name="cpf" />
            </div>
            <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>

            <button type="submit" class="btn btn-primary mb-4" >Verificar</button>
            <div class="text-center">
                
            </div>
                
        </form>

    </main>

</body>
</html>

        
