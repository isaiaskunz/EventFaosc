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


</style>
<header class="container" style="margin-top: 1%; font-size: 15px; font-family: sans-serif;">
    <div class="nav row">
        <nav class="col-12 navbar-light bg-light sticky-top" style="margin-top: 2%;">
                <ul>
                    <li class="home">
                    <?php
                        if(!isset($_SESSION)) 
                        { 
                            session_start(); 
                        } 
                        if ($_SERVER['HTTP_HOST'] == "localhost"){
                            include "db.php";
                        } else {
                            include "../db.php";
                        }
            //            print(mysqli_num_rows($sql2));
                        $query2 = "SELECT * FROM evento WHERE data_fim > NOW() LIMIT 1;";
                        $sql2 = $conn->query($query2);
                        
                        if (mysqli_num_rows($sql2) > 0) {
                            $row = $sql2->fetch_assoc();
                            echo "<texto><b>Proximo Evento: </b>".$row['nome_evento']."</texto>";
                        }
                        else {
                            $sql2 = $conn->query("SELECT * FROM evento
                                            WHERE data_fim < NOW() LIMIT 1");
                            if (mysqli_num_rows($sql2) > 0) {
                                $row = $sql2->fetch_assoc();
                                echo "<texto><b>Evento Aterior: </b>".$row['nome_evento']."</texto>";
                            }
                        }
                    ?>
                    </li>
                    <!-- <li >
                        <a  href="#">Tutoriais/Faq</a>
                    </li> -->
                    <li >
                        <a  href="evento.php?id=<?php echo $row['id_evento']; ?>"><span class="glyphicon glyphicon-info-sign"></span> Informações</a>
                    </li>
                    <li >
                        <a  href="evento_programacao.php?id=<?php echo $row['id_evento']; ?>"><span class="glyphicon glyphicon-time"></span> Programação</a>
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

