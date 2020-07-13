<?php 
include_once 'conexao.php'; 
 
if(!empty($_POST["estado"])){ 
    $query = "SELECT * FROM lista_cidade_estado WHERE estado = '".$_POST['estado']."' ORDER BY cidade ASC";
    $result = $conexao->query($query);
     
    if($result->num_rows > 0){
        echo '<option value="">Escolha uma cidade</option>';
        while($row = $result->fetch_assoc()){
            echo '<option value="'.$row['cod_cidade'].'">'.$row['cidade'].'</option>';
        }
    }else{
        echo '<option value="">'.$_POST['estado'].'</option>';
    }
}
?>