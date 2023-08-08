<?php
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

// budget table
$budgetsql = "SELECT * from budgets";
$result1 = ($link->query($budgetsql));
//declare array to store the data of database
$row1 = [];

if ($result1->num_rows > 0)
  {
    // fetch all data from db into array
    $row1 = $result1->fetch_all(MYSQLI_ASSOC);
  }
//getting sum of budget
$budgetsum = ($link->query("SELECT  SUM(cost) FROM budgets"));
$countresult3 = mysqli_fetch_row($budgetsum);
$totalbudget = $countresult3[0];
//budget graph data
$graph_sql = ($link->query("SELECT expense, cost FROM budgets"));
$budgetdata = array();
$res = [];
while ($res = mysqli_fetch_assoc($graph_sql)) { 
  $budgetdata[] = $res;
}
//write to json file
$fp = fopen('budget.json', 'w');
fwrite($fp, json_encode($budgetdata));
fclose($fp);


// loan table
$loansql = "SELECT * from loans";
$result2 = ($link->query($loansql));
//declare array to store the data of database
$row2 = [];

if ($result2->num_rows > 0)
  {
    // fetch all data from db into array
    $row2 = $result2->fetch_all(MYSQLI_ASSOC);
  }
//loan graph data
$g_sql = ($link->query("SELECT description, principal FROM loans"));
$loandata = array();
$res2 = [];
while ($res2 = mysqli_fetch_assoc($g_sql)) { 
  $loandata[] = $res2;
}
//write to json file
$fp1 = fopen('loan.json', 'w');
fwrite($fp1, json_encode($loandata));
fclose($fp1);
?>

