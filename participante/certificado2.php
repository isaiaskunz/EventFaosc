<?php 
    session_start(); 
    use Dompdf\Dompdf;
    
//    $_SESSION['id'] = 103;
    $id_usuario = $_SESSION['id'];

    require_once 'dompdf/autoload.inc.php';

    
    include $_SESSION['path']."db.php"; // servidor
//    include "../db.php"; // local

    setlocale(LC_TIME, 'portuguese'); 
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
            echo "<script>alert('$msg'); window.location.href = 'https://eventos.faosc.edu.br/participante/certificado.php';</script>";
        }
    } else if (isset($_GET['visualizar'])){
        $certificado_buscar = $_GET['visualizar'];

        $sql = "SELECT ia.id_inscricao_alteracao_status_registro_acesso, c.*, e.nome_evento, u.numero_documento, ac.nome_assinatura, ac.funcao_pessoa_assinatura, (   
                select id_status_pagamento from inscricao_alteracao_status_registro_acesso where id_inscricao_alteracao_status_registro_acesso = (
                    select MAX(id_inscricao_alteracao_status_registro_acesso) from inscricao_alteracao_status_registro_acesso ia WHERE ia.id_inscricao = ie.id_inscricao
                    )
                ) as status_pagamento
                FROM certificado as c 
                INNER JOIN evento as e ON e.id_evento = c.id_evento
                INNER JOIN usuario as u ON u.id_usuario = c.id_usuario
                INNER JOIN inscricao_evento as ie ON ie.id_usuario = c.id_usuario
                INNER JOIN inscricao_alteracao_status_registro_acesso as ia ON ia.id_inscricao = ie.id_inscricao
                INNER JOIN assinatura_certificado as ac ON ac.id_assinatura_certificado = c.id_assinatura_certificado
                where id_certificado = $certificado_buscar
                order by 1 desc
                limit 1";
                $result = $conn->query($sql);

        $falta_pagamento = true;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (($row['status_pagamento'] == 3) or ($row['status_pagamento'] == 4)){ // só recebe certificado quem tiver pago ou for isento
                    $falta_pagamento = false;
                    $nome_completo = $row['nome_completo'];
                    $numero_doc = $row['numero_documento'];
                    $qtd_horas = $row['carga_horaria_certificado'];
                    $nome_evento = $row['nome_evento'];
                    $data_certificado = strftime("%d de %B de %Y", strtotime($row['data_emissao'])); 
                    $nome_assinatura_diploma = $row['nome_assinatura'];
                    $cargo_assinatura_diploma = $row['funcao_pessoa_assinatura'];  
                    $codigo_de_validacao = $row['codigo_de_validacao'];               
                } else {
                    $falta_pagamento = true;
                }            
            }
        }

        if ($falta_pagamento){
            echo "Você não realizou o pagamento da inscrição e por isso não tem direito ao certificado de participação!";
        } else {
            $quebra_de_pagina = "<div style='page-break-after: always;'></div>";
            $data_evento = "09 a 12/05/2023"; // deve pegar pela data do evento
            $nome_atividades = [];
            $local = "Palmitos/SC"; // deve pegar pelo endereço do evento

            $dompdf = new Dompdf();

            $html = "";

            $html .= "<br><br><br><br>";

            $html .= "<div align='center' style='font-size:20px'>
                <h3>CERTIFICADO DE PARTICIPAÇÃO</h3>
                </div>";

            $html .= "<div align='center' style='font-size:20px'>
                A Direção Acadêmica da Faculdade FAOSC, em suas atribuições, certifica pelo presente a participação na " .
                $nome_evento . " do acadêmico(a) $nome_completo, CPF: $numero_doc, na qualidade de ouvinte, 
                realizada no período de $data_evento, totalizando a carga horária registrada de " . $qtd_horas . "h.
                </div>";

            $html .= "<br><br>";
            
            $html .= "<div align='right' style='font-size:20px'>
                $local, $data_certificado.
                </div>";

            $html .= "<br><br><br><br>";

            $html .= "<div align='center' style='font-size:20px'>
                $nome_assinatura_diploma <br>
                $cargo_assinatura_diploma
                </div>";

            $html .= "<br><br><br><br><br><br><br><br>";


            $html .= "<div align='center' style='font-size:16px'>
                SOCIEDADE EDUCACIONAL DE PALMITOS – FACULDADE DO OESTE DE SANTA CATARINA<br>
                FAOSC (FACULDADE REGIONAL PALMITOS)<br>
                Recredenciamento Portaria MEC nº947 de 11/11/2020<br>
                CNPJ 07.488.858/0001-96<br>
                </div>";



            $html .= $quebra_de_pagina;




            $sql = "SELECT DATE_FORMAT(a.data_hora_inicio, '%d/%m/%Y') as data_atividade, a.nome_atividade, a.data_hora_inicio, (
                    SELECT GROUP_CONCAT(concat(nome, ' ', sobrenome)) from usuario as u2 
                    INNER JOIN atividade_palestrante as ap2 on ap2.id_usuario = u2.id_usuario
                    WHERE a.id_atividade = ap2.id_atividade and ap2.id_atividade = ap.id_atividade
                ) as nomes_palestrantes
                FROM presenca as p
                INNER JOIN atividade as a ON DATE_FORMAT(p.data_horario_entrada, '%Y-%m-%d') = DATE_FORMAT(a.data_hora_inicio, '%Y-%m-%d')
                INNER JOIN atividade_palestrante as ap ON ap.id_atividade = a.id_atividade
                INNER JOIN usuario as u ON u.id_usuario = ap.id_usuario
                WHERE p.id_usuario = $id_usuario
                GROUP BY 1
                ORDER BY 1, 2;
                ";
                $result = $conn->query($sql);








            $html .= "<br><br><br><br>";

            $tabela_verso_palestras = "
                <table class='table' border = 1>
                <thead>
                    <tr>
                        <th>PALESTRA-TEMA</th>
                        <th>PALESTRANTE</th>
                        <th>DATA</th>
                    </tr>
                </thead>
                <tbody>";
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $tabela_verso_palestras .= "                   
                        <tr>
                        <td> " . $row['nome_atividade'] . "</td>
                        <td> " . str_replace(',','<br>',$row['nomes_palestrantes']) . "</td>
                        <td> " . $row['data_atividade'] . "<br> " . dia_semana_extenso($row['data_hora_inicio']) . "</td>
                        
                    </tr>    ";               
                    }
                }      
                
                $tabela_verso_palestras .= "                    
                    </tbody>
                </table>";

        //    $html .= $tabela_verso_palestras;
            $html .= "<div align='center' style='font-size:20px'>
                $tabela_verso_palestras
                </div>";

            $html .= "<br><br><br>";

            $html .= "<div align='right' style='font-size:20px'>
                <b>Regulamento da $nome_evento: 
                <br>Certificado segue a carga horária registrada nos 
                <br>sistemas de presença.</b>
                </div>";

            $html .= "<br><br><br><br><br>";

            $html .= "<div align='center' style='font-size:15px'>
                Para conferir a veracidade deste certificado, acesse o link https://eventos.faosc.edu.br/verificacao e informe o código abaixo:<br>
                    $codigo_de_validacao
                </div>";

            $dompdf->loadHtml($html);

        //    $dompdf->set_option('defaultFont', 'sans');
        
            $dompdf->setPaper('A4','landscape'); // portrait
        
            $dompdf->render();
        
        //    $dompdf->stream(); // deixando isso vai fazer o download diretamente para o usuário
        
            header('Content-type: application/pdf'); // assim vai mostrar o pdf na tela
            echo $dompdf->output();
    }
} else {
    
    $sql = "SELECT a.id_evento, e.nome_evento FROM presenca as p
            INNER JOIN atividade as a USING(id_atividade)
            INNER JOIN evento as e USING(id_evento)
            WHERE a.id_evento not in(
                select id_evento from certificado where id_usuario = $id_usuario
                )
            GROUP BY 1;
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Veja abaixo os eventos que você pode gerar seu certificado:<br>";
        while ($row = $result->fetch_assoc()) {
            echo "<br>" . $row['nome_evento'] . "<a href='/eventfaosc/participante/certificado2.php?gerar=1&evento=" . $row['id_evento'] . "'>Gerar certificado</a>";
        }
    } 



    $sql2 = "SELECT c.id_certificado, e.nome_evento
        FROM certificado as c 
        INNER JOIN evento as e USING(id_evento)
        where id_usuario = $id_usuario
        order by 1 asc;
        ";
    $result2 = $conn->query($sql2);

    if ($result2->num_rows > 0) {
        echo "Veja abaixo os certificados disponíveis:<br>";
        while ($row = $result2->fetch_assoc()) {
            echo "<br><a href='/eventfaosc/participante/certificado2.php?visualizar=" . $row['id_certificado'] . "'>" . $row['nome_evento'] . "</a>";
        }
    } 
}






