<?php
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";
$sql = "SELECT * from users WHERE user_type IS NULL";
$result = ($link->query($sql));
//declare array to store the data of database
$row = [];

if ($result->num_rows > 0)
  {
    // fetch all data from db into array
    $row = $result->fetch_all(MYSQLI_ASSOC);
  }
//getting count of users
$usercountsql = ($link->query("SELECT  COUNT(*) FROM users"));
$usercount = mysqli_fetch_row($usercountsql);
$usersize = $usercount[0];

//getting count of assets
$assetcountsql = ($link->query("SELECT  COUNT(*) FROM assets"));
$assetcount = mysqli_fetch_row($assetcountsql);
$assetsize = $assetcount[0];

//getting count of investments
$investcountsql = ($link->query("SELECT  COUNT(*) FROM investments"));
$investcount = mysqli_fetch_row($investcountsql);
$investsize = $investcount[0];

//getting count of budgets
$budgetcountsql = ($link->query("SELECT  COUNT(*) FROM budgets"));
$budgetcount = mysqli_fetch_row($budgetcountsql);
$budgetsize = $budgetcount[0];

//getting count of loans
$loancountsql = ($link->query("SELECT  COUNT(*) FROM loans"));
$loancount = mysqli_fetch_row($loancountsql);
$loansize = $loancount[0];

// SUM of all entities
$assetsum = ($link->query("SELECT  SUM(value) FROM assets"));
$countresult1 = mysqli_fetch_row($assetsum);
$totalasset = $countresult1[0];

$investsum = ($link->query("SELECT  SUM(value) FROM investments"));
$countresult2 = mysqli_fetch_row($investsum);
$totalinvest = $countresult2[0];

$budgetsum = ($link->query("SELECT  SUM(cost) FROM budgets"));
$countresult3 = mysqli_fetch_row($budgetsum);
$totalbudget = $countresult3[0];

$loansum = ($link->query("SELECT  SUM(principal) FROM loans"));
$countresult4 = mysqli_fetch_row($loansum);
$totalloan = $countresult4[0];

//Graph data for user registration distribution
$user_sql = "SELECT COUNT(username) 'Users', start_date FROM users WHERE user_type IS NULL GROUP BY start_date;";
$userdata = array();
$row2 = [];
$output = $link->query($user_sql);
while ($res = mysqli_fetch_assoc($output)) { 
    $userdata[] = $res;
}
//write to json file
$fp = fopen('userdata.json', 'w');
fwrite($fp, json_encode($userdata));
fclose($fp);
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
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">HEKIMA - Admin panel</a>
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
                <a class="nav-link" href="#users">
                  <span data-feather="layers"></span>
                   User Accounts
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#reports">
                  <span data-feather="dollar-sign"></span>
                  Reports
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
          
          <!-- Users SECTION-->
          <div id="users">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom" data-html2canvas-ignore="true">
              <h1 class="h2">Welcome Admin</h1>
            </div>

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
              <h2>Current Users</h2>
              <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                  <button type="button" class="btn btn-sm btn-outline-secondary" id="userDwnld" data-html2canvas-ignore="true">Export as PDF</button>
                </div>
              </div>
            </div>
            
            <canvas class="my-4 w-100" id="usergraph" width="900" height="380"></canvas>

            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Username</th>
                    <th scope="col">Start date</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    if(!empty($row))
                    foreach($row as $rows)
                    {
                      ?>
                      <tr>
                          <td><?php echo $rows['id']; ?></td>
                          <td><?php echo $rows['username']; ?></td>
                          <td><?php echo $rows['start_date']; ?></td>
                          <td data-html2canvas-ignore="true" ><a href="delUser.php?del=<?php echo $rows['id']; ?>" onclick="return check();" class="btn btn-secondary btn-sm">Delete</a></td>
                      </tr>
                      <?php
                    } 
                    ?>
                </tbody>
                <tbody>
                  <tr>
                  <th scope="col">Total users:</th>
                  <th scope="col"><?php echo $usersize; ?></th>
                  </tr>
                </tbody>
              </table><hr>
            </div>
          </div>
          <!-- -->

          <!-- Reports SECTION-->
          <div id="reports">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
              <h2>Reports</h2>
              <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                  <button type="button" class="btn btn-sm btn-outline-secondary" id="reportDwnld" data-html2canvas-ignore="true">Export as PDF</button>
                </div>
              </div>
            </div>
            <h3>Current Entities for all users</h3>
            <canvas class="my-4 w-80" id="entity" width="700" height="300"></canvas> 
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Entity type</th>
                    <th scope="col">Current Count</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td scope="col">Assets</td>
                    <th scope="col"><?php echo $assetsize; ?></th>
                  </tr>
                  <tr>
                    <td scope="col">Investments</td>
                    <th scope="col"><?php echo $investsize; ?></th>
                  </tr>
                  <tr>
                    <td scope="col">Budget Plans</td>
                    <th scope="col"><?php echo $budgetsize; ?></th>
                  </tr>
                  <tr>
                    <td scope="col">Loans</td>
                    <th scope="col"><?php echo $loansize; ?></th>
                  </tr>
                </tbody>
              </table>
            </div>
            <hr> 
            <h3>Fund Distribution</h3>
            <h5>Total money spent on each financial entity</h5>
            <canvas class="my-4 w-80" id="funds" width="700" height="380"></canvas>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">Entity type</th>
                    <th scope="col">Total amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                      <td scope="col">Assets</td>
                      <th scope="col"><?php echo $english_format_number = number_format($totalasset); ?></th>
                    </tr>
                    <tr>
                      <td scope="col">Investments</td>
                      <th scope="col"><?php echo $english_format_number = number_format($totalinvest); ?></th>
                    </tr>
                    <tr>
                      <td scope="col">Budget Plans</td>
                      <th scope="col"><?php echo $english_format_number = number_format($totalbudget); ?></th>
                    </tr>
                    <tr>
                      <td scope="col">Loans</td>
                      <th scope="col"><?php echo $english_format_number = number_format($totalloan); ?></th>
                    </tr>
                </tbody>
              </table>
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
    <script src="admin.js"></script>
  </body>
