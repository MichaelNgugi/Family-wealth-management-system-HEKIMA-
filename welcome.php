<?php
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

// assets table
$assetsql = "SELECT * from assets";
$result1 = ($link->query($assetsql));
//declare array to store the data of database
$row1 = [];

if ($result1->num_rows > 0)
  {
    // fetch all data from db into array
    $row1 = $result1->fetch_all(MYSQLI_ASSOC);
  }
//getting count of assets
$assetcountsql = ($link->query("SELECT  COUNT(*) FROM assets"));
$assetcount = mysqli_fetch_row($assetcountsql);
$assetsize = $assetcount[0];
//getting sum of all assets
$assetsum = ($link->query("SELECT  SUM(value) FROM assets"));
$countresult1 = mysqli_fetch_row($assetsum);
$totalasset = $countresult1[0];
//asset graph data
$graph_sql = ($link->query("SELECT description, value FROM assets"));
$assetdata = array();
$res = [];
while ($res = mysqli_fetch_assoc($graph_sql)) { 
  $assetdata[] = $res;
}
//write to json file
$fp = fopen('asset.json', 'w');
fwrite($fp, json_encode($assetdata));
fclose($fp);



// investment table
$investsql = "SELECT * from investments";
$result2 = ($link->query($investsql));
//declare array to store the data of database
$row2 = [];

if ($result2->num_rows > 0)
  {
    // fetch all data from db into array
    $row2 = $result2->fetch_all(MYSQLI_ASSOC);
  }
