<?php
$sql = "SELECT * FROM dados_covid19 where city_ibge_code = ";
//$sql += ();
$passou = true;
$valor = "aaaaa";
$num = ($passou) ? $valor : 3300407;
$sql .= (($passou) ? $valor : 3300407);
echo $sql;