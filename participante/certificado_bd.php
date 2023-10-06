
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

        $sql = "SELECT ia.id_inscricao_alteracao_status_registro_acesso, c.nome_completo, c.carga_horaria_certificado, c.cpf, CONVERT(c.data_emissao, DATE) as data_emissao, 
		e.nome_evento, e.id_evento, ac.nome_assinatura, ac.funcao_pessoa_assinatura, c.codigo_de_validacao, (   
                select id_status_pagamento from inscricao_alteracao_status_registro_acesso where id_inscricao_alteracao_status_registro_acesso = (
                    select MAX(id_inscricao_alteracao_status_registro_acesso) from inscricao_alteracao_status_registro_acesso ia WHERE ia.id_inscricao = ie.id_inscricao
                    )
                ) as status_pagamento
                FROM certificado as c 
                INNER JOIN evento as e ON e.id_evento = c.id_evento
                INNER JOIN inscricao_evento as ie ON ie.id_usuario = c.id_usuario
                INNER JOIN inscricao_alteracao_status_registro_acesso as ia ON ia.id_inscricao = ie.id_inscricao
                INNER JOIN assinatura_certificado as ac ON ac.id_assinatura_certificado = c.id_assinatura_certificado
                where c.id_certificado = $certificado_buscar and c.id_usuario = $id_usuario
                order by 1 desc
                limit 1";
                $result = $conn->query($sql);

        $falta_pagamento = true;
	    
	    $formatter = new IntlDateFormatter(
	     'pt_BR'
	     ,IntlDateFormatter::FULL
	     ,IntlDateFormatter::NONE
	     ,'America/Sao_Paulo'       
	     ,IntlDateFormatter::GREGORIAN
	     ,"dd 'de' MMMM 'de' YYYY"
	   );
	    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (($row['status_pagamento'] == 3) or ($row['status_pagamento'] == 4)){ // só recebe certificado quem tiver pago ou for isento
                    $falta_pagamento = false;
                    $nome_completo = $row['nome_completo'];
                    $numero_doc = mask($row['cpf'], '###.###.###-##');
                    $qtd_horas = $row['carga_horaria_certificado'];
                    $nome_evento = $row['nome_evento'];			
                    $id_evento = $row['id_evento'];
			
					$dateTime = new DateTime($row['data_emissao']);
					$data_certificado = utf8_encode($formatter->format($dateTime));
			
                    $nome_assinatura_diploma = $row['nome_assinatura'];
                    $cargo_assinatura_diploma = $row['funcao_pessoa_assinatura'];  
                    $codigo_de_validacao = $row['codigo_de_validacao'];               
                } else {
                    $falta_pagamento = true;
                }            
            }
		
            if ($falta_pagamento){
                ?>
                <div class="row-12" style="margin-top:5%; text-align: center;"> 
                    <h4> Você não realizou o pagamento da inscrição e por isso não tem direito ao certificado de participação! </h4>
                </div>
                <?php
			} else {
				$dompdf = new Dompdf();

				$html = '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
			    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
			    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
			    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
			    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>';

                $sql = "SELECT *
                    FROM estilo_certificado as ec 
                    where id_evento = $id_evento
                    order by 1 desc
                    limit 1";
                    $result = $conn->query($sql);
        
                
                if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						$pagina_frente = $row['pagina_frente'];
						$pagina_verso = $row['pagina_verso'];
						$local = $row['local_evento'];
						$data_evento = $row['data_evento'];
						$linha_tabela_verso = $row['linha_tabela_verso'];
						break;
					}
                } 
				$pagina_frente = str_replace("%base_link%", $base_link, $pagina_frente);
				$pagina_frente = str_replace("%nome_evento%", $nome_evento, $pagina_frente);
				$pagina_frente = str_replace("%nome_completo%", $nome_completo, $pagina_frente);
				$pagina_frente = str_replace("%numero_doc%", $numero_doc, $pagina_frente);
				$pagina_frente = str_replace("%data_evento%", $data_evento, $pagina_frente);
				$pagina_frente = str_replace("%qtd_horas%", $qtd_horas, $pagina_frente);
				$pagina_frente = str_replace("%data_certificado%", $data_certificado, $pagina_frente);
				$pagina_frente = str_replace("%local%", $local, $pagina_frente);
				$pagina_frente = str_replace("%nome_assinatura_diploma%", $nome_assinatura_diploma, $pagina_frente);
				$pagina_frente = str_replace("%cargo_assinatura_diploma%", $cargo_assinatura_diploma, $pagina_frente);
			//	echo $pagina_frente;

				$quebra_de_pagina = "<div style='page-break-after: always;'></div>";
				$nome_atividades = [];
			//	$local = "Palmitos/SC"; // deve pegar pelo endereço do evento

				$html .= $pagina_frente;

				if (strlen($pagina_verso) > 0){
					$html .= $quebra_de_pagina;
				
					$sql = "SELECT 
					DATE_FORMAT(a.data_hora_inicio, '%d/%m/%Y') as data_atividade, 
					a.nome_atividade, 
					a.data_hora_inicio, 
					(
						SELECT GROUP_CONCAT(
						concat(nome, ' ', sobrenome)
						) 
						from usuario as u2 
						INNER JOIN atividade_palestrante as ap2 on ap2.id_usuario = u2.id_usuario 
						WHERE 
						a.id_atividade = ap2.id_atividade 
						and ap2.id_atividade = ap.id_atividade
					) as nomes_palestrantes 
					FROM presenca as p 
					INNER JOIN atividade as a ON DATE_FORMAT(
						p.data_horario_entrada, '%Y-%m-%d'
					) = DATE_FORMAT(a.data_hora_inicio, '%Y-%m-%d') 
					LEFT JOIN atividade_palestrante as ap ON ap.id_atividade = a.id_atividade 
					LEFT JOIN usuario as u ON u.id_usuario = ap.id_usuario 
					WHERE p.id_usuario = $id_usuario and a.id_evento = $id_evento
					GROUP BY 1, 
					a.nome_atividade, 
					a.data_hora_inicio, 
					a.id_atividade 
					ORDER BY 1, 2;";

					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						$linhas_tabela_verso = "";
						while ($row = $result->fetch_assoc()) {
							$linha = $linha_tabela_verso;
							$nomes_palestrantes = array_unique(explode(",", $row['nomes_palestrantes']));
							$palestrantes = "";
							foreach ($nomes_palestrantes as $nome_palestrante) {
								$palestrantes .= $nome_palestrante . "<br>";
							}
							$nome_atividade = $row['nome_atividade'];
							$data_atividade = $row['data_atividade'];
							$dia_semana_extenso = dia_semana_extenso($row['data_hora_inicio']);	

							$linha = str_replace("%nome_atividade%", $nome_atividade, $linha);
							$linha = str_replace("%palestrantes%", $palestrantes, $linha);
							$linha = str_replace("%data_atividade%", $data_atividade, $linha);
							$linha = str_replace("%dia_semana_extenso%", $dia_semana_extenso, $linha);
							$linhas_tabela_verso .= $linha;
						}
					}  
					
					$pagina_verso = str_replace("%base_link%", $base_link, $pagina_verso);
					$pagina_verso = str_replace("%codigo_de_validacao%", $codigo_de_validacao, $pagina_verso);
					$pagina_verso = str_replace("%nome_evento%", $nome_evento, $pagina_verso);
					$pagina_verso = str_replace("%linha_tabela_verso%", $linhas_tabela_verso, $pagina_verso);

					$html .= $pagina_verso;

				}
				$dompdf->loadHtml($html);

			//    $dompdf->set_option('defaultFont', 'sans');
				
    			$dompdf->set_option('isRemoteEnabled', TRUE);
				$dompdf->setPaper('A4','landscape'); // portrait
			
				$dompdf->render();
			
			//    $dompdf->stream(); // deixando isso vai fazer o download diretamente para o usuário
			
				header('Content-type: application/pdf'); // assim vai mostrar o pdf na tela
				echo $dompdf->output();

