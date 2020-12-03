<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if( $_SESSION["loggedin"] == false){
  header("location: login.php");
  exit;
}

require_once 'config/config.php';

require_once 'includes/header.php';

require_once 'includes/nav-bar.php';

$dateErr = $startTimeErr = $endTimeErr = "";

$successMsg = "";

$empId = $_SESSION['user_id'];

date_default_timezone_set('Asia/Kuwait');
$today = date('Y-m-d');

if($_SERVER["REQUEST_METHOD"] == "POST")
{

  $error = false;

  $stmt = $conn->prepare("insert into `timesheet`(`Employee_ID`, `Date`, `Start_Time`, `End_Time`) values(?, ?, ?, ?)");

  $stmt->bind_param("isss", $employeeId, $date, $startTime, $endTime);


  $date = $_POST['strDate'];
  $startTime = $_POST['startTime'];
  $endTime = $_POST["endTime"];
  $employeeId = $empId;

  if(empty($date)){
    $dateErr = "الرجاء إدخال التاريخس";
    $error = true;
  }

  if(empty($startTime)){
    $startTimeErr = "الرجاء إدخال وقت البداية";
    $error = true;
  }
  else if(strtotime($startTime)>=strtotime($endTime)){
    $startTimeErr = "الوقت غير صحيح";
    $error = true;
  }

  if(empty($endTime)){
    $endTimeErr = "الرجاء إدخال وقت النهاية";
    $error = true;
  }
  else if(strtotime($startTime)>=strtotime($endTime)){
    $endTimeErr = "الوقت غير صحيح";
    $error = true;
  }

  if($error == false){
    $sql = "SELECT * FROM `timesheet` WHERE Date = '".$today."' AND Employee_ID  = ".$empId;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $errorMsg = "تمت إضافة الحضور لهذا اليوم";
      $error = true;
    }
    else {
      if ($stmt->execute()) {
        $successMsg = "تم تسجيل الحضور";

      } else {
        $errorMsg = "لم يتم التسجيل";
      }
    }

  }
  $stmt->close();
  $conn->close();
}

?>

    <div class="container">
      <div class="row">
        <?php
          require_once 'includes/side-bar.php';
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">إضافة حضور</h1>

          <form class="form-custom" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="col-sm-6 col-sm-offset-3">
                <label for="StartDate">تاريخ البداية</label>
                <input type="date" readonly id="strDate" name="strDate" class="form-control" value="<?php echo isset($_POST['strDate']) ? htmlspecialchars($_POST['strDate'], ENT_QUOTES) : $today; ?>" autofocus onkeydown="return false">
                <?php
                  if(!empty($dateErr)){
                    echo '<div class="invalid-feedback">'.$dateErr.'</div>';
                  }
                ?>

                <label for="EndDate">وقت البداية</label>
                <input type="time" id="startTime" name="startTime" class="form-control" value="<?php echo isset($_POST['startTime']) ? htmlspecialchars($_POST['startTime'], ENT_QUOTES) : ''; ?>" autofocus onkeydown="return false">
                <?php
                  if(!empty($startTimeErr)){
                    echo '<div class="invalid-feedback">'.$startTimeErr.'</div>';
                  }
                ?>

                <label for="EndDate">وقت النهاية</label>
                <input type="time" id="endTime" name="endTime" class="form-control" value="<?php echo isset($_POST['endTime']) ? htmlspecialchars($_POST['endTime'], ENT_QUOTES) : ''; ?>" autofocus onkeydown="return false">
                <?php
                  if(!empty($endTimeErr)){
                    echo '<div class="invalid-feedback">'.$endTimeErr.'</div>';
                  }
                ?>

                <button class="btn btn-lg btn-primary btn-block btn-custom" type="submit">إرسال</button>

                <?php
                if(!empty($successMsg)){
                  echo '<div class="alert alert-success" style="margin-top: 20px;">'.$successMsg.'</div>';
                }

                if(!empty($errorMsg)){
                  echo '<div class="alert alert-danger" style="margin-top: 20px;">'.$errorMsg.'</div>';
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
