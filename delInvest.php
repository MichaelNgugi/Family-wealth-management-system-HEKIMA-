<?php
session_start();
//check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

if (isset($_GET['del'])) {
    $invest_id = $_GET['del'];
    $delete_sql ="DELETE FROM investments WHERE id= ?";

    if($stmt = mysqli_prepare($link, $delete_sql)){
        // Bind variables to the prepared statement as parameters        
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = $invest_id;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
           // Records deleted successfully. Redirect to landing page
           echo "<script>
           window.location.href='welcome.php';
           alert('Records deleted successfully');
           </script>";
           exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}
?>