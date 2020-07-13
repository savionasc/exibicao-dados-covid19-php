<!doctype html>
<?php
    include 'conexao.php';

    $passou = false;

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

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="./bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./bootstrap/dashboard.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> <!-- do select -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!-- do gráfico -->
    <script type="text/javascript"> //do gráfico
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
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Análises COVID-19</a>
      <!-- <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search"> -->
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="#">Sign out</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="#inicio">
                  <span data-feather="home"></span>
                  Dashboard <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="shopping-cart"></span>
                  Products
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="users"></span>
                  Customers
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="bar-chart-2"></span>
                  Reports
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="layers"></span>
                  Integrations
                </a>
              </li>
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
            <h1 class="h2">Dashboard</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                <button class="btn btn-sm btn-outline-secondary">Share</button>
                <button class="btn btn-sm btn-outline-secondary">Export</button>
              </div>
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                This week
              </button>
            </div>
          </div>

          <p>Introdução do artigo</p><br /><br /><br /><br /><br /><br /><br /><br />
          <div id="graficoLinha" style="width: 900px; height: 500px;"></div>
          <p><b>Descrição:</b></p>
          <p>Neste gráfico, é possível visualizar... Na horizontal (eixo X) se vê os dias e na vertical (eixo Y) se vê o número de casos confirmados de COVID19.</p>
          <select id="estado">
              <option value="">Selecione o estado</option>
                <?php 
                if($result->num_rows > 0){ 
                    while($row = $result->fetch_assoc()){  
                        echo '<option value="'.$row['cidade'].'">'.$row['cidade'].'</option>'; 
                    } 
                }else{ 
                    echo '<option value="">Estado não disponível</option>'; 
                } 
                ?>
          </select>

          <select id="state" onchange="window.location.href = ('./?p='+state.value)">
              <option value="">Primeiro escolha um estado</option>
          </select>

          <ul>
              <li><a href="./?p=3550308">São Paulo</a></li>
              <li><a href="./?p=2910800">Feira de Santana</a></li>
              <li><a href="./?p=3300407">Barra Mansa:</a> </li>
              <li><a href="./?p=2311306">Quixadá</a></li>
              <li><a href="./?p=2304400">Fortaleza:</a> </li>
          </ul>

          
          <h2 id="secao2">Section title</h2>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Header</th>
                  <th>Header</th>
                  <th>Header</th>
                  <th>Header</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1,001</td>
                  <td>Lorem</td>
                  <td>ipsum</td>
                  <td>dolor</td>
                  <td>sit</td>
                </tr>
                <tr>
                  <td>1,002</td>
                  <td>amet</td>
                  <td>consectetur</td>
                  <td>adipiscing</td>
                  <td>elit</td>
                </tr>
                <tr>
                  <td>1,003</td>
                  <td>Integer</td>
                  <td>nec</td>
                  <td>odio</td>
                  <td>Praesent</td>
                </tr>
                <tr>
                  <td>1,003</td>
                  <td>libero</td>
                  <td>Sed</td>
                  <td>cursus</td>
                  <td>ante</td>
                </tr>
                <tr>
                  <td>1,004</td>
                  <td>dapibus</td>
                  <td>diam</td>
                  <td>Sed</td>
                  <td>nisi</td>
                </tr>
                <tr>
                  <td>1,005</td>
                  <td>Nulla</td>
                  <td>quis</td>
                  <td>sem</td>
                  <td>at</td>
                </tr>
                <tr>
                  <td>1,006</td>
                  <td>nibh</td>
                  <td>elementum</td>
                  <td>imperdiet</td>
                  <td>Duis</td>
                </tr>
                <tr>
                  <td>1,007</td>
                  <td>sagittis</td>
                  <td>ipsum</td>
                  <td>Praesent</td>
                  <td>mauris</td>
                </tr>
                <tr>
                  <td>1,008</td>
                  <td>Fusce</td>
                  <td>nec</td>
                  <td>tellus</td>
                  <td>sed</td>
                </tr>
                <tr>
                  <td>1,009</td>
                  <td>augue</td>
                  <td>semper</td>
                  <td>porta</td>
                  <td>Mauris</td>
                </tr>
                <tr>
                  <td>1,010</td>
                  <td>massa</td>
                  <td>Vestibulum</td>
                  <td>lacinia</td>
                  <td>arcu</td>
                </tr>
                <tr>
                  <td>1,011</td>
                  <td>eget</td>
                  <td>nulla</td>
                  <td>Class</td>
                  <td>aptent</td>
                </tr>
                <tr>
                  <td>1,012</td>
                  <td>taciti</td>
                  <td>sociosqu</td>
                  <td>ad</td>
                  <td>litora</td>
                </tr>
                <tr>
                  <td>1,013</td>
                  <td>torquent</td>
                  <td>per</td>
                  <td>conubia</td>
                  <td>nostra</td>
                </tr>
                <tr>
                  <td>1,014</td>
                  <td>per</td>
                  <td>inceptos</td>
                  <td>himenaeos</td>
                  <td>Curabitur</td>
                </tr>
                <tr>
                  <td>1,015</td>
                  <td>sodales</td>
                  <td>ligula</td>
                  <td>in</td>
                  <td>libero</td>
                </tr>
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