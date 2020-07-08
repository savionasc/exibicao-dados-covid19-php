<?php  
//$connect = mysqli_connect("localhost", "root", "", "test");
if(isset($_POST["submit"])){
 if($_FILES['file']['name']){
  $filename = explode(".", $_FILES['file']['name']);
  if($filename[1] == 'csv'){
   $handle = fopen($_FILES['file']['tmp_name'], "r");
   while($data = fgetcsv($handle)){
    try {
      $user = 'root';
      $password = '';
      $pdo = new PDO('mysql:host=localhost:3306;dbname=comp_urbana', $user, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       
      $stmt = $pdo->prepare('INSERT INTO dados_covid19 (city,city_ibge_code,date,epidemiological_week,estimated_population_2019,is_last,is_repeated,last_available_confirmed,la_confirmed_per_100k_hab,last_available_date,last_available_death_rate,last_available_deaths,order_for_place,place_type,state,new_confirmed,new_deaths) VALUES(:city,:city_ibge_code,:date,:epidemiological_week,:estimated_population_2019,:is_last,:is_repeated,:last_available_confirmed,:la_confirmed_per_100k_hab,:last_available_date,:last_available_death_rate,:last_available_deaths,:order_for_place,:place_type,:state,:new_confirmed,:new_deaths)');
      $stmt->execute(array(
        ':city' => $data[0],
        ':city_ibge_code' => $data[1],
        ':date' => $data[2],
        ':epidemiological_week' => $data[3],
        ':estimated_population_2019' => $data[4],
        ':is_last' => $data[5],
        ':is_repeated' => $data[6],
        ':last_available_confirmed' => $data[7],
        ':la_confirmed_per_100k_hab' => $data[8],
        ':last_available_date' => $data[9],
        ':last_available_death_rate' => $data[10],
        ':last_available_deaths' => $data[11],
        ':order_for_place' => $data[12],
        ':place_type' => $data[13],
        ':state' => $data[14],
        ':new_confirmed' => $data[15],
        ':new_deaths' => $data[16]
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
   </div>
  </form>
 </body>  
</html>