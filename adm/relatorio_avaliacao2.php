<?php
    
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if ($_SESSION['admin'] != TRUE){
        echo "você não tem permissão para acessar este recurso";
        exit();
    }
?>

<?php 

    include $_SESSION['path']."db.php";
    $sql = "SELECT * FROM usuario as u 
            INNER JOIN tipo_perfil_acesso as t 
            ON u.id_tipo_perfil_acesso=t.id_tipo_perfil_acesso ";
            $result = $conn->query($sql);


?>

<!DOCTYPE html>
<html>
    <head>
        <title>Usuarios</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


                
        <?php
            include $_SESSION['path']."header.php";
        ?>
    
    </head>
    <body>


        
        <div class="container row-12" style="margin-top:2%">
            <div class="col-md-12" style="text-align:center;">
                 <h4>RESULTADO NPS DA 3ª SEMANA ACADÊMICA INTEGRADA FAOSC 2023</h4>
                 <h5>RESULTADOS OBTIDOS: </h5>
                 
            </div>
            
            <div class="row-12" style="margin-top:5%">

                <div class="container">
                    <label class="form-label">Baseado em SUAS EXPERIÊNCIAS recentes e utilizando uma ESCALA DE O A 10, qual a CHANCE de você RECOMENDAR a Semana Acadêmica Integrada FAOSC para um amigo ou familiar?</label>
                    <div>
                    <?php 
                        include "../db.php";
                        $sql = "SELECT count(id_avaliacao) as total FROM avaliacao;";
                        $result = $conn->query($sql);
                    
                        if ($result && $result->num_rows == 1) {
                            $row = $result->fetch_assoc();
                            echo "Total avaliações: " . $row['total'];
                            $votosTotal = $row['total'];
                            
                            //$sql = "select nota_chance_recomendar, count(nota_chance_recomendar) as total from avaliacao group by 1";
                            $sql = "SELECT nota_chance_recomendar, COUNT(nota_chance_recomendar) as total FROM avaliacao GROUP BY nota_chance_recomendar ORDER BY nota_chance_recomendar ASC";
                            $result = $conn->query($sql);
                            if ($result) {
                                $data = array();
                                while ($row = $result->fetch_assoc()) {
                                    $data[] = [$row['nota_chance_recomendar'], (int)$row['total']];
                                }
                                ?>
                                <script>
                                    var chartData = <?php echo json_encode($data); ?>;
                                    google.charts.load("current", { packages: ["corechart"] });
                                    google.charts.setOnLoadCallback(drawChart);
                
                                    function drawChart() {
                                        var data = new google.visualization.DataTable();
                                        data.addColumn("string", "Element");
                                        data.addColumn("number", "Density");
                                        data.addRows(chartData);
                
                                        var options = {
                                            width: "100%",
                                            height: "100%",
                                            is3D: true,
                                            bar: { groupWidth: "85%" },
                                            legend: { position: "none" },
                                        };
                
                                        var chart = new google.visualization.ColumnChart(
                                            document.getElementById("columnchart_values")
                                        );
                
                                        function resizeChart() {
                                            chart.draw(data, options);
                                        }
                
                                        window.addEventListener("resize", resizeChart, false);
                                        resizeChart();
                                    }
                                </script>
                                <?php
                            }
                        }
                        ?>
                        
                    </div>
                    <!-- mostrar aqui um grafico com a quantidade de respostas de 1 a 10 conforme arquivo word -->
                    <div id="columnchart_values" style="width: 80%; height: 75%; margin: auto; allign: center;"></div>
                </div>

                <div class="container" style="margin-top: 2px;">
                    <label class="form-label">A RAZÃO ATRIBUÍDA A NOTA:</label>
                    <div>
                    <?php
                        include "../db.php";
                        $sql = "SELECT COUNT(id_motivo_nota) as total FROM avaliacao;";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows == 1) {
                            $row = $result->fetch_assoc();
                            echo "Total avaliações: " . $row['total'];
                            $votosTotal = $row['total'];

                            $sql = "SELECT id_motivo_nota, COUNT(id_motivo_nota) as total FROM avaliacao GROUP BY id_motivo_nota ORDER BY id_motivo_nota ASC";
                            $result = $conn->query($sql);
                            if ($result) {
                                function getLabelForId($id) {
                                    switch ($id) {
                                        case 1:
                                            return 'Organização do Evento.';
                                        case 2:
                                            return 'Cordialidade e Agilidade.';
                                        case 3:
                                            return 'Qualidade das Palestras.';
                                        case 4:
                                            return 'Falhas de Atendimento.';
                                        case 5:
                                            return 'Desinteresse nas Palestras e/ou Qualidade das Palestras.';
                                        case 6:
                                            return 'Qualidade das Palestras e/ou Gestão do Evento consideradas de Excelência.';
                                        case 7:
                                            return 'Prefiro não opinar.';
                                        default:
                                            return 'Unknown';
                                    }
                                }

                                $dataPie = array();
                                $dataPie[] = ['Motivo Nota', 'Quantidade'];
                                while ($row = $result->fetch_assoc()) {
                                    $label = getLabelForId($row['id_motivo_nota']);
                                    $dataPie[] = [$label, (int)$row['total']];
                                }
                            }
                        }
                        ?>
                    </div>
                    <?php if (isset($dataPie)) : ?>
                        <script>
                            var chartDataPie = <?php echo json_encode($dataPie); ?>;
                            google.charts.load("current", { packages: ["corechart"] });
                            google.charts.setOnLoadCallback(drawChart);

                            function drawChart() {
                                var data = google.visualization.arrayToDataTable(chartDataPie);

                                var options = {
                                    width: '100%',
                                    height: '100%',
                                    is3D: true,
                                };
                                var chart = new google.visualization.PieChart(document.getElementById("piechart_3d"));

                                function resizeChart() {
                                    chart.draw(data, options);
                                }
                                window.addEventListener("resize", resizeChart, false);
                                resizeChart();
                            }
                        </script>
                    <?php endif; ?>

                    </div>
                    <!-- mostrar aqui um grafico de pizza com a porcetagem conforme arquivo word -->
                    <div id="piechart_3d" style="width: 100%; height: 100%; margin: auto; allign: center;"></div>
                </div>
            <br>
            <br>
            <div class="text-center">
                <a class="btn btn-secondary mb-4" href="painel.php">Retornar ao Painel</a>
            </div>
        </div>
            
                    
      </div>
    </body>
</html>