//    echo $html;

    function gravar_informacoes_certificado($conn, $id_evento, $id_participante){

        $sql_test = "select * from certificado where id_evento = $id_evento and id_usuario = $id_participante;";
        $result2 = $conn->query($sql_test);
        if ($result2->num_rows > 0) {
            return "Você já possui um certificado cadastrado para esse evento.";
        }

        $date = date("d/m/Y H:i:s");

        $cod_validacao = strtoupper(substr(md5($date), 0, 9));
        $sql = "INSERT INTO certificado (
            id_evento, 
            id_usuario,
            nome_completo,
            data_emissao,
            carga_horaria_certificado,
            id_assinatura_certificado,
            codigo_de_validacao
            )
        VALUES (            
            $id_evento,
            $id_participante,
            (select concat(nome, ' ', sobrenome) from usuario where id_usuario = $id_participante),
            TIMESTAMPADD(HOUR, -3, NOW()),
            (SELECT SUM(a.carga_horaria) as ch_total FROM presenca as p
                INNER JOIN atividade as a ON DATE_FORMAT(p.data_horario_entrada, '%Y-%m-%d') = DATE_FORMAT(a.data_hora_inicio, '%Y-%m-%d')
                WHERE p.id_usuario = $id_participante),
            1,
            '$cod_validacao'
        );";
        $result = $conn->query($sql);
        if ($result == TRUE) {
            $msg_alerta =  "Certificado gerado com sucesso.";
        }else{
            $msg_alerta =  "Erro ao gerar certificado - contate o administrador do sistema.";
        } 

        return $msg_alerta;

    }

    function dia_semana_extenso($data){
        // Array com os dias da semana
        $diasemana = array('Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado');

        // Variável que recebe o dia da semana (0 = domingo, 1 = segunda, 2 = terca ...)
        $diasemana_numero = date('w', strtotime($data));

//        echo "diasemana_numero =" . $diasemana_numero . " " . $diasemana[$diasemana_numero];

        // Mostra o dia da semana
        return $diasemana[$diasemana_numero];
    }
?>
