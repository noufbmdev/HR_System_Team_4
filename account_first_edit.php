<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if( $_SESSION["loggedin"] == false){
  header("location: login.php");
  exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if( isset($_SESSION["role_id"]) && ($_SESSION["role_id"] == 2 || $_SESSION["role_id"] == 3 || $_SESSION["role_id"] == 4)){
  header("location: not_allowed.php");
  exit;
}

require_once 'config/config.php';

require_once 'includes/header.php';

require_once 'includes/nav-bar.php';

$accountId = $_GET['account_id'];

$firstNameErr = $lastNameErr = $middleNameErr = $nationalIdErr = "";

$successMsg = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
  $error = false;
  $stmt = $conn->prepare("update employee set `First_Name` = ?, `Middle_Name` = ?, `Last_Name` = ?, `National_ID` = ? where `ID` = ?");

  $stmt->bind_param("ssssi", $firstName, $middleName, $lastName, $nationalId, $ftAccountId);
  $firstName = $_POST["firstName"];
  $middleName = $_POST["middleName"];
  $lastName = $_POST["lastName"];
  $nationalId = $_POST["nationalId"];
  $ftAccountId = $accountId;

  if(empty($firstName)){
    $firstNameErr = "الرجاء ادخال الاسم الاول";
    $error = true;
  }

  if(empty($middleName)){
    $middleNameErr = "الرجاء ادخال الاسم الاوسط ";
    $error = true;
  }

  if(empty($lastName)){
    $lastNameErr = "الرجاء ادخال الاسم الاخير";
    $error = true;
  }

  if(empty($nationalId)){
    $nationalIdErr = "الرجاء ادخال رقم الهوية/الاقامة";
    $error = true;
  }

  if($error == false){

    if ($stmt->execute()) {
      $successMsg = "تم تسجيل المعلومات بنجاح";

    } else {
      $errorMsg = "لم يتم تسجيل المعلومات";
    }

  }
  $stmt->close();
}

$sql = "select * from employee where ID = ".$accountId;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $user = mysqli_fetch_array($result,MYSQLI_ASSOC);
} else {
  //header("location: dashboard.php");
}

//$conn->close();

?>

    <div class="container">
      <div class="row">
        <?php
          require_once 'includes/side-bar.php';
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">تحديث معلومات الحساب</h1>

          <form class="form-custom" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?account_id=<?php echo $accountId; ?>" method="post">

            <div class="col-sm-6">

                <label for="">رمز الرظيفي</label>
                <input type="text" class="form-control" value="<?php echo $user['Employee_ID'] ?>" disabled autofocus>

                <label for="">اسم الاول</label>
                <input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo $user['First_Name'] ?>" autofocus>

                <?php
                  if(!empty($firstNameErr)){
                    echo '<div class="invalid-feedback">'.$firstNameErr.'</div>';
                  }
                ?>

                <label for="">اسم الاوسط</label>
                <input type="text" id="middleName" name="middleName" class="form-control" value="<?php echo $user['Middle_Name'] ?>" autofocus>

                <?php
                  if(!empty($middleNameErr)){
                    echo '<div class="invalid-feedback">'.$middleNameErr.'</div>';
                  }
                ?>

                <label for="">اسم الاخير</label>
                <input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo $user['Last_Name'] ?>" autofocus>

                <?php
                  if(!empty($lastNameErr)){
                    echo '<div class="invalid-feedback">'.$lastNameErr.'</div>';
                  }
                ?>

                <label for="">الهوية الوطنية/الاقامة</label>
                <input type="text" class="form-control" name="nationalId" id="nationalId" value="<?php echo $user['National_ID'] ?>" autofocus>

                <?php
                  if(!empty($nationalIdErr)){
                    echo '<div class="invalid-feedback">'.$nationalIdErr.'</div>';
                  }
                ?>

                <button class="btn btn-lg btn-primary btn-block btn-custom" type="submit">تحديث معلومات الحساب</button>
                <?php
                if(!empty($successMsg)){
                  echo '<div class="alert alert-success" style="margin-top: 20px;">'.$successMsg.'</div>';
                }
                ?>
            </div>

          </form>

        </div>
      </div>
    </div>

 <?php
 require_once 'includes/footer.php';
 ?>
