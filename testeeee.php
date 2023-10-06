<!DOCTYPE html>
<html>
    <body>
        <?php
            include "./db.php";
            
            $primeira_clausula = true;
            $where = " where ";
            $and = " and ";
            $clausula_forma_pagamento = "";
            $clausula_evento = "";
            $clausula_nome = "";
            if (isset($_POST['id_forma_pagamento']) && ($_POST['id_forma_pagamento'] != 0)){
                $id_forma_pagamento = $_POST['id_forma_pagamento'];                
                $clausula_forma_pagamento = " id_forma_pagamento = $id_forma_pagamento";
            }
            if (isset($_POST['id_evento']) && ($_POST['id_evento'] != 0)){
                $id_evento = $_POST['id_evento'];                
                $clausula_evento = " id_evento = $id_evento";
            }
            if (isset($_POST['id_nome']) && ($_POST['id_nome'] != 0)){
                $id_nome = $_POST['id_nome'];                
                $clausula_nome = '( u.nome like "%'.$id_nome.'%" OR u.sobrenome like "%'.$id_nome.'%")';
            }
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
            INNER JOIN forma_pagamento as fp using(id_forma_pagamento)"; 
            
            if ($clausula_forma_pagamento != ""){
                if ($primeira_clausula){
                    $sql .= $where . $clausula_forma_pagamento;
                    $primeira_clausula = false;
                } else {
                    $sql .= $and . $clausula_forma_pagamento;                    
                }
            }

            if ($clausula_evento != ""){                
                if ($primeira_clausula){
                    $sql .= $where . $clausula_evento;
                    $primeira_clausula = false;
                } else {
                    $sql .= $and . $clausula_evento;
                }
            } 
            if ($clausula_nome != ""){                
                if ($primeira_clausula){
                    $sql .= $where . $clausula_nome;
                    $primeira_clausula = false;
                } else {
                    $sql .= $and . $clausula_nome;
                }
            } 

            echo $sql;
            $result = $conn->query($sql);

            $sql_forma_pagamento = "select * from forma_pagamento;";   
            $result_forma_pagamento = $conn->query($sql_forma_pagamento);
        
            $sql_evento = "select * from evento;";   
            $result_evento = $conn->query($sql_evento);
        ?>

<form name="produto" method="post" action="testeeee.php">
<label name="">Selecione uma forma de pagamento: </label>
    <select name="id_forma_pagamento">
        <option value = "0">Selecione...</option>
        <?php while ($row_fp = $result_forma_pagamento->fetch_assoc()) { ?>
            <option value="<?php echo $row_fp['id_forma_pagamento'] ?>"><?php echo $row_fp['descricao_forma_pagamento'] ?></option>
        <?php } ?>
    </select>

    <label name="">Selecione um evento: </label>
    <select name="id_evento">
        <option value = "0">Selecione...</option>
        <?php while ($row_e = $result_evento->fetch_assoc()) { ?>
            <option value="<?php echo $row_e['id_evento'] ?>"><?php echo $row_e['nome_evento'] ?></option>
        <?php } ?>
    </select>

    <label name="">Filtrar por nome: </label>
    <input type="text" id="id_nome" class="form-control" name="id_nome"/>

    <input type="submit" name="submit" value="Filtrar"/>
</form>

<table class="table" border = "1">
                    <thead>
                        <tr>
                            <th>Evento</th>
                            <th>Nome</th>
                            <th>Forma pagamento</th>
                            <th>Status</th>
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
                                    <td><?php echo $row['descricao_status_pagamento']; ?></td>                                   
                                </tr>     
                        <?php 
                            }
                        }
                        ?>                
                    </tbody>
                </table>
    </body>
</html>