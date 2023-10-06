<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }     
    $_SESSION['path'] = $_SERVER['DOCUMENT_ROOT']."/";    
    $base_link = "https://eventos.faosc.edu.br/";
    if ($_SERVER['HTTP_HOST'] == "localhost"){
        $base_link = "http://localhost/eventfaosc/";
        $_SESSION['path'] .= "eventfaosc/";
    }
    include('./install/index.php');
    include $_SESSION['path']."header.php";       
?>

<!DOCTYPE html>
<html>
    <head>
        <title>EVENTFAOSC</title>
        <link rel="stylesheet" type="text/css" href="style.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <base href="<?php echo $base_link; ?>">

        <style>
            .item{
                background-color: #1B0574;
                font-size: ;
                font-family: ;
                padding: 10px;
                margin: 20px;
                float: center;
                text-align: center;
                border-radius: 10px;

            }
            .item:hover{
                background-color:#1A3E05 ;
            }
            a{
                color: ghostwhite;
            }
            .semana:hover, .active {
                color:#00ff00;
            }
        </style>
    </head>
    <body>
    <div class="container">
    <div id="demo" class="carousel slide" data-bs-ride="carousel">
         <div class="carousel-indicators">
            <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="3"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="4"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="5"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="img/banner1.jpeg" alt="3º semana academica" class="d-block" style="width:100%">
            </div>

            <div class="carousel-item">
              <img src="img/banner2.jpeg" alt="inscrição" class="d-block" style="width:100%">
            </div>

            <div class="carousel-item">
              <img src="img/banner3.jpeg" alt="Faculdade Faosc" class="d-block" style="width:100%">
            </div>

            <div class="carousel-item">
              <img src="img/banner4.jpeg" alt="Local do Evento" class="d-block" style="width:100%">
            </div>

            <div class="carousel-item">
              <img src="img/banner5.jpeg" alt="Camera dos Vereadores" class="d-block" style="width:100%">
            </div>
            <div class="carousel-item">
              <img src="img/banner6.jpeg" alt="Programação evento" class="d-block" style="width:100%">
            </div>
            
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        </button>
        </div>
    </div>

        <div class="header">
                <?php
                    include $_SESSION['path'].'header_proximo_evento.php';
                ?>
        </div>
        
        <br>
        <br>
        </header>


        <main class="container">
        <div class="container row">
            <div class="col-md-8" style="font-size: 15px;">
                <p class="text-center">
                <b><?php echo $row['nome_evento'];?></b>
                </p>
                <p style="text-align:justify;">
                <?php echo $row['descricao_curta'];?>
                </p>
            </div>

	<div class="col-md-4">
		<div class="item">
			<a  class="semana" href="#">Inscrever-se</a>
		</div>

		<!--<div class="item">
			<a class="semana" target="_blank" href="painel.php">Área Restrita</a>
		</div>-->
		<div class="item">
			<a  class="semana" target="_blank" href="https://faosc.edu.br/">Site Oficial Faosc</a>
		</div>
	</div>

</div>
            <?php 
                include $_SESSION['path']."db.php";
                $sql = $conn->query("SELECT * FROM eventfaosc.evento
                                    WHERE data_fim > NOW() ");
                if (mysqli_num_rows($sql) >=2) {
                    ?>
                        <br>
                        <h3>Próximos Eventos:</h3>
                    <?php
                    while ($row = $sql->fetch_assoc()){
                        echo "<a href=evento.php?id=".$row['id_evento'].">".$row['id_evento'] .",". 
                            $row['id_tipo_evento'] .",".
                            $row['nome_evento'].",".
                            $row['data_inicio'].",".
                            $row['data_fim']."</a></br>";
                    }
                }
            ?>
            
            <?php 
                include $_SESSION['path']."db.php";
                $sql = $conn->query("SELECT * FROM eventfaosc.evento
                                    WHERE data_fim < NOW() ");
                if (mysqli_num_rows($sql) >=1) {
                    ?>
                        <br>
                        <h3>Eventos Anteriores:</h3>
                    <?php
                    while ($row = $sql->fetch_assoc()){
                        echo "<a href=evento.php?id=".$row['id_evento'].">".$row['id_evento'] .",". 
                            $row['id_tipo_evento'] .",".
                            $row['nome_evento'].",".
                            $row['data_inicio'].",".
                            $row['data_fim']."</br>";
                    }
                }
            ?>


        </main>


        <footer style="margin:2%; background-color: ; padding-left: 10px; padding-right: 10px;">
            <?php
                include('template/footer.html');
            ?>

        </footer>



    </body>
</html>

