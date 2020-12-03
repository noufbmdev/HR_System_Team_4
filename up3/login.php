<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if( isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: dashboard.php");
  exit;
}

// Include config file
require_once "config/config.php";

// Define variables and initialize with empty values
$employeeId = $password =  $errorMsg = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
  $employeeId = $_POST['inputEmpId'];
  $password = $_POST['inputPassword'];

  if( $employeeId == ""){
    $errorMsg = " يجب ادخال جميع المعلومات";
  }
  else if($password == ""){
    $errorMsg = " يجب ادخال جميع المعلومات";
  }
  else {

    $sql = "SELECT *, roles.role_name from employee inner join roles on roles.id = employee.Role  where Employee_ID = '".$employeeId."' and Password = '".$password."'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $user = mysqli_fetch_array($result,MYSQLI_ASSOC);

      $_SESSION["loggedin"] = true;
      $_SESSION["role_id"] = $user['Role'];
      $_SESSION["role"] = $user['role_name'];
      $_SESSION["username"] = $user['First_Name'];
      $_SESSION["user_id"] = $user['ID'];

      header("location: dashboard.php");

    } else {
      $errorMsg = "البيانات غير صحيحة";
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

    <title>تسجيل الدخول</title>

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
        <p class="text-center">من فضلك ادخل الرمز الوظيفي و كلمة المرور لتسجيل الدخول</p>

        <label for="EmployeeId">الرمز الوظيفي</label>
        <input type="text" id="inputEmpId" name="inputEmpId" class="form-control"  autofocus>

        <label for="inputPassword">كلمة المرور</label>
        <input type="password" id="inputPassword" name="inputPassword" class="form-control" >

        <div class="text-center">
          <a href="password_reset_step1.php">هل نسيت كلمة المرور؟</a>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">تسجيل دخول</button>
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