//			    echo $html;
		}
	} else {
        echo "Você não tem permissão para acessar este recurso!";
	}
} else {
    
    $sql = "SELECT a.id_evento, e.nome_evento FROM presenca as p
            INNER JOIN atividade as a USING(id_atividade)
            INNER JOIN evento as e USING(id_evento)
            WHERE a.id_evento not in(
                select id_evento from certificado where id_usuario = $id_usuario
                ) and id_usuario = $id_usuario e.permite_gerar_certificado = 1
            GROUP BY 1;
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo " <div class='container'> Veja abaixo os eventos que você pode gerar seu certificado:<br>";
        while ($row = $result->fetch_assoc()) {
            echo "<br>" . $row['nome_evento'] . "<a href='" . $base_link . "participante/certificado.php?gerar=1&evento=" . $row['id_evento'] . "'>Gerar certificado</a> </div>";
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
    	echo "<div class='container'>";
        echo "<h4>Veja abaixo os certificados disponíveis:<br></h4>";
        while ($row = $result2->fetch_assoc()) {
            echo "<h3><br><a href='" . $base_link . "participante/certificado.php?visualizar=" . $row['id_certificado'] . "'>" . $row['nome_evento'] . "</a> </h3>";
        }
        echo "</div>";
    } else {
		?>
		<div class="row-12" style="margin-top:5%; text-align: center;">
			<h4> Nenhum certificado encontrado. </h4>
		</div>
		<?php
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
            cpf,
            data_emissao,
            carga_horaria_certificado,
            id_assinatura_certificado,
            codigo_de_validacao
            )
        VALUES (            
            $id_evento,
            $id_participante,
            (select concat(nome, ' ', sobrenome) from usuario where id_usuario = $id_participante),
            (select numero_documento from usuario where id_usuario = $id_participante),
            TIMESTAMPADD(HOUR, -3, NOW()),
            (SELECT SUM(a.carga_horaria) as ch_total FROM presenca as p
                INNER JOIN atividade as a ON DATE_FORMAT(p.data_horario_entrada, '%Y-%m-%d') = DATE_FORMAT(a.data_hora_inicio, '%Y-%m-%d')
                WHERE p.id_usuario = $id_participante and a.id_evento = $id_evento),
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

	function mask($val, $mask) {
	    $maskared = '';
	    $k = 0;
	    for ($i = 0; $i <= strlen($mask) - 1; ++$i) {
		if ($mask[$i] == '#') {
		    if (isset($val[$k])) {
			$maskared .= $val[$k++];
		    }
		} else {
		    if (isset($mask[$i])) {
			$maskared .= $mask[$i];
		    }
		}
	    }

	    return $maskared;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
	<title>Meus certificados</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>


</head>
<body>
        <div class="container row-12" style="margin-top:2%; text-align: center;">


            <a class="btn btn-secondary  mb-4" href="../painel.php">Retornar</a> 
      </div>
      
    </body>
</html>
