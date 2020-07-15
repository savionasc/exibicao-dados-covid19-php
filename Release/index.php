<!doctype html>
<?php
    include 'conexao.php';

    $passou = false;
    $est = "SP";
    $valor = 3550308;
    if(isset($_GET['p'])){
        $valor = $_GET["p"];
        $passou = true;
    }

    $query = "SELECT * FROM lista_cidade_estado WHERE tipo = 'state'  ORDER BY cidade ASC"; 
    $result = $conexao->query($query); 
?>

<html lang="pt">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- <link rel="icon" href="favicon.ico"> -->

    <title>Análises COVID-19</title>

    <!-- Bootstrap core CSS -->
    <link href="./bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./bootstrap/dashboard.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> <!-- do select -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!-- do gráfico -->
    <script type="text/javascript"> //do gráfico
        google.charts.load('current', {'packages':['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawChart);
        google.charts.setOnLoadCallback(drawStuff);
        google.charts.setOnLoadCallback(drawChart2);

        //Grafico principal
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Cidade', 'Casos'],
                <?php
                $sql = "SELECT * FROM dados_covid19 where city_ibge_code = ";
                $sql .= (($passou) ? $valor : 3550308);
                $buscar = mysqli_query($conexao,$sql);

                while($dados = mysqli_fetch_array($buscar)){
                    $cidade = explode("-",$dados["date"]);
                    $casos = $dados['last_available_confirmed'];
                    $nomeCidade = $dados['city'];
                    $est = $dados['state'];
                ?>
                ['<?php echo $cidade[2];?>/<?php echo $cidade[1];?>', <?php echo $casos ?>],

                <?php } ?>
            ]);
            
            var options = {
                title: 'Número de casos de covid19 na cidade de <?php echo $nomeCidade ?>',
                legend: {position: 'right'}
            };

            var chart = new google.visualization.LineChart(document.getElementById('graficoLinha'));
            chart.draw(data, options);
        }

        //Grafico secundário - top 10

      function drawStuff() {

        var chartDiv = document.getElementById('chart_div');

        var data = google.visualization.arrayToDataTable([
          ['Data', 'novos casos'],
          <?php
            $sql = "SELECT `city`,`date`,`new_confirmed` FROM `dados_covid19` WHERE `city_ibge_code` = ";
            $sql .= (($passou) ? $valor : 3550308);
            $buscar = mysqli_query($conexao,$sql);

            while($dados = mysqli_fetch_array($buscar)){
                $res = explode("-",$dados["date"]);
          ?>
          ['<?php echo $res[2];?>/<?php echo $res[1];?>', <?php echo $dados["new_confirmed"]; ?>],
          <?php } ?>
        ]);

        var materialOptions = {
          title: 'Número de casos de covid19 na cidade de <?php echo $nomeCidade ?>',
          legend: {position: 'right'}
        };

        var classicOptions = {
          //height: 650,
          series: {
            0: {targetAxisIndex: 0},
            1: {targetAxisIndex: 1}
          },
          title: 'Nearby galaxies - distance on the left, brightness on the right'
        };

        function drawClassicChart() {
          var classicChart = new google.visualization.ColumnChart(chartDiv);
          classicChart.draw(data, classicOptions);
        }

        drawClassicChart();
    };
    function drawChart2() {
            var data = google.visualization.arrayToDataTable([
                ['Cidade', 'Casos'],
                <?php
                $sql = "SELECT * FROM dados_covid19 where city_ibge_code = ";
                $sql .= (($passou) ? $valor : 3550308);
                $buscar = mysqli_query($conexao,$sql);

                while($dados = mysqli_fetch_array($buscar)){
                    $cidade = explode("-",$dados["date"]);
                    $casos = $dados['new_confirmed'];
                    $nomeCidade = $dados['city'];
                    $est = $dados['state'];
                ?>
                ['<?php echo $cidade[2];?>/<?php echo $cidade[1];?>', <?php echo $casos ?>],

                <?php } ?>
            ]);
            
            var options = {
                title: 'Número de casos de covid19 na cidade de <?php echo $nomeCidade ?>',
                legend: {position: 'right'}
            };

            var chart = new google.visualization.LineChart(document.getElementById('graficoLinha2'));
            chart.draw(data, options);
        }
    </script>
  </head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Análises COVID-19</a>
      <!-- <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search"> -->
      <!--<ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="#">Sign out</a>
        </li>
      </ul>-->
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="#inicio">
                  <span data-feather="home"></span>
                  Inicio <span class="sr-only">(current)</span>
                </a>
              </li>
              <!--<li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="users"></span>
                  Customers
                </a>
              </li>-->
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="bar-chart-2"></span>
                  Graficos
                </a>
              </li>
              <!--<li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="layers"></span>
                  Integrations
                </a>
              </li>-->
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Saved reports</span>
              <a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
              </a>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="#secao2">
                  <span data-feather="file-text"></span>
                  Current month
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" id="inicio" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Inicio</h1>
            <!--<div class="btn-toolbar mb-2 mb-md-0">
               <div class="btn-group mr-2">
                <button class="btn btn-sm btn-outline-secondary">Share</button>
                <button class="btn btn-sm btn-outline-secondary">Export</button>
              </div> 
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                This week
              </button>
            </div>-->
          </div>
          <!-- <div class="jumbotron">
                <h1>Navbar example</h1>
                <p class="lead">This example is a quick exercise to illustrate how fixed to top navbar works. As you scroll, it will remain fixed to the top of your browser's viewport.</p>
                <a class="btn btn-lg btn-primary" href="../../components/navbar/" role="button">View navbar docs &raquo;</a>
          </div> -->
          <div class="jumbotron">
              <!--<p>Introdução do artigo</p><br /><br /><br /><br /><br /><br /><br /><br /> -->
              <center><div id="graficoLinha" style="width: 90%; height: 500px;"></div></center>
          </div>
          <p><b>Descrição:</b></p>
          <p>Neste gráfico, é possível visualizar... Na horizontal (eixo X) se vê os dias e na vertical (eixo Y) se vê o número de casos confirmados de COVID19.</p>
          <select id="estado">
              <option value="">Selecione o estado</option>
                <?php 
                if($result->num_rows > 0){ 
                    while($row = $result->fetch_assoc()){  
                        echo '<option value="'.$row['estado'].'">'.$row['cidade'].'</option>'; 
                    } 
                }else{ 
                    echo '<option value="">Estado não disponível</option>'; 
                } 
                ?>
          </select>

          <select id="state" onchange="window.location.href = ('./?p='+state.value)">
              <option value="">Primeiro escolha um estado</option>
          </select>
          <br /><br />

          <div id="chart_div" style="width: 100%; height: 500px;"></div>

          <div id="graficoLinha2" style="width: 90%; height: 500px;"></div>
          
          <div class="row">
            <div class="jumbotron col-md-4 offset-md-1 bg-info text-white link-white">
                <p><b>Veja também - cidades do estado</b></p>
                <ul>
                  <?php
                    $sql = "SELECT `cidade`, `cod_cidade`, `estado`  FROM lista_cidade_estado where estado = '".$est."' and tipo = 'city' ORDER BY RAND() LIMIT 5";
                    $buscar = mysqli_query($conexao,$sql);
                    while($dados = mysqli_fetch_array($buscar)){
                    ?>
                    <li>
                        <a class="text-white" href="./?p=<?php echo $dados['cod_cidade']?>"><?php echo $dados['cidade']; ?> (<?php echo $dados['estado']; ?>)
                        </a>
                    </li>
                  <?php } ?>
                </ul>
            </div>
            <div class="jumbotron col-md-4 offset-md-1 bg-info text-white link-white">
              <p><b>Veja também - cidades do país</b></p>
              <ul>
              <?php
                $sql = "SELECT `cidade`, `cod_cidade`, `estado`  FROM lista_cidade_estado where tipo = 'city' ORDER BY RAND() LIMIT 5";
                $buscar = mysqli_query($conexao,$sql);
                while($dados = mysqli_fetch_array($buscar)){
                ?>
                <li><a class="text-white" href="./?p=<?php echo $dados['cod_cidade']?>"><?php echo $dados['cidade']; ?> (<?php echo $dados['estado']; ?>)</a></li>
                <?php } ?>
              </ul>
            </div>
          </div>
          <!-- <ul>
              <li><a href="./?p=3550308">São Paulo</a></li>
              <li><a href="./?p=2910800">Feira de Santana</a></li>
              <li><a href="./?p=3300407">Barra Mansa:</a> </li>
              <li><a href="./?p=2311306">Quixadá</a></li>
              <li><a href="./?p=2304400">Fortaleza:</a> </li>
          </ul> -->
         
          <h2 id="secao2">Cidades com mais casos do estado</h2>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>Estado</th>
                  <th>Casos</th>
                  <th>Mortes</th>
                  <th>Posição no país por Nº de casos</th>
                  <th>Posição no país por Nº de mortes</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "SELECT DISTINCT `city_ibge_code`, ROW_NUMBER() OVER (ORDER BY `last_available_confirmed` DESC) contagem_linha, ROW_NUMBER() OVER (ORDER BY `last_available_deaths` DESC) contagem_linha_mortes, `city`, `last_available_confirmed`, `last_available_deaths` FROM `dados_covid19` WHERE `date` IN (SELECT max(`date`) FROM `dados_covid19`) and `place_type` = 'state' ORDER BY `last_available_confirmed` DESC";
                
                $buscar = mysqli_query($conexao,$sql);

                $ct = 0;

                while($dados = mysqli_fetch_array($buscar)){
                    if($dados['city'] == $est){

                    $city = $dados['city'];
                    $last_available_confirmed = $dados['last_available_confirmed'];
                    $posicao = $dados['contagem_linha'];
                    $posicao_mortes = $dados['contagem_linha_mortes'];
                    $last_available_deaths = $dados['last_available_deaths'];
                ?>
                <tr>
                  <td><?php echo $city; ?></td>
                  <td><?php echo $last_available_confirmed; ?></td>
                  <td><?php echo $last_available_deaths; ?></td>
                  <td><?php echo $posicao; ?>º</td>
                  <td><?php echo $posicao_mortes; ?>º</td>
                </tr>
                <?php } }?>
              </tbody>
              <thead>
                <tr>
                  <th>Cidade</th>
                  <th>Casos</th>
                  <th>Mortes</th>
                  <th>Posição no estado por Nº de casos</th>
                  <th>Posição no estado por Nº de mortes</th>
                </tr>
              </thead>
              <tbody>
                <?php

                $sql = "SELECT DISTINCT `city_ibge_code`, ROW_NUMBER() OVER (ORDER BY `last_available_confirmed` DESC) contagem_linha, ROW_NUMBER() OVER (ORDER BY `last_available_deaths` DESC) contagem_linha_mortes, `city`, `last_available_confirmed`, `last_available_deaths` FROM `dados_covid19` WHERE `date` IN (SELECT max(`date`) FROM `dados_covid19`) and state = '".$est."' and `place_type` = 'city' ORDER BY `last_available_confirmed` DESC";
                
                $buscar = mysqli_query($conexao,$sql);

                $ct = 0;

                while($dados = mysqli_fetch_array($buscar)){
                    if($ct < 10 || $dados['city_ibge_code'] == $valor){
                        $city = $dados['city'];
                        $last_available_confirmed = $dados['last_available_confirmed'];
                        $posicao = $dados['contagem_linha'];
                        $posicao_mortes = $dados['contagem_linha_mortes'];
                        $last_available_deaths = $dados['last_available_deaths'];
                ?>
                <tr>
                  <td><b><?php echo $city ?></b></td>
                  <td><?php echo $last_available_confirmed?></td>
                  <td><?php echo $last_available_deaths?></td>
                  <td><?php echo $posicao?>º</td>
                  <td><?php echo $posicao_mortes?>º</td>
                </tr>
                
                <?php } $ct++; } ?>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>window.jQuery || document.write('<script src="./bootstrap/jquery-3.5.1.js"><\/script>')</script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="./bootstrap/bootstrap.min.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

    <script> //do select
            $(document).ready(function(){
                $('#estado').on('change', function(){
                    var estado = $(this).val();
                    if(estado){
                        $.ajax({
                            type:'POST',
                            url:'ajaxDataCidades.php',
                            data:'estado='+estado,
                            success:function(html){
                                $('#state').html(html);
                            }
                        }); 
                    }else{
                        $('#state').html('<option value="">Select estado first</option>');
                    }
                });
            });
        </script>
  </body>
</html>