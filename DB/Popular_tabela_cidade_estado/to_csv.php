<?php
include './Release/conexao.php';

 $sql = "SELECT DISTINCT `city_ibge_code`, `city`,`state`,`place_type` FROM `dados_covid19` WHERE 1";
$buscar = mysqli_query($conexao,$sql);

$arquivo = array();
while($dados = mysqli_fetch_array($buscar)){
    //$ibge = $dados['city_ibge_code'];
    //$cidade = $dados['city'];
    //$state = $dados['state'];
    //$place_type = $dados['place_type'];
array_push($arquivo, $dados);
}

/*$lista = array (
    array('aaa', 'bbb', 'ccc', 'dddd'),
    array('123', '456', '789'),
    array('"aaa"', '"bbb"')
);*/

$fp = fopen('arquivo.csv', 'w');

foreach ($arquivo as $linha) {
    fputcsv($fp, $linha);
}

fclose($fp);
?>