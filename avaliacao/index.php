<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    include_once('../tools.php');
    include_once("../db.php");
    include "../header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Avaliação Evento</title>

    <link rel="stylesheet" type="text/css" href="style.css">
        
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!--<base href="http://eventos.faosc.edu.br/">-->
    <base href="http://localhost/eventfaosc/">




    <style>
        /* style range */
        input[type=range] {
            width: 100%;
            max-width: 100%;
            margin-left: 0;
        }

        /* style datalist */
        input[type=range] + datalist {
            display: block;
            margin-top: -4px;
        }
        input[type=range] + datalist option {
            display: inline-block;
            width: calc((100% - 12px) / (var(--list-length) - 1));
            text-align: center;
        }
        input[type=range] + datalist option:first-child {
            width: calc((100% - 12px) / ((var(--list-length) - 1) * 2) + 6px);
            text-align: left;
        }
        input[type=range] + datalist option:last-child {
            width: calc((100% - 12px) / ((var(--list-length) - 1) * 2) + 6px);
            text-align: right;
        }
    </style>
</head>
<body class="container">
<?php
            $nota_chance_recomendarErr = $id_motivo_notaErr = $id_tipo_aluno_faoscErr = "";
            $nota_chance_recomendar = $id_motivo_nota = $id_tipo_aluno_faosc = "";
            $ok = TRUE;
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($_POST["id_tipo_aluno_faosc"])) {
                   $id_tipo_aluno_faoscErr = "Informe um nome válido";
                   $ok=FALSE;
                } else {
                    $id_tipo_aluno_faosc = test_input($_POST["id_tipo_aluno_faosc"]); 
                }
                
                if (empty($_POST["nota_chance_recomendar"])) {
                    $nota_chance_recomendarErr = "Informe um nome válido";
                    $ok=FALSE;
                 } else {
                     $nota_chance_recomendar = test_input($_POST["nota_chance_recomendar"]); 
                 }
                
                if (empty($_POST["id_motivo_nota"])) {
                    $id_motivo_notaErr = "Informe um motivo válido";
                    $ok=FALSE;
                 } else {
                     $id_motivo_nota = test_input($_POST["id_motivo_nota"]); 
                 }

                if (($ok == TRUE) and (!isset($_SESSION['votou_avaliacao']))){
                    include "../db.php";
                    $sql = "INSERT INTO avaliacao (
                                id_evento,
                                id_tipo_aluno_faosc, 
                                nota_chance_recomendar, 
                                id_motivo_nota,
                                data_horario_avaliacao) 
                            VALUES (
                                '1',
                                '$id_tipo_aluno_faosc',
                                '$nota_chance_recomendar',
                                '$id_motivo_nota',
                                TIMESTAMPADD(HOUR, -3, NOW()))";
                    $result = $conn->query($sql);
                    if ($result == TRUE) {
                        $_SESSION['votou_avaliacao'] = true;
                        echo "Obrigado pela sua avaliação!<BR><BR><BR>";
                    }
                }
            }

?>


    <div class="container row" >
        <div class="col-md-8" style="font-size:14px;">
        <?php if ((isset($_SESSION['votou_avaliacao'])) and ($_SESSION['votou_avaliacao'])) {
            echo "<h3>Você já avaliou o evento!</h3>";
        } else { ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <!--<form method="post" action="http://localhost/eventfaosc/avaliacao/index.php">-->

                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example1">Já é estudante FAOSC? </label>
                    <select value="0"id="id_tipo_aluno_faosc" name="id_tipo_aluno_faosc" class="form-select" aria-label="Default select example" onchange="userFaosc()">
                        <?php 
                            $sql = $conn->query("SELECT * FROM aluno_faosc");
                                echo "<option value='-1'>Não</option>";
                            while ($row = $sql->fetch_assoc()){
                                echo "<option value=\"". $row['id_tipo_aluno_faosc'] ."\">" . $row['descricao_tipo_aluno_faosc'] . "</option>";
                            }
                        ?>
                    </select>            
                </div>


                <div class="form-group">
                    <label for="pergunta_escala">Baseado em SUAS EXPERIÊNCIAS recentes e utilizando uma ESCALA DE O A 10, qual a CHANCE de você RECOMENDAR a Semana Acadêmica Integrada FAOSC para um amigo ou familiar?</label>
                   <!-- <input type="number" class="form-control" id="pergunta_escala" name="pergunta_escala" min="0" max="10" placeholder="Escala de 0 a 10"> -->
                    <input type="range" id="nota_chance_recomendar" name="nota_chance_recomendar" list="number" min="0" max="10" step="1" value="5">
                    <!-- escolher un dos inputs acima para usar -->
                        <datalist id="number" style="--list-length: 12;">
                        <option value="0"></option>
                        <option value="1"></option>
                        <option value="2"></option>
                        <option value="3"></option>
                        <option value="4"></option>
                        <option value="5"></option>
                        <option value="6"></option>
                        <option value="7"></option>
                        <option value="8"></option>
                        <option value="9"></option>
                        <option value="10"></option>
                        </datalist>
                </div>

                <div class="form-group">
                    <label for="id_motivo_nota">A RAZÃO ATRIBUÍDA A NOTA: <span class="error">* </label> <?php echo $id_motivo_notaErr;?> </span>
                    <select class="form-control" id="id_motivo_nota" name="id_motivo_nota">
                        <option value="">Escolha uma opção</option>
                        <?php 
                            $sql = $conn->query("SELECT * FROM motivo_nota");
                            while ($row = $sql->fetch_assoc()){
                                echo "<option value=\"". $row['id_motivo_nota'] ."\">" . $row['descricao_motivo_nota'] . "</option>";
                            }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
            <?php } ?>
        </div>        
    </div>

</body>
</html>

<script>
    // change thumb-width variable on input change
    var tw = document.getElementById('thumb-width');
    var mr = document.getElementById('my-range');
    var ml = document.getElementById('my-datalist');
    tw.onchange = () => {
        mr.style.setProperty('--thumb-width', tw.value + 'px');
    ml.style.setProperty('--thumb-width', tw.value + 'px');
    }
</script>
