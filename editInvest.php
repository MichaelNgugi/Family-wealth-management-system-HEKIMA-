<?php
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

$type = $description = $rate =  $value ="";
$type_err = $desc_err = $rate_err = $value_err = "";
$message = "";
$update= false;

if (isset($_GET["edit"])) {
    $invest_id = $_GET['edit'];
    $update = true;
    $record = ($link->query("SELECT * FROM investments WHERE id = '$invest_id'"));
    $n = [];
  
    if ($record->num_rows > 0) {
      $n = mysqli_fetch_array($record, MYSQLI_ASSOC);
      $type = $n["type"];
      $description = $n["description"];
      $rate = $n["growth_rate"];
      $value = $n["value"];
    }

    if (isset($_POST['update'])) {
        // Check if investment type is empty
        if(empty(trim($_POST["invest"]))){
            $type_err = "Please enter the Investment type.";
        } else{
            $type = trim($_POST["invest"]);
        }

        // Check if description is empty
        if(empty(trim($_POST["desc"]))){
            $desc_err = "Please enter Description of Investment.";
        } else{
            $description = trim($_POST["desc"]);
        }

        // Check if value of rate is empty
        if(empty(trim($_POST["rate"]))){
            $rate_err = "Please enter growth rate of the Investment.";
        } else{
            $rate = trim($_POST["rate"]);
        }

        // Check if value of asset is empty
        if(empty(trim($_POST["value"]))){
            $value_err = "Please enter value of Investment.";
        } else{
            $value = trim($_POST["value"]);
        }

        // Check input errors before updating database
        if(empty($type_err) && empty($desc_err) && empty($rate_err) && empty($value_err)) {
            $sql_update = "UPDATE investments SET type='$type', description='$description', growth_rate= '$rate', value='$value' WHERE id = '$invest_id' ";
            if ($link->query($sql_update) === TRUE) {
                $message = '<div class="alert alert-success"><strong>Succesfully Updated</strong> Investment details. Proceed to Home Page to view</div>';;
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
          <h2>Fill in the form below to Edit the investment</h2>
          <form action="" method="post">
            <div class="form-group">
              <div class="col-sm-10 col-sm-offset-2">
                <?php echo $message; ?>   
              </div>
            </div> 
            <div class="row">
              <div class="col">
                <h5>Investment type</h5>
                <div class="form-floating is-invalid mb-3 mt-3">
                  <input type="text" class="form-control" id="invest" placeholder="Enter investment type" name="invest" 
                  <?php echo (!empty($type_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $type; ?>">
                  <label for="invest">Investment type</label>
                </div>
                <div class="invalid-feedback" style="font-size: medium;"><strong><?php echo $type_err; ?></strong></div>
              </div>
              <div class="col">
              <h5>Description</h5>
                <div class="form-floating is-invalid mb-3 mt-3">
                  <input type="text" class="form-control" id="desc" placeholder="Enter description" name="desc"
                  <?php echo (!empty($desc_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $description; ?>">
                  <label for="desc">Short description of the investment</label>
                </div>
                <div class="invalid-feedback" style="font-size: medium;"><strong><?php echo $desc_err; ?></strong></div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <h5>Growth rate</h5>
                <div class="form-floating is-invalid mb-3 mt-3">
                  <input type="number" class="form-control" id="rate" placeholder="Enter growth rate" name="rate" step="any"
                  <?php echo (!empty($rate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $rate; ?>">
                  <label for="rate">Growth rate in %</label>
                </div>
                <div class="invalid-feedback" style="font-size: medium;"><strong><?php echo $rate_err; ?></strong></div>
              </div>
              <div class="col">
                <h5>Value</h5>
                <div class="form-floating is-invalid mb-3 mt-3">
                  <input type="number" class="form-control" id="value" placeholder="Enter value" name="value"
                  <?php echo (!empty($value_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $value; ?>">
                  <label for="value">Investment value in Kshs</label>
                </div>
                <div class="invalid-feedback" style="font-size: medium;"><strong><?php echo $value_err; ?></strong></div>
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