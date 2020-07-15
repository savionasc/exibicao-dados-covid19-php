<?php  
//$connect = mysqli_connect("localhost", "root", "", "test");
$user = 'root';
$password = '';

if(isset($_POST["limpar_banco"]) == "LimparBanco"){
  try {
    $pdo = new PDO('mysql:host=localhost:3306;dbname=comp_urbana', $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     
    $stmt = $pdo->prepare('TRUNCATE TABLE covid_mobilidade');
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
       
      $stmt = $pdo->prepare('INSERT INTO covid_mobilidade (country_region_code,country_region,sub_region_1,sub_region_2,iso_3166_2_code,census_fips_code,date,retail_and_recreation_percent_change_from_baseline,grocery_and_pharmacy_percent_change_from_baseline,parks_percent_change_from_baseline,transit_stations_percent_change_from_baseline,workplaces_percent_change_from_baseline,residential_percent_change_from_baseline
) VALUES(:country_region_code,:country_region,:sub_region_1,:sub_region_2,:iso_3166_2_code,:census_fips_code,:date,:retail_and_recreation_percent_change_from_baseline,:grocery_and_pharmacy_percent_change_from_baseline,:parks_percent_change_from_baseline,:transit_stations_percent_change_from_baseline,:workplaces_percent_change_from_baseline,:residential_percent_change_from_baseline
)');
      $stmt->execute(array(
        ':country_region_code' => $data[0],
        ':country_region' => $data[1],
        ':sub_region_1' => $data[2],
        ':sub_region_2' => $data[3],
        ':iso_3166_2_code' => $data[4],
        ':census_fips_code' => $data[5],
        ':date' => $data[6],
        ':retail_and_recreation_percent_change_from_baseline' => $data[7],
        ':grocery_and_pharmacy_percent_change_from_baseline' => $data[8],
        ':parks_percent_change_from_baseline' => $data[9],
        ':transit_stations_percent_change_from_baseline' => $data[10],
        ':workplaces_percent_change_from_baseline' => $data[11],
        ':residential_percent_change_from_baseline' => $data[12]
      )); 
      echo $stmt->rowCount(); 
    } catch (PDOException $e) {
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