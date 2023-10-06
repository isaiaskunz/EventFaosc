<style>

.event{
    text-align: center;
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

.texto{
  display: block;
  color: black;
  padding: 14px 16px;
  text-decoration: none;
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

li a:hover {
  /*background-color: #111;*/
  background-color: #A9E2F3;
}

nav{
    margin-top: 5%;
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
<?php
            if(!isset($_SESSION)) 
            { 
                session_start(); 
            } 
            include $_SESSION['path']."db.php";
            $sql_evento = "SELECT * FROM evento WHERE id_evento=".$_GET['id'];
            $result = $conn->query($sql_evento);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            }
            
            $sql_img = "SELECT * FROM imagem_evento WHERE id_evento = ".$_GET['id'];
            $result_img = $conn->query($sql_img);
?>

</style>
<header class="container" style="margin-top: 1%; font-size: 15px; font-family: sans-serif;">
    
  <?php     

  if ($result_img->num_rows > 0) { ?>

    <div id="demo" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            
        <?php 
              while ($row_img = $result_img->fetch_assoc()) { ?>
                <div class="carousel-item active">
                  <img src="<?php echo $row_img['caminho'] ?>" alt="3º semana academica" class="d-block" style="width:100%">
                </div>    
        <?php
                } ?>
  
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        </button>
        </div>
    </div>
  
  <?php 
    } ?>

    <div class="nav row">
        <nav class="col-12 navbar-light bg-light sticky-top" style="margin-top: 2%;">
                <ul>
                    <li class="home">
                        <texto><b><?php echo $row['nome_evento'];?> </b></texto>
                    </li>
                    <!-- <li >
                        <a  href="#">Tutoriais/Faq</a>
                    </li> -->
                    <li >
                        <a  href="evento.php?id=<?php echo $_GET['id']; ?>"><span class="glyphicon glyphicon-info-sign"></span> Informações</a>
                    </li>
                    <li >
                        <a  href="evento_programacao.php?id=<?php echo $_GET['id']; ?>"><span class="glyphicon glyphicon-time"></span> Programação</a>
                    </li>
                    <!--<li >
                        <a  href="participante/cadastrar_no_evento.php"><span class="glyphicon glyphicon-pencil"></span> Inscrição</a>
                    </li> -->
                    <li >
                        <a  href="avaliacao/index.php"><span class="glyphicon glyphicon-pencil"></span> Avaliar Evento</a>
                    </li>

                </ul>
        </nav>
    </div>  

</header>

