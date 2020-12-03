<?php
// Initialize the session
session_start();

// Include config file
require_once "config/config.php";

// Define variables and initialize with empty values
$employeeId = $errorMsg = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
  $employeeId = $_POST['inputEmpId'];

  if( $employeeId == ""){
    $errorMsg = "يجب ادخال الرمز الوظيفي";
  }

  else {
    $sql = "SELECT * from employee where Employee_ID = '".$employeeId."'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $_SESSION["reset_password_user"] = $employeeId;
        header("location: password_reset_step2.php");
    } else {
      $errorMsg = "الرمز الوظيفي غير صيحح";
    }
    $conn->close();
  }

}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>تغيير كلمة المرور</title>

    <!-- Bootstrap core CSS -->
    <link href="plugins/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="plugins/dist/css/bootstrap-rtl.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
    <script src="assets/js/ie-emulation-modes-warning.js"></script>
  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <img id="logo" src="images/logo.png" class="img img-responsive" alt="Saudi ADHD Society Logo" style="width: 120px; margin: 0 auto;">
        <h2 class="form-signin-heading text-center">HR System</h2>
        <p class="text-center">من فضلك ادخل الرمز الوظيفي لتغيير كلمة المرور</p>

        <label for="EmployeeId">الرقم الوظيفي</label>
        <input type="text" id="inputEmpId" name="inputEmpId" class="form-control"  autofocus>

        <button class="btn btn-lg btn-primary btn-block" type="submit">التحقق من الحساب</button>
        <?php
        if($errorMsg != ""){
        ?>
          <div class="alert alert-danger" role="alert" style="margin-top: 20px;">
            <?php echo $errorMsg; ?>
          </div>
        <?php
        }
        ?>
      </form>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
