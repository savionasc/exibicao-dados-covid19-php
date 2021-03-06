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
    <meta name="description" content="@savionasc">
    <meta name="author" content="Sávio Nascimento">
    <!-- <link rel="icon" href="favicon.ico"> -->

    <title>Análises COVID-19</title>
    <link rel="icon" href="./covid19-icone.png">

    <!-- Bootstrap core CSS -->
    <link href="./bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./bootstrap/dashboard.css" rel="stylesheet">
    <script src="https://cdn.syncfusion.com/ej2/dist/ej2.min.js" type="text/javascript"></script> <!-- Heatmap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> <!-- do select -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!-- do gráfico -->
    <script type="text/javascript"> //do gráfico
        google.charts.load('current', {'packages':['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawChart);
        google.charts.setOnLoadCallback(drawStuff);
        google.charts.setOnLoadCallback(drawChart2);
        google.charts.setOnLoadCallback(mediaMovel);
        google.charts.setOnLoadCallback(scatter);
        

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
                title: 'Número de casos acumulados de covid19 na cidade de <?php echo $nomeCidade ?>',
                legend: {position: 'none'}
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

        var classicOptions = {
          //height: 650,
          series: {
            0: {targetAxisIndex: 0},
            1: {targetAxisIndex: 1}
          },
          title: 'Novos casos de covid19 em <?php echo $nomeCidade ?>',
          legend: {position: 'none'}
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
            legend: {position: 'none'}
        };

        var chart = new google.visualization.LineChart(document.getElementById('graficoLinha2'));
        chart.draw(data, options);
      }

      function mediaMovel() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Novos Casos', 'Média móvel'],
          //['2004/05',  165,      614.6],
        <?php
            //$sql = "SELECT `city`,`date`,`new_confirmed` FROM `dados_covid19` WHERE `city_ibge_code` = ";
            $sql = "select t1.date, t1.new_confirmed, if(count(1)>=7,avg(t2.new_confirmed),0) as 'med_movel', t1.city from dados_covid19 t1 , dados_covid19 t2 where t1.city_ibge_code = '".$valor."' and t2.city_ibge_code = '".$valor."' and t2.date between DATE_SUB(date(t1.date),INTERVAL 6 day)+0 and t1.date and t1.date between 20200305 and 20200707 group by t1.date ,t1.new_confirmed order by date";
            //$sql .= (($passou) ? $valor : 3550308);
            $buscar = mysqli_query($conexao,$sql);

            while($dados = mysqli_fetch_array($buscar)){
                //$res = explode("-",$dados["date"]);
                $ru = $dados["date"];
          ?>
        
          ['<?php echo $ru;?>', <?php echo $dados["new_confirmed"]; ?>, <?php echo $dados["med_movel"]; ?>],
        
          <?php } ?>
          ]);
        var options = {
          title : 'Gráfico de novos casos com média móvel do estado de São Paulo',
          //vAxis: {title: 'Cups'},
          //hAxis: {title: 'Month'},
          seriesType: 'bars',
          series: {1: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('media_movel'));
        chart.draw(data, options);
      }

      function scatter() {
        var data = google.visualization.arrayToDataTable([
          ['Age', 'Weight'],
          <?php
            $sql = "SELECT `new_confirmed` FROM `dados_covid19` WHERE `place_type` = 'state' and `state` = '".$est."' and (date between 20200415 and 20200428) order by date asc";
            $buscar = mysqli_query($conexao,$sql);

            $novos_casos = array();

            while($dados = mysqli_fetch_array($buscar)){
              array_push($novos_casos, $dados["new_confirmed"]);
            }

            $sql = "SELECT `residential_percent_change_from_baseline` FROM `covid_mobilidade` WHERE `est` = '".$est."' and (date between 20200415 and 20200428) order by date asc";
            $buscar = mysqli_query($conexao,$sql);

            $mobilidade = array();

            while($dados = mysqli_fetch_array($buscar)){
              array_push($mobilidade, $dados["residential_percent_change_from_baseline"]);
            }

            for($i = 0; $i < count($mobilidade); ++$i) {
          ?>
          [<?php echo $mobilidade[$i];?>,<?php echo $novos_casos[$i];?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Age vs. Weight comparison',
          hAxis: {title: 'Age', minValue: -30, maxValue: 30},
          vAxis: {title: 'Weight', minValue: 0, maxValue: 15},
          legend: 'none'
        };

        var chart = new google.visualization.ScatterChart(document.getElementById('scatter'));

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
                  Início <span class="sr-only">(current)</span>
                </a>
              </li>
              <!--<li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="users"></span>
                  Customers
                </a>
              </li>-->
              <li class="nav-item">
                <a class="nav-link" href="#chart_div">
                  <span data-feather="bar-chart-2"></span>
                  Gráficos
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#table-responsive">
                  <span data-feather="layers"></span>
                  Tabela
                </a>
              </li>
            </ul>

            <!--<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
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
            </ul>-->
          </div>
        </nav>

        <main role="main" id="inicio" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Início</h1>
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
              <center><div id="graficoLinha" style="width: 100%; height: 550px;"></div></center>
              <p><b>Descrição:</b></p>
              <p>Neste gráfico, é possível visualizar os casos acumulados até cada dia da cidade. Na horizontal (eixo X) se vê os dias e na vertical (eixo Y) se vê o acumulado de casos confirmados de COVID19.</p>
          </div>
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
          <p><b>Descrição (gráfico de barras):</b></p>
          <p>Gráfico de visualização dos novos casos de coronavírus por dia naquela cidade. Na horizontal (eixo X) se vê os dias e na vertical (eixo Y) se vê o número de novos casos confirmados de COVID19.</p>
          <div id="graficoLinha2" style="width: 100%; height: 550px;"></div>
          <p><b>Descrição (gráfico de linha):</b></p>
          <p>Gráfico de visualização dos novos casos de coronavírus por dia naquela cidade. Na horizontal (eixo X) se vê os dias e na vertical (eixo Y) se vê o número de novos casos confirmados de COVID19.</p>

          <div id="media_movel" style="width: 900px; height: 500px;"></div>
          <p><b>Descrição (gráfico de barras com linha):</b></p>
          <p>Gráfico de visualização dos novos casos de coronavírus por dia naquela cidade juntamente com a visualização da média móvel representada no gráfico de linha. Na horizontal (eixo X) se vê os dias e na vertical (eixo Y) se vê o número de novos casos confirmados de COVID19.</p>

          <hr>
          <h3>Dados do estado - <?php echo $est;?></h3>
          <p><b>Mapas de calor:</b></p>
          <p>A seguir encontra-se 3 mapas de calor baseados em dados de mobilidade. O primeiro mapa refere-se ao inicio da pandemia, o segundo aos últimos 30 dias no dataset e por último um mapa de calor referente aos dias da semana.</p>
          <div id="container2">
              <div id="element2"></div>
          </div>

          <div id="container">
              <div id="element"></div>
          </div>
          
          <script type="text/javascript">
            var heatmapData = [
           //[73, 39, 26, 39, 94, 0],
           //[93, 58, 53, 38, 26, 68],
           <?php
            //$sql = "SELECT `city`,`date`,`new_confirmed` FROM `dados_covid19` WHERE `city_ibge_code` = ";
            $sql = "SELECT date,
                    retail_and_recreation_percent_change_from_baseline,
                    grocery_and_pharmacy_percent_change_from_baseline,
                    parks_percent_change_from_baseline,
                    transit_stations_percent_change_from_baseline,
                    workplaces_percent_change_from_baseline,
                    residential_percent_change_from_baseline FROM `covid_mobilidade` WHERE `sub_region_1` = 'State of Ceará' and ( date between 20200301 and 20200330)";
            //$sql .= (($passou) ? $valor : 3550308);
            $buscar = mysqli_query($conexao,$sql);

            while($dados = mysqli_fetch_array($buscar)){
          ?>
        
          [<?php echo $dados["retail_and_recreation_percent_change_from_baseline"];?>,
          <?php echo $dados["grocery_and_pharmacy_percent_change_from_baseline"];?>,
          <?php echo $dados["parks_percent_change_from_baseline"];?>,
          <?php echo $dados["transit_stations_percent_change_from_baseline"];?>,
          <?php echo $dados["workplaces_percent_change_from_baseline"];?>,
          <?php echo $dados["residential_percent_change_from_baseline"];?>],
        
          <?php } ?>
          ];

      var heatmap = new ej.heatmap.HeatMap({
           titleSettings: {
                  text: 'Frequência de ida aos locais no período de 30 dias (em porcentagem de -100% a 100%) - início da quarentena',
                  textStyle: {
                      size: '15px',
                      fontWeight: '500',
                      fontStyle: 'Normal',
                      fontFamily: 'Segoe UI'
                  }
              },
              cellSettings: {
                  showLabel: false,
              },
              paletteSettings: {
                      palette: [
                      { color: '#ffd072ff'},
                      { color: '#D94B18'}
                  ],
                  type: "Gradient"
              },
              xAxis: {
                labels: [
                <?php
                  $sql = "SELECT date FROM `covid_mobilidade` WHERE `sub_region_1` = 'State of Ceará' and (date between 20200225 and 20200229 OR date between 20200301 and 20200324)";
                  //$sql .= (($passou) ? $valor : 3550308);
                  $buscar = mysqli_query($conexao,$sql);

                  while($dados = mysqli_fetch_array($buscar)){
                    $res = explode("-",$dados["date"]);
                ?>
                  '<?php echo $res[2];?>/<?php echo $res[1];?>',
                <?php } ?>],
              },
              yAxis: {
                  labels: ['Varejo','Merc/Farm','Parques','Est. Transp.','Trabalho','Residencias'],
              },
           dataSource: heatmapData, 
      }, '#element2');


      var ele = document.getElementById('container2');
      if(ele) {
          ele.style.visibility = "visible";
      }
          </script>

          <script type="text/javascript">
            var heatmapData = [
           //[73, 39, 26, 39, 94, 0],
           //[93, 58, 53, 38, 26, 68],
           <?php
            //$sql = "SELECT `city`,`date`,`new_confirmed` FROM `dados_covid19` WHERE `city_ibge_code` = ";
            $sql = "SELECT date,
                    retail_and_recreation_percent_change_from_baseline,
                    grocery_and_pharmacy_percent_change_from_baseline,
                    parks_percent_change_from_baseline,
                    transit_stations_percent_change_from_baseline,
                    workplaces_percent_change_from_baseline,
                    residential_percent_change_from_baseline FROM `covid_mobilidade` WHERE `sub_region_1` = 'State of Ceará' and (date between 20200701 and 20200710 OR date between 20200608 and 20200630)";
            //$sql .= (($passou) ? $valor : 3550308);
            $buscar = mysqli_query($conexao,$sql);

            while($dados = mysqli_fetch_array($buscar)){
          ?>
        
          [<?php echo $dados["retail_and_recreation_percent_change_from_baseline"];?>,
          <?php echo $dados["grocery_and_pharmacy_percent_change_from_baseline"];?>,
          <?php echo $dados["parks_percent_change_from_baseline"];?>,
          <?php echo $dados["transit_stations_percent_change_from_baseline"];?>,
          <?php echo $dados["workplaces_percent_change_from_baseline"];?>,
          <?php echo $dados["residential_percent_change_from_baseline"];?>],
        
          <?php } ?>
          ];

      var heatmap = new ej.heatmap.HeatMap({
           titleSettings: {
                  text: 'Frequência de ida aos locais no período de 30 dias (em porcentagem de -100% a 100%) - atualmente na quarentena',
                  textStyle: {
                      size: '15px',
                      fontWeight: '500',
                      fontStyle: 'Normal',
                      fontFamily: 'Segoe UI'
                  }
              },
              cellSettings: {
                  showLabel: false,
              },
              paletteSettings: {
                      palette: [
                      { color: '#ffd072ff'},
                      { color: '#D94B18'}
                  ],
                  type: "Gradient"
              },
              xAxis: {
                labels: [
                <?php
                  $sql = "SELECT date FROM `covid_mobilidade` WHERE `sub_region_1` = 'State of Ceará' and (date between 20200701 and 20200710 OR date between 20200608 and 20200630)";
                  //$sql .= (($passou) ? $valor : 3550308);
                  $buscar = mysqli_query($conexao,$sql);

                  while($dados = mysqli_fetch_array($buscar)){
                    $res = explode("-",$dados["date"]);
                ?>
                  '<?php echo $res[2];?>/<?php echo $res[1];?>',
                <?php } ?>],
              },
              yAxis: {
                  labels: ['Varejo','Merc/Farm','Parques','Est. Transp.','Trabalho','Residencias'],
              },
           dataSource: heatmapData, 
      }, '#element');


      var ele = document.getElementById('container');
      if(ele) {
          ele.style.visibility = "visible";
      }
          </script>

          <div id="heatmapsemana">
              <div id="elementsemana"></div>
          </div>

          <script type="text/javascript">
            var heatmapData = [
           //[73, 39, 26, 39, 94, 0],
           //[93, 58, 53, 38, 26, 68],
           <?php
            //$sql = "SELECT `city`,`date`,`new_confirmed` FROM `dados_covid19` WHERE `city_ibge_code` = ";
            $dias = array("domingo","segunda","terça","quarta","quinta","sexta","sábado");
            $tabelas = array(
              "retail_and_recreation_percent_change_from_baseline",
              "grocery_and_pharmacy_percent_change_from_baseline",
              "parks_percent_change_from_baseline",
              "transit_stations_percent_change_from_baseline",
              "workplaces_percent_change_from_baseline",
              "residential_percent_change_from_baseline");
            $sqlGeral = array();
            
            $cou2 = 0;
            while ($cou2 < 7) { //7 dias da semana
              $cou = 0;
              $sql_dia = array();
              while ($cou < 6) { //6 colunas da tabela
                $sql_temp = "SELECT
                        AVG(dd.`".$tabelas[$cou]."`) as mediana
                      FROM (
                      SELECT d.`".$tabelas[$cou]."`, @rownum:=@rownum+1 as `row_number`, @total_rows:=@rownum
                        FROM `covid_mobilidade` d, (SELECT @rownum:=0) r
                        WHERE d.`dia_semana` = '".$dias[$cou2]."' and d.`est` = '".$est."'  and d.`".$tabelas[$cou]."` is NOT NULL
                        ORDER BY d.`".$tabelas[$cou]."`
                      ) as dd WHERE dd.row_number IN ( FLOOR((@total_rows+1)/2), FLOOR((@total_rows+2)/2))";
                array_push($sql_dia, $sql_temp);
                $cou++;
              }
              array_push($sqlGeral, $sql_dia);
                $cou2++;
            }
            
            $arrayGeral = array();

            $cou2 = 0;
            while ($cou2 < 7) {
              $cou = 0;
              $medianas = array();
              $sql_dia = $sqlGeral[$cou2];
              while ($cou < 6) {
                $buscar = mysqli_query($conexao,$sql_dia[$cou]);

                while($dados = mysqli_fetch_array($buscar)){
                  array_push($medianas, $dados["mediana"]);
                }
                $cou++;
              }
              array_push($arrayGeral, $medianas);
              $cou2++;
            }

            $cou2 = 0;
            while ($cou2 < 7) {
              $cou = 0;
              $medianas_domingo = $arrayGeral[$cou2];
          ?>
        
              [
              <?php
                while ($cou < 6) {
              ?>
              [<?php echo $medianas_domingo[$cou++];?>],
            
              <?php } ?>], <?php  $cou2++; }?>
              ];

            var heatmap = new ej.heatmap.HeatMap({
                 titleSettings: {
                        text: 'Frequência de ida aos locais por dia da semana (em porcentagem de -100% a 100%) - toda a quarentena',
                        textStyle: {
                            size: '15px',
                            fontWeight: '500',
                            fontStyle: 'Normal',
                            fontFamily: 'Segoe UI'
                        }
                    },
                    cellSettings: {
                        showLabel: false,
                    },
                    xAxis: {
                      labels: ['domingo','segunda','terça','quarta','quinta','sexta','sábado'],
                    },
                     /*paletteSettings: {
                              palette: [
                              { color: '#C06C84'},
                              { color: '#6C5B7B'},
                              { color: '#355C7D'}
                          ],
                          type: "Gradient"
                      },*/
                      paletteSettings: {
                              palette: [
                              { color: '#ffd072ff'},
                              { color: '#D94B18'}
                          ],
                          type: "Gradient"
                      },
                    yAxis: {
                        labels: ['Varejo','Merc/Farm','Parques','Est. Transp.','Trabalho','Residencias'],
                    },
                 dataSource: heatmapData, 
            }, '#elementsemana');


            var ele = document.getElementById('heatmapsemana');
            if(ele) {
                ele.style.visibility = "visible";
            }
          </script>
          <p><b>Descrição (gráfico de pontos):</b></p>
          <p>Gráfico de visualização dos novos casos de coronavírus por dia com os dados de mobilidade naquela cidade. Na horizontal (eixo X) se vê variações da mobilidade e na vertical (eixo Y) se vê o número de novos casos confirmados de COVID19.</p>

          <div id="scatter" style="width: 900px; height: 500px;"></div>
          
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
          <div class="table-responsive" id="table-responsive">
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