//getting count of investments
$investcountsql = ($link->query("SELECT  COUNT(*) FROM investments"));
$investcount = mysqli_fetch_row($investcountsql);
$investsize = $investcount[0];
//getting sum of all investments
$investsum = ($link->query("SELECT  SUM(value) FROM investments"));
$countresult2 = mysqli_fetch_row($investsum);
$totalinvest = $countresult2[0];
//invest graph data
$gr_sql = ($link->query("SELECT description, value FROM investments"));
$investdata = array();
$res2 = [];
while ($res2 = mysqli_fetch_assoc($gr_sql)) { 
  $investdata[] = $res2;
}
//write to json file
$fp2 = fopen('invest.json', 'w');
fwrite($fp2, json_encode($investdata));
fclose($fp2);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HEKIMA</title>
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Custom styles -->
    <link href="myStyle.css" rel="stylesheet">
    <!-- Font Awesome -->
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
   <!-- Scripts -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  </head>
  <body>
    <header class="navbar  sticky-top  flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">HEKIMA</a>
      <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search"> -->
      <div class="navbar-nav">
        <div class="nav-item text-nowrap">
          <a class="nav-link px-3" href="logout.php" style="color: white;">Sign out</a>
        </div>
      </div>
    </header>

    <div class="container-fluid">
      <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
          <div class="position-sticky pt-3">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#">
                  <span data-feather="home"></span>
                  Home
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#assets">
                  <span data-feather="layers"></span>
                  Assets
                </a>
                <ul class="sub">
                  <li>
                    <a class="nav-link" href="#assetinsight">
                      <span data-feather="bar-chart-2"></span>
                      Insights
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#investment">
                  <span data-feather="dollar-sign"></span>
                  Investments
                </a>
                <ul class="sub">
                  <li>
                    <a class="nav-link" href="#investinsight">
                      <span data-feather="bar-chart-2"></span>
                      Insights
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php">
                  <span data-feather="file-text"></span>
                  Budgeting & Loans
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div class="col-52">
              <h3>Welcome to HEKIMA</h3>
              <h4>"Command your wealth and you will be rich and free; if your wealth commands you, you are poor indeed."</h4>
              <h5>Edmund Burke</h5>
            </div>
          </div>

          <div class="container text-center">
            <h1 class="h2">Welcome <?php echo htmlspecialchars($_SESSION["username"]);; ?></h1>
            </div>

          <!-- ASSETS SECTION-->
          <div id="assets">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
              <h1 class="h2">Assets</h1>
              <div class="btn-toolbar mb-2 mb-md-0">
                  <div class="btn-group me-2">
                    <a type="button" class="btn btn-sm btn-primary" href="addAsset.php" data-html2canvas-ignore="true">Add new Asset</a>
                  </div>
                  <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="assetDwnld" data-html2canvas-ignore="true">Export as PDF</button>
                  </div>
              </div>
              </div>
            <canvas class="my-4 w-100" id="assetchart" width="900" height="380"></canvas>
            <div class="table-responsive" id="assettable">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Asset type</th>
                    <th scope="col">Description</th>
                    <th scope="col">Value (in Kshs)</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                    if(!empty($row1))
                    foreach($row1 as $rows)
                    {
                      ?>
                      <tr>
                          <td><?php echo $rows['id']; ?></td>
                          <td><?php echo $rows['type']; ?></td>
                          <td><?php echo $rows['description']; ?></td>
                          <td><?php  echo $english_format_number = number_format($rows['value']); ?></td>
                          <td>
                            <a href="editAsset.php?edit=<?php echo $rows['id']; ?>" class="btn btn-primary btn-sm" data-html2canvas-ignore="true">Edit</a>
                            <a href="delAsset.php?del=<?php echo $rows['id']; ?>" onclick="return check();" class="btn btn-secondary btn-sm" data-html2canvas-ignore="true">Delete</a>
                          </td>
                      </tr>
                      <?php
                    } 
                    ?>
                </tbody>
              </table>
              <h3 id="assetinsight">Insights</h3><hr>
              
              <div class="row">
                <div class="col-xl-6 col-md-12 mb-4">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between p-md-1">
                        <div class="d-flex flex-row">
                          <div class="align-self-center">
                            <h2 class="h1 mb-0 me-4"><?php echo $assetsize; ?></h2>
                          </div>
                          <div>
                            <h4>Assets</h4>
                            <p class="mb-0">No of Assets owned</p>
                          </div>
                        </div>
                        <div class="align-self-center">
                          <i class="far fa-heart text-secondary fa-3x" style="font-size: 400%;"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-6 col-md-12 mb-4">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between p-md-1">
                        <div class="d-flex flex-row">
                          <div class="align-self-center">
                            <h5 class="h3 mb-0 me-4">Ksh <?php echo $english_format_number = number_format($totalasset); ?></h5>
                          </div>
                          <div>
                            <h4>Total Value</h4>
                            <p class="mb-0">Total Valuation</p>
                          </div>
                        </div>
                        <div class="align-self-center">
                          <i class="fas fa-wallet text-success fa-3x"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <!---->
          <hr>

          <!-- INVESTMENTS SECTION-->
          <div id="investment">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Investment</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                  <div class="btn-group me-2">
                    <a type="button" class="btn btn-sm btn-primary" href="addInvest.php" data-html2canvas-ignore="true">Add new Investment</a>
                  </div>
                  <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="investDwnld" data-html2canvas-ignore="true">Export as PDF</button>
                  </div>
                </div>
              </div>
              <hr>

            <canvas class="my-4 w-100" id="investchart" width="900" height="380"></canvas> 
            <div class="table-responsive" id="investtable">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Investment type</th>
                      <th scope="col">Description</th>
                      <th scope="col">Growth rate</th>
                      <th scope="col">Value (in Kshs)</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    if(!empty($row2))
                    foreach($row2 as $rows)
                    {
                      ?>
                      <tr>
                          <td><?php echo $rows['id']; ?></td>
                          <td><?php echo $rows['type']; ?></td>
                          <td><?php echo $rows['description']; ?></td>
                          <td><?php echo $rows['growth_rate']; ?>%</td>
                          <td><?php  echo $english_format_number = number_format($rows['value']); ?></td>
                          <td>
                            <a href="editInvest.php?edit=<?php echo $rows['id']; ?>" class="btn btn-primary btn-sm" data-html2canvas-ignore="true">Edit</a>
                            <a href="delInvest.php?del=<?php echo $rows['id']; ?>" onclick="return check();" class="btn btn-secondary btn-sm" data-html2canvas-ignore="true">Delete</a>
                          </td>
                      </tr>
                      <?php
                    } 
                    ?>
                  </tbody>
                </table>
              </div>

              <h3 id="investinsight">Insights</h3><hr>
              <div class="row">
                <div class="col-xl-6 col-md-12 mb-4">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between p-md-1">
                        <div class="d-flex flex-row">
                          <div class="align-self-center">
                            <h2 class="h1 mb-0 me-4"><?php echo $investsize; ?></h2>
                          </div>
                          <div>
                            <h4>Investments</h4>
                            <p class="mb-0">No of Investments</p>
                          </div>
                        </div>
                        <div class="align-self-center">
                          <i class="far fa-heart text-danger fa-3x"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-6 col-md-12 mb-4">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between p-md-1">
                        <div class="d-flex flex-row">
                          <div class="align-self-center">
                            <h2 class="h3 mb-0 me-4">Ksh <?php echo $english_format_number = number_format($totalinvest); ?></h2>
                          </div>
                          <div>
                            <h4>Total Value</h4>
                            <p class="mb-0">Total Valuation</p>
                          </div>
                        </div>
                        <div class="align-self-center">
                          <i class="fas fa-wallet text-success fa-3x"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <!-- -->
        </main>
      </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="welcome.js"></script>
  </body>
