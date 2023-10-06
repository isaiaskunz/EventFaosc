<?php 
    use Dompdf\Dompdf;

    require_once 'dompdf/autoload.inc.php';

    
//    include $_SESSION['path']."db.php"; // servidor
/*    include "../db.php"; // local
    $sql = "SELECT * FROM usuario as u 
            INNER JOIN tipo_perfil_acesso as t 
            ON u.id_tipo_perfil_acesso=t.id_tipo_perfil_acesso ";
            $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row['id_usuario'];
            echo $row['nome'];                        
            echo $row['sobrenome'];
            echo $row['email'];
        }
    }
*/
    $quebra_de_pagina = "<div style='page-break-after: always;'></div>";
    $nome_completo = "Junior Romanzini";
    $nome_evento = "3ª Semana Acadêmica Integrada FAOSC";
    $documento = "CPF";
    $numero_doc = "042.104.798-96";
    $data_evento = "09 a 12/05/2023";
    $qtd_horas = "20";
    $nome_atividades = [];
    $local = "Palmitos/SC";
    $data = "12 de maio de 2023";
    $nome_assinatura_diploma = "Profa. Me Lucineide Orsolin";
    $cargo_assinatura_diploma = " Diretora Acadêmica";

    $dompdf = new Dompdf();

    $html = '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>';

    $html .= "<div style='margin-top:1px'>";
    $html .= "<div class='row'> 
                <div class='col-md-12' >
                    <img align='left' src='https://eventos.faosc.edu.br/img/logofaosc.png' style= 'width:20%; heigth: 20%;'>
                    <img align='right' src='https://eventos.faosc.edu.br/img/fundo_logo_3semana.png' style= 'width:20%; heigth: 20%;'>
                </div>
            </div>";

    $html .= "<div class='row-12'>
                <div class='col-md-12'>
                    <div align='center' style='font-size:20px'>
                        <h3>CERTIFICADO DE PARTICIPAÇÃO</h3>
                    </div>
                </div>
            </div>";

    $html .= " <div class='row-12'>
                    <div class='col-md-12'>
                        <div align='center' style='font-size:20px'>
                            A Direção Acadêmica da Faculdade FAOSC, em suas atribuições, certifica pelo presente a participação na " . $nome_evento . " do acadêmico(a) $nome_completo , $documento: $numero_doc, na qualidade de ouvinte,    realizada no período de $data_evento, totalizando a carga horária registrada de " . $qtd_horas . "h.
                        </div>
                    </div>
                </div>";


$html .= "<div class='row'>";
    
    $html .= "<div align='right' style='font-size:20px; margin-top:1%;'>
                    $local, $data.
                </div>";

    //$html .= "<br><br><br><br>";

    $html .= "<div align='center' style='font-size:20px; margin-top:6%; '>
            <img align='center' src='https://eventos.faosc.edu.br/img/assinatura_teste.png' style='width:15%; heigth: 15%;'><br>        
                $nome_assinatura_diploma <br> $cargo_assinatura_diploma
            </div> </div>";

   $html .= "<div align='center' style=\"font-size:12px; margin-top:8%;\">
                <img align='left' src='https://eventos.faosc.edu.br/img/fundo_circulo.png' style='width:10%; heigth: 10%;'>
                <img align='right' src='https://eventos.faosc.edu.br/img/fundo_circulo.png' style='width:10%; heigth: 10%;'>

                SOCIEDADE EDUCACIONAL DE PALMITOS – FACULDADE DO OESTE DE SANTA CATARINA<br>
                FAOSC (FACULDADE REGIONAL PALMITOS)<br>Recredenciamento Portaria MEC nº947 de 11/11/2020<br> CNPJ 07.488.858/0001-96<br>
            </div>";

    $html .= "</div>";

  


    $html .= $quebra_de_pagina;
    $html .= "Verso do certificado";

    $dompdf->loadHtml($html);

    $dompdf->set_option('defaultFont', 'sans');
    $dompdf->set_option('isRemoteEnabled', TRUE);

    $dompdf->set_Paper('A4','landscape'); // portrait

    $dompdf->render();

    //$dompdf->stream(); // deixando isso vai fazer o download diretamente para o usuário

    header('Content-type: application/pdf'); // assim vai mostrar o pdf na tela
    echo $dompdf->output();

    //echo $html;
?>