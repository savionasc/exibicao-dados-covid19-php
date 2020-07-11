<?php
	include 'conexao.php';

	$passou = false;

	if(isset($_GET['p'])){
		$valor = $_GET["p"];
		$passou = true;
	}
?>
<html>
    <head>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Cidade', 'Casos'],
                    <?php
                    $sql = "SELECT * FROM dados_covid19 where city_ibge_code = ";
                    $sql .= (($passou) ? $valor : 3550308);
                    $buscar = mysqli_query($conexao,$sql);

                    while($dados = mysqli_fetch_array($buscar)){
                        $cidade = $dados['date'];
                        $casos = $dados['last_available_confirmed'];
                        $nomeCidade = $dados['city'];
                    ?>
                    ['<?php echo $cidade ?>', <?php echo $casos ?>],

                    <?php } ?>
                ]);
                
                var options = {
                    title: 'Número de casos de covid19 na cidade de <?php echo $nomeCidade ?>',
                    legend: {position: 'right'}
                };

                var chart = new google.visualization.LineChart(document.getElementById('graficoLinha'));
                chart.draw(data, options);
            }
        </script>
    </head>
    <body>
        <div id="graficoLinha" style="width: 900px; height: 500px;"></div>
        <ul>
        	<li><a href="./?p=3550308">São Paulo</a></li>
        	<li><a href="./?p=2910800">Feira de Santana</a></li>
			<li><a href="./?p=3300407">Barra Mansa:</a> </li>
        	<li><a href="./?p=2311306">Quixadá</a></li>
        	<li><a href="./?p=2304400">Fortaleza:</a> </li>
        	
        </ul>
    </body>
</html>