<?php 
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

$expense = $cost = $expense_err = $cost_err = $message = "";
$update= false;

if (isset($_GET["edit"])) {
    $budget_id = $_GET['edit'];
    $update = true;
    $record = ($link->query("SELECT * FROM budgets WHERE budget_id = '$budget_id'"));
    $n = [];
  
    if ($record->num_rows > 0) {
      $n = mysqli_fetch_array($record, MYSQLI_ASSOC);
      $expense = $n["expense"];
      $cost = $n["cost"];
    }

    if (isset($_POST["update"])) {
        // Check if expense is empty
        if(empty(trim($_POST["expense"]))){
            $expense_err = "Please enter budget expense.";
        } else{
            $expense = trim($_POST["expense"]);
        }

        // Check if cost is empty
        if(empty(trim($_POST["cost"]))){
            $cost_err = "Please enter the cost of the expense.";
        } else{
            $cost = trim($_POST["cost"]);
        }

        // Check input errors before updating database
        if(empty($expense_err) && empty($cost_err)) {
            $sql_update = "UPDATE budgets SET expense='$expense', cost='$cost' WHERE budget_id = '$budget_id' ";
            if ($link->query($sql_update) === TRUE) {
                $message = '<div class="alert alert-success"><strong>Succesfully Updated</strong> budget details. Proceed to Home Page to view</div>';;
            } else {
                echo "Error: ".$link->error;
            }
        }
    }
}
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
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="welcome.php">HEKIMA</a>
      <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
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
                <a class="nav-link" href="index.php">
                  <span data-feather="file-text"></span>
                  Budgeting & Loans
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <!-- -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
          <h2>Fill in the form below to add an expense to your family budget</h2>
          <form action="" method="post">
            <div class="form-group">
              <div class="col-sm-10 col-sm-offset-2">
                <?php echo $message; ?>   
              </div>
            </div> 
            <div class="row">
              <div class="col">
              <h5>Budget Expense</h5>
                <div class="form-floating is-invalid mb-3 mt-3">
                  <input type="text" class="form-control" id="expense" placeholder="Enter expense" name="expense"
                  <?php echo (!empty($expense_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $expense; ?>">
                  <label for="expense">Description of budget expense</label>
                </div>
                <div class="invalid-feedback" style="font-size: medium;"><strong><?php echo $expense_err; ?></strong></div>
              </div>
              <div class="col">
                <h5>Cost of Expense</h5>
                <div class="form-floating is-invalid mb-3 mt-3">
                  <input type="number" class="form-control" id="cost" placeholder="Enter cost" name="cost" 
                  <?php echo (!empty($cost_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cost; ?>">
                  <label for="cost">Cost of the expense stated</label>
                </div>
                <div class="invalid-feedback" style="font-size: medium;"><strong><?php echo $cost_err; ?></strong></div>
              </div>
            </div>
            <?php if ($update == true): ?>
              <button class="btn btn-primary" type="submit" name="update" >Update details</button>
            <?php else: ?>
              <input type="submit" class="btn btn-primary" value="Submit">
            <?php endif ?>
          </form>
        </main>
        <!-- -->

      </div>
    </div>

        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
        <script src="visual.js"></script>
    </body>
</html>