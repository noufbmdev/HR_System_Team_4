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

if( isset($_SESSION["role_id"]) && $_SESSION["role_id"] != 1){
  header("location: not_allowed.php");
  exit;
}

require_once 'config/config.php';

require_once 'includes/header.php';

require_once 'includes/nav-bar.php';

$accountId = $_GET['account_id'];

$emailErr = $deptErr = $managerErr =  $roleErr = "";

$successMsg = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{

  $stmt = $conn->prepare("update employee set `Email` = ?, `Department_ID` = ?, `Manager_ID` = ?, `Role` = ? where `ID` = ?");

  $stmt->bind_param("siiii", $email, $departmenId, $managerID, $roleId, $ftAccountId);

  $email = $_POST['email'];
  $departmenId = $_POST['departmenId'];
  $managerID = $_POST["managerID"];
  $roleId = $_POST["role"];
  $ftAccountId = $accountId;

  $error = false;

  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $emailErr = "الرجاء إدخال بريد إلكتروني صحيح";
    $error = true;
  }

  if(empty($departmenId)){
    $deptErr = "الرجاء تحديد القسم";
    $error = true;
  }

  if(empty($managerID)){
    $managerErr = "الرجاء تحديد المدير";
    $error = true;
  }

  if(empty($roleId)){
    $roleErr = "الرجاء ادخال الدور";
    $error = true;
  }
  if($error == false){

    if ($stmt->execute()) {

      $successMsg = "تم ادخال المعلومات بنجاح";

    } else {
      $errorMsg = "لم يتم تحديث المعلومات";
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
          <h1 class="page-header">تغيير معلومات المستخدم<</h1>

          <form class="form-custom" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?account_id=<?php echo $accountId; ?>" method="post">

            <div class="col-sm-6">

                <label for="Email">البريد الإلكتروني</label>
                <input type="text" id="email" name="email" class="form-control" value="<?php echo $user['Email']; ?>" autofocus>
                <?php
                  if(!empty($emailErr)){
                    echo '<div class="invalid-feedback">'.$emailErr.'</div>';
                  }
                ?>

                <label for="ContractType">قسم</label>
                <select class="form-control" name="departmenId">
                  <?php
                    $sqlD = "select * from department";
                    $resultD = $conn->query($sqlD);

                    if ($resultD->num_rows > 0) {
                      $departments = mysqli_fetch_all($resultD, MYSQLI_ASSOC);
                      foreach ($departments as $depatment) {
                  ?>
                        <option value="<?php echo $depatment['Department_ID'] ?>"><?php echo $depatment['Name']; ?></option>
                  <?php
                      }
                    }
                  ?>
                </select>

                <label for="ContractType">المدير</label>
                <select class="form-control" name="managerID">
                  <?php
                    $sqlU = "select * from employee where Employee_ID != ''";
                    $resultU = $conn->query($sqlU);

                    if ($resultU->num_rows > 0) {
                      $managers = mysqli_fetch_all($resultU, MYSQLI_ASSOC);
                      foreach ($managers as $manager) {
                  ?>
                        <option value="<?php echo $manager['ID'] ?>"><?php echo $manager['Employee_ID']; ?></option>
                  <?php
                      }
                    }
                  ?>
                </select>

                <label for="ContractType">الدور</label>
                <select class="form-control" name="role">
                  <?php
                    $sqlRole = "select * from roles";
                    $resultRole = $conn->query($sqlRole);

                    if ($resultRole->num_rows > 0) {
                      $roles = mysqli_fetch_all($resultRole, MYSQLI_ASSOC);
                      foreach ($roles as $role) {
                  ?>
                        <option value="<?php echo $role['id'] ?>"><?php echo $role['role_name']; ?></option>
                  <?php
                      }
                    }
                  ?>
                </select>

                <button class="btn btn-lg btn-primary btn-block btn-custom" type="submit">تحديث المعلومات</button>
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
