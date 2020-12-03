<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if( !isset($_SESSION["reset_password_user"]) ){
  header("location: login.php");
  exit;
}

// Include config file
require_once "config/config.php";

// Define variables and initialize with empty values
$employeeId = $errorMsg = $newPassword = $confirmPassword = $successMsg = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{

  $employeeId = $_SESSION["reset_password_user"];

  $newPassword = $_POST['inputNewPassword'];
  $confirmPassword = $_POST['inputConfirmPassword'];

  if( $newPassword == ""){
    $errorMsg = "الرجاء ادخال كلمة المرور الجديدة";
  }
  else if(strlen($newPassword) < 8){
    $errorMsg = "كلمة المرور يجب ان يتكون من ٨ احرف او اكثر";
  }
  else if($newPassword != $confirmPassword) {
    $errorMsg = "كلمة المرور التي ادخلتها لا تتطابق";
  }
  else {
    // prepare and bind
  $stmt = $conn->prepare("update `employee` set `Password` = ? where `Employee_ID` = ? ");
  $stmt->bind_param("ss", $paramPassword, $paramEmployeeId);

  $paramPassword = $newPassword;
  $paramEmployeeId = $employeeId;

    if ($stmt->execute()) {
      $successMsg = "تم تسجيل كلمة المرور الجديدة بنجاح";
      session_destroy();
    } else {
      $errorMsg = "لم يتم تسجيل كلمة المرور الجديدة";
    }
    $stmt->close();

  }

  $conn->close();
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
        <p class="text-center">من فضلك ادخل كلمة المرور الجديدة</p>

        <label for="NewPassword">كلمة المرور الجديدة</label>
        <input type="password" id="inputNewPassword" name="inputNewPassword" class="form-control"  autofocus>

        <label for="ConfirmPassword">تأكيد كلمة المرور</label>
        <input type="password" id="inputConfirmPassword" name="inputConfirmPassword" class="form-control"  autofocus>

        <button class="btn btn-lg btn-primary btn-block" type="submit">تغيير كلمة المرور</button>
        <?php
        if($errorMsg != ""){
        ?>
          <div class="alert alert-danger" role="alert" style="margin-top: 20px;">
            <?php echo $errorMsg; ?>
          </div>
        <?php
        }
        ?>
        <?php
        if($successMsg != ""){
        ?>
          <div class="alert alert-success" role="alert" style="margin-top: 20px;">
            <?php echo $successMsg; ?>
          </div>
          <div class="text-center">
            <a href="login.php">Login</a>
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
