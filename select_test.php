<?php
	include './DB/conexao.php';
?>
<!DOCTYPE html>
<html>
<body>

<form action="/action_page.php">
  <label for="cars">Escolha um estado</label>
  <select name="cars" id="cars" onchange="myFunction()">
  	<option value="0">Escolha</option>
  <?php
    $sql = "SELECT estado FROM lista_cidade_estado where cod_cidade < 100 order by estado";
    $buscar = mysqli_query($conexao,$sql);
    while($dados = mysqli_fetch_array($buscar)){
        $estado = $dados['estado'];
    ?>
    <option value="<?php echo $estado ?>"><?php echo $estado ?></option>
    <?php } ?>
  </select>
  <br><br>
  <input type="submit" value="Submit">

  <script>
function myFunction() {
	//alert("Teste!");
  <?php
  	echo "teste";
  ?>
}
</script>
</form>
</body>
</html>
