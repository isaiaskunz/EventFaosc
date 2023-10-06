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
    include "../db.php";
    if ((isset($_SESSION['admin'])) and (isset($_SESSION['id']))){
		$id_usuario = $_SESSION['id'];
		$erro = false;

		if ((isset($_POST['id_inscricao'])) and (isset($_POST['novo_status']))) {
			$id_inscricao = $_POST['id_inscricao'];
			$novo_status = $_POST['novo_status'];

			if (!isset($_SESSION['id_registro_acesso'])){
				$ip_remoto = $_SERVER["REMOTE_ADDR"];
				$info_navegador = $_SERVER['HTTP_USER_AGENT'];
				$sql = "INSERT INTO registro_acesso (
                    id_usuario, 
                    ip, 
                    navegador,
					data_horario_acesso) 
                VALUES (
                    '$id_usuario',
                    '$ip_remoto',
					'$info_navegador',
                    NOW())";
                $result = $conn->query($sql);   

                if ($result == TRUE) {
					$sql = "SELECT max(id_registro_acesso) as id_registro_acesso from registro_acesso";
					$result = mysqli_query($conn, $sql);

					if (mysqli_num_rows($result) === 1) {
						$row = mysqli_fetch_assoc($result);
//						echo "id_registro_acesso= " . $row['id_registro_acesso'];
						$_SESSION['id_registro_acesso'] = $row['id_registro_acesso'];
					} else {
						$erro = true;;
					}                  
                } else {
                    $erro = true;
                }
			} 
			

			if (isset($_SESSION['id_registro_acesso'])){

				if ($novo_status == "Aprovado"){
					$id_status_pagamento = 3;
				} else if ($novo_status == "Isento"){
					$id_status_pagamento = 4;
				} else if ($novo_status == "Recusado"){
					$id_status_pagamento = 5;
				}
				if (!$erro){
					$id_registro_acesso = $_SESSION['id_registro_acesso'];
					$sql = "INSERT INTO inscricao_alteracao_status_registro_acesso (
						id_status_pagamento, 
						id_inscricao, 
						id_registro_acesso,
						data_horario_alteracao_status) 
					VALUES (
						'$id_status_pagamento',
						'$id_inscricao',
						'$id_registro_acesso',
						NOW())";
					
					$result = $conn->query($sql);            
					if ($result == TRUE) {
						echo $id_status_pagamento;
					}
				}
			}
			exit(); // aqui finaliza a parte de interação com o ajax
		} else {
    			include '../header.php';
		}




// SQL utilizado abaixo para montar a tabela
/*		$sql = "SELECT ie.id_inscricao, u.nome, u.sobrenome, e.nome_evento, fp.descricao_forma_pagamento, sp.descricao_status_pagamento, ie.arquivo_comprovante
		FROM inscricao_evento as ie
		INNER JOIN usuario as u using(id_usuario)
		INNER JOIN evento as e using(id_evento)
		INNER JOIN forma_pagamento as fp using(id_forma_pagamento)
		LEFT JOIN inscricao_alteracao_status_registro_acesso using(id_inscricao)
		LEFT JOIN status_pagamento as sp using(id_status_pagamento)
		order by 1 asc"; */

