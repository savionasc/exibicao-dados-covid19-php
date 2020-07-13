<?php  
$user = 'root';
$password = '';

if(isset($_POST["limpar_banco"]) == "LimparBanco"){
  try {
    $pdo = new PDO('mysql:host=localhost:3306;dbname=comp_urbana', $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     
    $stmt = $pdo->prepare('TRUNCATE TABLE lista_cidade_estado');
    //$stmt->bindParam(':id', $id); 
    $stmt->execute();
       
    echo "Apagado com sucesso, agora a tabela tem ".$stmt->rowCount()." linhas."; 
  } catch(PDOException $e) {
    echo 'Erro ao deletar: ' . $e->getMessage();
  }
}else if(isset($_POST["submit"])){
 if($_FILES['file']['name']){
  $filename = explode(".", $_FILES['file']['name']);
  if($filename[1] == 'csv'){
   $handle = fopen($_FILES['file']['tmp_name'], "r");
   while($data = fgetcsv($handle)){
    try {
      $pdo = new PDO('mysql:host=localhost:3306;dbname=comp_urbana', $user, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       
      $stmt = $pdo->prepare('INSERT INTO lista_cidade_estado (cod_cidade,cidade,estado,tipo) VALUES(:cod_cidade,:cidade,:estado,:tipo)');
        //$escolhido = ($data[2] == "city") ? $data[2] : $data[4];
        $stmt->execute(array(
        ':cod_cidade' => $data[0],
        ':cidade' => ($data[6] == "city") ? $data[2] : $data[4],
        ':estado' => $data[4],
        ':tipo' => $data[6]
      ));
      echo $stmt->rowCount(); 
    } catch(PDOException $e) {
      echo 'Error: ' . $e->getMessage();
    }

    //$item1 = mysqli_real_escape_string($connect, $data[0]);  
    //$item2 = mysqli_real_escape_string($connect, $data[1]);
    //$query = "INSERT into covid19(cidade,casos) values('$item1','$item2')";
    //mysqli_query($connect, $query);
   }
   fclose($handle);
   echo "<script>alert('Importação concluída!');</script>";
  }
 }
}
?>  
<!DOCTYPE html>  
<html>  
 <head>  
  <title>Importando dados</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
 </head>  
 <body>  
  <h3 align="center">Importando dados de CSV para Mysql usando PHP</h3><br />
  <form method="post" enctype="multipart/form-data">
   <div align="center">  
    <label>Selecionar um arquivo CSV:</label>
    <input type="file" name="file" />
    <br />
    <input type="submit" name="submit" value="Import" class="btn btn-info" />
    <input type="submit" name="limpar_banco" value="LimparBanco" class="btn btn-info" />
   </div>
  </form>

  
 </body>  
</html>