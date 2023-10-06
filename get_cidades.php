<?php
include"./db.php";
$cod_estados = $_GET['cod_estados'];

$cidades = array();

$sql = "SELECT id_municipio, nome
    FROM municipio
    WHERE UF='$cod_estados'
    ORDER BY nome";
$res = $conn->query( $sql );
while ( $row = $res->fetch_assoc( ) ) {
  $cidades[] = array(
    'cod_cidades'  => $row['id_municipio'],
    'nome'      => $row['nome'],
  );
}

echo( json_encode( $cidades ) );
?>