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

    if (isset($_SESSION['id']) && isset($_SESSION['user_name']) || ((basename($_SERVER['PHP_SELF'],'.php') == "index" ||
    basename($_SERVER['PHP_SELF'],'.php') == "evento")||
    basename($_SERVER['PHP_SELF'],'.php') == "informacoes" ||
    basename($_SERVER['PHP_SELF'],'.php') == "programacao" ||
    basename($_SERVER['PHP_SELF'],'.php') == "evento_programacao"  ) )
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <base href="<?php echo $base_link; ?>">
</head>

<style>

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
    body {font-family: fantasy; margin:0}
    .mySlides {display: none}
    img {vertical-align: middle;}

    /* Slideshow container */
    .slideshow-container {
      max-width: 100%;
      position: relative;
      margin: auto;
    }

</style>

<header class="container" style="margin-top: 1%; font-size: 15px; font-family: fantasy;> 

<?php 
    if (isset($_GET['msg'])) { ?>
        <div class="alert alert-primary" role="alert" id='alerta'>
            <?php echo ($_GET['msg']); ?>
        </div>
    <?php 
    } ?>
    <script>
        $("#alerta").fadeTo(3000, 500).slideUp(500, function() {
            $("#success-alert").slideUp(500);
        });
    </script>
    
    <div class="container row" >
        <div class="event-logo col-2">
            <a href="index.php">
                <img src= "img/logofaosc.png" class="img-responsive img-fluid">
            </a>
        </div>
        <div class="event col-10">
            <br>
            <h1 style="text-shadow: #146DB0 5px 4px 5px; font-size: 60px;"> <span style="color: #041333;"> EVENTOS FAOSC</span> </h1>
        </div>
    </div>
    <div class="nav row">
        <nav class="col-12 navbar-light bg-light sticky-top" style="margin-top: 1%;">
                <ul>
                    
                    <li class="home"><a  href="index.php">
                            <span class="glyphicon glyphicon-home"></span>
                            Home 
                        </a>
                    </li>
                    <?php 
                        if (isset($_SESSION['id']) && isset($_SESSION['user_name'])){ ?>
                            <li>
                                <a href="logout.php">
                                    <span class="glyphicon glyphicon-log-out">
                                    </span> Sair
                                </a>
                            </li>
                         
                            <li>
                                <a href="registro.php?id=<?php echo $_SESSION['id']; ?>">
                                    <span class="glyphicon glyphicon-user">
                                    </span> Perfil
                                </a>
                            </li>
                            <li>
                                <a  href="painel.php">
                                    <span class="glyphicon glyphicon-cog">
                                    </span> Painel
                                </a>
                            </li>
                            <li>
                                <texto>
                                    <?php echo "Olá ".$_SESSION['name']." ".$_SESSION['sobrenome']; ?>
                                </texto>
                            </li>
                    <?php
                        } 
                        else { ?>
                            <li><a href="login.php">
                                    <span class="glyphicon glyphicon-log-in"></span>
                                    Entrar
                            </a></li>
                            <li><a  href="registro.php"> 
                                    <span class="glyphicon glyphicon-user"></span>
                                    Registrar-se
                                </a></li>
                    <?php        
                        } ?>
                </ul>
        </nav>
        
    </div>  
    
</header>

<?php
?>
