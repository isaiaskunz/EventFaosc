<?php
    session_start(); 
    include('db.php');
    if (isset($_POST['id_participante']) and ($_SESSION['credenciador'] or $_SESSION['admin'])){
        $id_participante = $_POST['id_participante'];
        if (isset($_POST['tipo_registro'])) {
            if ($_POST['tipo_registro'] == "consulta"){
                $sql = "SELECT 
                    CAST(p.data_horario_entrada AS DATE) as data_horario_entrada, u.id_usuario, u.nome, u.sobrenome, td.descricao as tipo_doc, 
                    u.numero_documento, p.data_horario_saida, CAST(TIMESTAMPADD(HOUR, -3, NOW()) AS DATE) as data_hoje, (
                        select count(*) from presenca where CAST(data_horario_entrada AS DATE) = CAST(TIMESTAMPADD(HOUR, -3, NOW()) AS DATE)) as total_participantes_hoje 
                    FROM inscricao_evento ie
                    INNER JOIN usuario u USING (id_usuario)
                    LEFT JOIN presenca p USING (id_usuario)
                    LEFT JOIN tipo_documento td USING (id_tipo_documento)
                    WHERE u.id_usuario = '$id_participante' order by 1 desc limit 1";
//                        echo $sql;
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) === 1) { // se encontrou registro, então existe inscrição no evento
                    $row = mysqli_fetch_assoc($result);
                    $status_entrada = "";
                    if ($row['data_horario_entrada']!=$row['data_hoje']){ // assim para permitir o registro de presenca em dias diferentes
                        $status_entrada = "0"; // ainda não foi registrada entrada
                    } else if (($row['data_horario_entrada']!="") and ($row['data_horario_saida']=="")){
                        $status_entrada = "1"; // foi registrada entrada mas não a saída
                    } else if (($row['data_horario_entrada']!="") and ($row['data_horario_saida']!="")){
                        echo $row['data_horario_entrada'];
                        $status_entrada = "2"; // foi registrada entrada e saída
                    }
                    echo $row['id_usuario'] . "-|-" . $row['nome'] . "-|-" . $row['sobrenome'] . "-|-" . $row['tipo_doc'] . "-|-" . $row['numero_documento'] . "-|-" . $status_entrada . "-|-" . $row['total_participantes_hoje'];                    
                } else {
                    $status_entrada = "-1";
                    echo $status_entrada;
                } 

//            } else if (($_POST['tipo_registro'] == "entrada") and ($_POST['id_atividade'])){
            } else if (($_POST['tipo_registro'] == "entrada")){
//                $id_atividade = $_POST['id_atividade'];
                $sql = "INSERT INTO presenca (
                    id_usuario, 
                    id_atividade, 
                    data_horario_entrada) 
                VALUES (
                    '$id_participante',
                    (SELECT id_atividade FROM atividade WHERE 1 order by 1 asc limit 1),
                    TIMESTAMPADD(HOUR, -3, NOW()))";
                $result = $conn->query($sql);            
                if ($result == TRUE) {
                    echo "1-|-Entrada registrada";
                } else {
                    echo "0-|-Erro ao registrar entrada";
                }
            }
            else if ($_POST['tipo_registro'] == "saida"){                
//                $id_atividade = $_POST['id_atividade'];
//                $sql = "SELECT * FROM presenca WHERE (id_usuario='$id_participante' AND id_atividade='$id_atividade')";
                $sql = "SELECT id_presenca FROM presenca WHERE (id_usuario='$id_participante' AND id_atividade=(SELECT id_atividade FROM atividade WHERE 1 order by 1 asc limit 1)) order by 1 desc limit 1";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) === 1) { // se encontrou registro, então deve registrar a saída
                    $row = mysqli_fetch_assoc($result);
                    $id_presenca = $row['id_presenca'];
                    $sql = "UPDATE presenca 
                        SET data_horario_saida = TIMESTAMPADD(HOUR, -3, NOW())
                        WHERE id_presenca = $id_presenca";
                    $result = $conn->query($sql);            
                    if ($result == TRUE) {
                        echo "1-|-Saída registrada";
                    } else {
                        echo "0-|-Erro ao registrar saída";
                    }
                }                 
            }
        }
    } else if (isset($_POST['nome_pesquisar'])){
        $nome_pesquisar = $_POST['nome_pesquisar'];
        $sql = "SELECT * FROM usuario WHERE nome like '%$nome_pesquisar%' or sobrenome like '%$nome_pesquisar%'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            print($row['id_usuario']."-|-".$row['nome']."-|-".$row['sobrenome']."-|-");
        }
        mysqli_free_result($result);
    }
    exit();
?>