<!DOCTYPE html>
<html>
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
        <script src="//code.jquery.com/jquery-1.9.1.js"></script>
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
                <a class="nav-link" aria-current="page" href="welcome.php">
                  <span data-feather="home"></span>
                  Home
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#budgets">
                  <span data-feather="file-text"></span>
                  Budgeting
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#loans">
                  <span data-feather="file-text"></span>
                  Loans
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Budget Section -->
              <div id="budgets">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Family Budget</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a type="button" class="btn btn-sm btn-primary" href="addBudget.php" data-html2canvas-ignore="true">Add new budget expense</a>
                        </div>
                        <div class="btn-group me-2">
                          <button type="button" class="btn btn-sm btn-outline-secondary" id="budgetDwnld" data-html2canvas-ignore="true">Export as PDF</button>
                        </div>
                    </div>
                </div>
                <h3>Your Total of your budget expenses are: <?php echo $english_format_number = number_format($totalbudget); ?></h3>
                <hr>
                <canvas class="my-4 w-100" id="budgetchart" width="900" height="380"></canvas> 
                <div class="table-responsive" id="budgettable">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Expense</th>
                          <th scope="col">Cost</th>
                          <th scope="col"></th>
                        </tr>
                      </thead>
                        <tbody>
                          <?php
                            if(!empty($row1)){
                              foreach($row1 as $rows){
                                ?>
                                <tr>
                                    <td><?php echo $rows['budget_id']; ?></td>
                                    <td><?php echo $rows['expense']; ?></td>
                                    <td><?php echo $english_format_number = number_format($rows['cost']); ?></td>
                                    <td>
                                      <a href="editBudget.php?edit=<?php echo $rows['budget_id']; ?>" class="btn btn-primary btn-sm" data-html2canvas-ignore="true">Edit</a>
                                      <a href="delBudget.php?del=<?php echo $rows['budget_id']; ?>" onclick="return check();" class="btn btn-secondary btn-sm" data-html2canvas-ignore="true">Delete</a>
                                    </td>
                                </tr>
                                <?php
                              }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
              </div>
              <!-- -->

              <!-- Loans Section -->
              <div id="loans">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Loan</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a type="button" class="btn btn-sm btn-primary" href="addLoan.php" data-html2canvas-ignore="true">Add new loan to track</a>
                        </div>
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="loanDwnld" data-html2canvas-ignore="true">Export as PDF</button>
                        </div>
                    </div>
                </div>

                <div>
                <canvas class="my-4 w-100" id="loanchart" width="900" height="380"></canvas>
                </div>
                <div class="table-responsive" id="loantable">
                    <table class="table table-hover">
                      <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Description</th>
                            <th scope="col">Principal</th>
                            <th scope="col">Interest rate / month</th>
                            <th scope="col">Payment</th>
                            <th scope="col">Balance</th>
                            <th scope="col"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            if(!empty($row2)){
                              foreach($row2 as $rows){
                                ?>
                                <tr>
                                    <td><?php echo $rows['loan_id']; ?></td>
                                    <td><?php echo $rows['description']; ?></td>
                                    <td><?php echo $english_format_number = number_format($rows['principal']); ?></td>
                                    <td><?php echo $rows['rate']; ?>%</td>
                                    <td><?php echo $english_format_number = number_format($rows['total_payment']); ?></td>
                                    <td><?php echo $english_format_number = number_format($rows['balance']); ?></td>
                                    <td>
                                      <a href="editLoan.php?edit=<?php echo $rows['loan_id']; ?>" class="btn btn-primary btn-sm" data-html2canvas-ignore="true">Edit</a>
                                      <a href="delLoan.php?del=<?php echo $rows['loan_id']; ?>" onclick="return check();" class="btn btn-secondary btn-sm" data-html2canvas-ignore="true">Delete</a>
                                    </td>
                                    <tr><td style="color:#070001; font-weight:bold;" colspan="7">
                                      <?php
                                        $percentage = ($rows['balance'] / $rows['principal']) * 100;
                                        $percentage = 100 - (round($percentage, 0)); 
                                      ?>
                                      <?php echo $percentage; ?>% Complete
                                      <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="height: 20px; width: <?=$percentage;?>%"></div>
                                      </div>
                                    </td></tr>
                                </tr>
                                <?php
                              }
                            }
                          ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="index.js"></script>
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
    /* ----- Budget Graph -------- */
    $.ajax({
      url: "budget.json",
      method: "GET",
      success: function(budget) {
        var expense = [];
        var cost = [];

        for(var i in budget) {
          expense.push(budget[i].expense);
          cost.push(budget[i].cost);
        }

        var ctx = document.getElementById("budgetchart").getContext('2d');
          // draw background
          var backgroundColor = 'white';
          Chart.plugins.register({
          beforeDraw: function(c) {
          var ctx = c.chart.ctx;
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, c.chart.width, c.chart.height);
          }
          });

        var budgetChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: expense,
            datasets: [{
              label: ['Budget Expenses'],
              backgroundColor: "orange",
              data: cost,
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
                text: "Budget Expense Allocation"
            }
          }
        });
      },
      error: function(budget) {
        var jsDataPlaceholder = <?= json_encode($budgetdata); ?>;
        console.log(jsDataPlaceholder);
      }
    });

    /* ----- Loan Graph -------- */
    $.ajax({
      url: "loan.json",
      method: "GET",
      success: function(loan) {
        var description = [];
        var principal = [];

        for(var i in loan) {
          description.push(loan[i].description);
          principal.push(loan[i].principal);
        }

        var ctx2 = document.getElementById("loanchart").getContext('2d');
        // draw background
        var backgroundColor = 'white';
        Chart.plugins.register({
        beforeDraw: function(c) {
        var ctx = c.chart.ctx;
          ctx.fillStyle = backgroundColor;
          ctx.fillRect(0, 0, c.chart.width, c.chart.height);
          var width = c.chart.width;
          var height = c.chart.height;
          ctx.restore();
          var fontSize = (height / 114).toFixed(2);
          ctx.font = fontSize + "em sans-serif";
          ctx.textBaseline = "middle";
            var text = "75%",
                textX = Math.round((width - ctx.measureText(text).width) / 2),
                textY = height / 2;
            ctx.fillText(text, textX, textY);
            ctx.save();
        }
        });

        var loanChart = new Chart(ctx2, {
          onAnimationComplete: function() {
          ctx.fillText("Hello there");
          },
        type: 'doughnut',
        data: {
          labels: description,
          datasets: [{
            label: 'Initial Principal',
            data: principal,
            backgroundColor: ["purple", "red", "lime", "yellow", "blue", "orange"],
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
              text: "Loans Allocation"
          }
        }
        });
      },
      error: function(loan) {
        var jsDataPlaceholder = <?= json_encode($loandata); ?>;
        console.log(jsDataPlaceholder);
      }
    });
  });
</script>