<?php 
    session_start(); 
    use Dompdf\Dompdf;
    
//    $_SESSION['id'] = 103;
    $id_usuario = $_SESSION['id'];

    require_once 'dompdf/autoload.inc.php';
    if (!isset($_SESSION['id'])){
        echo "você não tem permissão para acessar este recurso";
        exit();
    }
    if (!isset($_GET['visualizar'])){
        include "../header.php";
    }

	$_SESSION['path'] = $_SERVER['DOCUMENT_ROOT']."/";    
    $base_link = "https://eventos.faosc.edu.br/";
    if ($_SERVER['HTTP_HOST'] == "localhost"){
        $base_link = "http://localhost/eventfaosc/";
        $_SESSION['path'] .= "eventfaosc/";
    }
?>

<?php 
    include $_SESSION['path']."db.php"; // servidor
//    include "../db.php"; // local

    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');

if (isset($_GET['gerar']) and ($_GET['gerar'] == 1) and (isset($_GET['evento']))) {
        $id_evento = $_GET['evento'];

        $sql = "SELECT *
            FROM certificado as c 
            where id_usuario = $id_usuario and id_evento = $id_evento
            order by 1 desc
            limit 1";
            $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "Você já possui certificado gerado para esse evento.";
        } else {
            $msg = gravar_informacoes_certificado($conn, $id_evento, $_SESSION['id']); 
            echo "<script>alert('$msg'); window.location.href = '" . $base_link . "participante/certificado.php';</script>";
        }
    } else if (isset($_GET['visualizar'])){
        $certificado_buscar = $_GET['visualizar'];
    } 

    function gravar_informacoes_certificado($conn, $id_evento, $id_participante){

        $sql_test = "select * from certificado where id_evento = $id_evento and id_usuario = $id_participante;";
        $result2 = $conn->query($sql_test);
        if ($result2->num_rows > 0) {
            return "Você já possui um certificado cadastrado para esse evento.";
        }
    }

    ?>