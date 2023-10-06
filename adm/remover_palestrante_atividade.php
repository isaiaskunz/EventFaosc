<?php

    $base_link = "https://eventos.faosc.edu.br/";
    if ($_SERVER['HTTP_HOST'] == "localhost"){
        $base_link = "http://localhost/eventfaosc/";
    }
?
<head>
    <base href="<?php echo $base_link; ?>">
</head>
<?php
      
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    include $_SESSION['path']."db.php";
    
    if ($_SESSION['admin'] != TRUE){
        echo "você não tem permissão para acessar este recurso";
        exit();
    }
    if (isset($_GET['id_atividade']) && isset($_GET['id_usuario']) ){
        $id_atividade = $_GET['id_atividade'];
        $id_usuario = $_GET['id_usuario'];
        $sql = "DELETE FROM atividade_palestrante 
                    WHERE id_atividade = $id_atividade and id_usuario=$id_usuario";
        $result = $conn->query($sql);
        if ($result == true) {
            header("location: ./palestrantes.php?id=$id_atividade"."&msg=palestrante removido com sucesso"."&id_evento=".$_GET['id_evento']);
            exit();
            }
        else {
            header("location: ./palestrantes.php?id=$id_atividade"."&msg=falha ao remover palestrante. Contate o Administrador");
            exit();
        }
    }
    else {
        header("location: ./palestrantes.php?id=$id_atividade"."&msg=falha ao remover palestrante. Contate o Administrador");
        exit();
    }

?>