$sql = "SELECT ie.id_inscricao, u.nome, u.sobrenome, e.nome_evento, fp.descricao_forma_pagamento, ie.arquivo_comprovante, (   
			select descricao_status_pagamento from status_pagamento where id_status_pagamento = (
				select id_status_pagamento from inscricao_alteracao_status_registro_acesso where id_inscricao_alteracao_status_registro_acesso = (
					select MAX(id_inscricao_alteracao_status_registro_acesso) from inscricao_alteracao_status_registro_acesso ia WHERE ia.id_inscricao = ie.id_inscricao
				)
			)
		) as descricao_status_pagamento
		FROM inscricao_evento as ie
		INNER JOIN usuario as u using(id_usuario)
		INNER JOIN evento as e using(id_evento)
		INNER JOIN forma_pagamento as fp using(id_forma_pagamento)
		order by 1 asc";


		$result = $conn->query($sql);
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">	
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
		<title>Confirmação Pagamento</title>

	    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
	    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
	</head>
	<body>

        <div class="container row-12" style="margin-top:2%">

            <div class="row-12">

                <table class="table">
                    <thead>
                        <tr>
                            <th>Evento</th>
                            <th>Nome</th>
                            <th>Forma pagamento</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                            if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td><?php echo $row['nome_evento']; ?></td>
                                    <td><?php echo $row['nome'] . " " . $row['sobrenome']; ?></td>
                                    <td><?php echo $row['descricao_forma_pagamento']; ?></td>
                                    <td id="status_<?php echo $row['id_inscricao']; ?>"><b> <?php echo (strlen($row['descricao_status_pagamento'])>2) ? $row['descricao_status_pagamento'] : "?"; ?> </b> </td>
                                    <td>
									<?php if (strlen($row['arquivo_comprovante'])>2){ ?>
										<div>
											<button class="btn btn-info"  type="submit" onclick="mostrar_comprovante('<?php echo $row['arquivo_comprovante']; ?>')">
												Visualizar comprovante
											</button>
											<?php } else { echo "Sem comprovante"; } ?>&nbsp;
										
											<button class="btn btn-success" type="submit" onclick="alterar_status_pagamento(<?php echo $row['id_inscricao']; ?>, 'Aprovado')"> 
												Aprovar
											</button>&nbsp; ou

											<button class="btn btn-danger" type="submit" onclick="alterar_status_pagamento(<?php echo $row['id_inscricao']; ?>, 'Recusado')">
												Recusar inscrição
											</button>&nbsp;

											<button class="btn btn-warning" type="submit" onclick="alterar_status_pagamento(<?php echo $row['id_inscricao']; ?>, 'Isento')">
													Isento
											</button>&nbsp;
										</div>
                                    </td>
                                </tr>                       

                        <?php 
                            }
                        }
                        ?>                
                    </tbody>
                </table>
            </div>
            <div class="text-center">
            	<a class="btn btn-secondary mb-4" href="../painel.php">Retornar ao Painel</a>
            </div>
      </div>
      
    </body>
</html>

<script>

	var base_url = window.location.origin;
	if (base_url.search("localhost") > 0){
		base_url += "/eventfaosc";
	}
	console.log(base_url);

	function alterar_status_pagamento(id_inscricao, novo_status){
		$.ajax({
			url: base_url+"/adm/conf_pagamentos.php",
			type: "POST",
			data: "id_inscricao="+id_inscricao+"&novo_status="+novo_status,
			dataType: "html"
		}).done(function(resposta) {
			console.log(resposta);
	//		mostrar_texto_na_div_resultados_nomes(resposta);
			alterar_status_na_tela(id_inscricao, resposta);
		}).fail(function(jqXHR, textStatus ) {
			console.log("Request failed: " + textStatus);
		}).always(function() {
			console.log("completou");
		}); 
	}

	function alterar_status_na_tela(id_inscricao, id_status_pagamento){
		novo_status = "?";
		if (id_status_pagamento == 3) {
			novo_status = "Pagamento aceito";
		} else if (id_status_pagamento == 4) {
			novo_status = "Isento";
		} else if (id_status_pagamento == 5) {
			novo_status = "Pagamento recusado";
		}
		document.getElementById("status_"+id_inscricao).innerHTML = novo_status;
	}

	function mostrar_comprovante(nome_arquivo){
		var add = document.createElement('div');

		add.id = "pop";

		add.style.zIndex = "999";

		add.style.display = "block";

		add.style.position = "fixed";

		add.style.top = "0%";

		add.style.left = "0%";

		add.style.width = "100%";

		add.style.height = "100%";

		document.body.appendChild(add);   

		document.getElementById("pop").innerHTML = [

			'<div style="display: block;position: fixed;top: 25%;left: 25%;margin-left: -150px;margin-top: -100px;padding: 10px;width: 300px;height: 200px;">' +

			'<span style="float:right;"><a href="javascript:(function(){document.getElementById(\'pop\').style.display=\'none\';}());">[Fechar]</a></span>' +

			'<br><img src="../participante/comprovantes_pagamento/'+nome_arquivo+'"><br>' +

			'</div>'

		];
	}
</script>
