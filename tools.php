<?php

function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

function verifica_CPF($cpf) {
    // Extrai somente os números
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return FALSE;
    }
    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return FALSE;
    }
    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return FALSE;
        }
    }
    return TRUE;
    }

function checkNumbRangeNasc($id_nacionalidade) {
    $id_nacionalidade_int = intval($id_nacionalidade);
    // Check if the integer value falls between 1 and 210
    if (($id_nacionalidade_int >= 1) && ($id_nacionalidade_int <= 210)) {
        return TRUE;
    }
    else {
        // Do something else if the value is outside of the range
        return FALSE;
    }
}
    
function checkNumbRangeEstu($id_estud_faosc) {
    $id_estud_faosc_int = intval($id_estud_faosc);
    // Check if the integer value falls between 1 and 3
    if ($id_estud_faosc_int >= 1 && $id_estud_faosc_int < 4) {
        return TRUE;
    }
    else {
        // Do something else if the value is outside of the range
        return FALSE;
    }
}

function usuario_ja_existe_email($email){
    include('db.php');
    $query = "SELECT * from usuario 
              WHERE email = '$email';";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

/*SELECT * FROM `usuario`  
ORDER BY `usuario`.`numero_documento` ASC*/

function usuario_ja_existe_numero_documento($numero_documento){
    include('db.php');
    $query = "SELECT numero_documento from usuario 
              WHERE numero_documento = '$numero_documento';";
    $result = $conn->query($query);
    //print_r($result);
    if ($result->num_rows > 0) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

    
?>