</html>

<script type="text/javascript">
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

<!-- Graphs -->
<script>
  $(document).ready(function(){
    $.ajax({
      url: "userdata.json",
      method: "GET",
      success: function(userdata) {
        var users = [];
        var date = [];

        for(var i in userdata) {
          users.push(userdata[i].Users);
          date.push(userdata[i].start_date);
        }

        //Graph for users 
        var ctx = document.getElementById("usergraph").getContext('2d');
        // draw background
        var backgroundColor = 'white';
          Chart.plugins.register({
          beforeDraw: function(c) {
            var ctx = c.chart.ctx;
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, c.chart.width, c.chart.height);
          }
        });

        var userchart = new Chart(ctx, {
          type: "line",
            data: {
              labels: date,
              datasets: [
                {
                  label: 'No of Users',
                  data: users,
                  lineTension: 0,
                  backgroundColor: "transparent",
                  borderColor: "#007bff",
                  borderWidth: 4,
                  pointBackgroundColor: "#0000",
                  hoverOffset: 4
                },
              ],
            },
            options: {
              scales: {
                yAxes: [
                  {
                    ticks: {
                      stepSize: 1,
                      beginAtZero: true,
                    },
                  },
                ],
              },
              legend: {
                display: true,
                position: 'bottom'
              },
              title: {
              display: true,
              text: "No of users registered by date"
              }
            }
        });

        //Graph for count distribution
        var ctx2 = document.getElementById("entity").getContext('2d');
        var entityChart = new Chart(ctx2, {
          type: 'bar',
            data: {
              labels: ["Financial Entity"] ,
              datasets: [
                {
                  label: "Assets",
                  backgroundColor: "purple",
                  data: [<?php echo $assetsize; ?>],
                },
                {
                  label: "Investments",
                  backgroundColor: "blue",
                  data: [<?php echo $investsize; ?>],
                },
                {
                  label: "Budget Expenses",
                  backgroundColor: "yellow",
                  data: [<?php echo $budgetsize; ?>],
                },
                {
                  label: "Loans",
                  backgroundColor: "red",
                  data: [<?php echo $loansize; ?>],
                }
              ]
            },
            options: {
              responsive: true,
              legend: {
                display: true,
                position: 'bottom',
                labels: {
                  fontColor: '#71748d',
                  fontFamily: 'Circular Std Book',
                  fontSize: 14,
                }
              },
              title: {
                display: true,
                text: "Total Count of all entities"
              },
              scales: {
                yAxes: [{
                  ticks: {
                  stepSize: 1,
                  beginAtZero: true,}
                }]
              }
            }
        });

        //Graph for fund distribution
        var ctx3 = document.getElementById("funds").getContext('2d');
        var fundsChart = new Chart(ctx3, {
          type: 'bar',
            data: {
              labels: ["Financial Entity"] ,
              datasets: [
                {
                  label: "Assets",
                  backgroundColor: "purple",
                  data: [<?php echo $totalasset; ?>],
                },
                {
                  label: "Investments",
                  backgroundColor: "blue",
                  data: [<?php echo $totalinvest; ?>],
                },
                {
                  label: "Total Budget Expenses",
                  backgroundColor: "yellow",
                  data: [<?php echo $totalbudget; ?>],
                },
                {
                  label: "Total amount of money borowed in loans",
                  backgroundColor: "red",
                  data: [<?php echo $totalloan; ?>],
                }
              ]
            },
            options: {
              responsive: true,
              legend: {
                display: true,
                position: 'bottom',
                labels: {
                  fontColor: '#71748d',
                  fontFamily: 'Circular Std Book',
                  fontSize: 14,
                }
              },
              title: {
                display: true,
                text: "Fund distribution"
              },
              scales: {
                yAxes: [{
                  ticks: {
                    stepSize: 1000000
                  }
                }]
              }
            }
        });
      },
      error: function(userdata) {
        var jsDataPlaceholder = <?= json_encode($userdata); ?>;
        console.log(jsDataPlaceholder);
      }
    });
  });
</script>