</html>

<script type="text/javascript">
  (function () {
  'use strict'

  feather.replace({ 'aria-hidden': 'true' }) 
  })()
  function check(){
    var question = confirm("Are you sure you want to delete this record?");
    if(question){
      return true;
    }else{
      alert("Value not deleted");
      return false;
    }
  }
</script>

<script type="text/javascript">
$(document).ready(function(){
  /* ----- Asset Graph -------- */
 $.ajax({
   url: "asset.json",
   method: "GET",
   success: function(asset) {
      var description = [];
      var value = [];

      for(var i in asset) {
        description.push(asset[i].description);
        value.push(asset[i].value);
      }

      var ctx = document.getElementById("assetchart").getContext('2d');
        // draw background
        var backgroundColor = 'white';
        Chart.plugins.register({
        beforeDraw: function(c) {
        var ctx = c.chart.ctx;
          ctx.fillStyle = backgroundColor;
          ctx.fillRect(0, 0, c.chart.width, c.chart.height);
        }
        });

      var assetChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: description,
          datasets: [{
            label: 'Value',
            data: value,
            backgroundColor: ["#1a2aac", "#97c2ee", "#1dbd42", "yellow", "#2f041d", "#f78d78", "#bb3459"],
            hoverOffset: 4
          }]
        },
        options: {
          legend: {
            display: true,
            position: 'left'
          },
          title: {
              display: true,
              text: "Asset Allocation"
          }
        }
      });
   },
   error: function(asset) {
    var jsDataPlaceholder = <?= json_encode($assetdata); ?>;
    console.log(jsDataPlaceholder);
   }
 });

 $.ajax({
   url: "invest.json",
   method: "GET",
   success: function(invest) {
      var description = [];
      var value = [];

      for(var i in invest) {
        description.push(invest[i].description);
        value.push(invest[i].value);
      }

      var ctx2 = document.getElementById("investchart").getContext('2d');
        // draw background
        var backgroundColor = 'white';
        Chart.plugins.register({
        beforeDraw: function(c) {
        var ctx = c.chart.ctx;
          ctx.fillStyle = backgroundColor;
          ctx.fillRect(0, 0, c.chart.width, c.chart.height);
        }
        });

      var investChart = new Chart(ctx2, {
        type: 'bar',
        data: {
          labels: description,
          datasets: [{
            label: ['Investments'],
            backgroundColor: "violet",
            data: value,
            barPercentage: 0.4
          }]
        },
        options: {
          scales: {
            yAxes: [
              {
              ticks: {
                stepSize: 10000,
                beginAtZero: true,
                },
              },
            ]
          },
            legend: {
              display: true,
              position: 'bottom'
            },
            title: {
              display: true,
              text: "Investment Allocation"
          }
        }
      });
   },
   error: function(invest) {
    var jsDataPlaceholder = <?= json_encode($investdata); ?>;
    console.log(jsDataPlaceholder);
   }
 });

});
</script>