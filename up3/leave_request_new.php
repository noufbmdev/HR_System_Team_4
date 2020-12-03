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

$reasonErr = $startTimeErr = $endTimeErr = $startDateErr = $endDateErr = $typeErr = "";

$successMsg = "";

$empId = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

if($_SERVER["REQUEST_METHOD"] == "POST")
{

  $error = false;

  $stmt = $conn->prepare("insert into `leave`(`Employee_ID`, `Reason`, `Status`, `Start_Time`, `End_Time`, `Start_Date`, `End_Date`, `Type`) values(?, ?, ?, ?, ?, ?, ?, ?)");

  $stmt->bind_param("issssssi", $employeeId, $reason, $status, $startTime, $endTime, $startDate, $endDate, $type);


  $reason = $_POST['reason'];
  $status = 'Pending';
  $startTime = $_POST["startTime"];
  $endTime = $_POST["endTime"];
  $startDate = $_POST["startDate"];
  $endDate = $_POST["endDate"];
  $type = $_POST["type"];
  $employeeId = $empId;

  if(empty($reason) ){
    $reasonErr = "الرجاء إدخال السبب";
    $error = true;
  }
  else if (strlen($reason) > 15){
    $reasonErr = "الرجاء إدخال أحرف أقل";
    $error = true;
  }

  if(empty($startTime)){
    $startTimeErr = "الرجاء إدخال وقت البداية";
    $error = true;
  }
  else{
    if($startDate == $endDate){
        if(strtotime($startTime) >= strtotime($endTime) ){
          $startTimeErr = "الوقت غير صحيح";
          $error = true;
        }
    }
  }

  if(empty($endTime)){
    $endTimeErr = "الرجاء إدخال وقت النهاية";
    $error = true;
  }
  else{
    if($startDate == $endDate){
        if(strtotime($startTime) >= strtotime($endTime) ){
          $endTimeErr = "الوقت غير صحيح";
          $error = true;
        }
    }
  }

  if(empty($startDate)){
    $startDateErr = "الرجاء إدخال تاريخ البداية";
    $error = true;
  }
  else if($startDate>$endDate) {
    $startDateErr = "الرجاء إدخال تواريخ صحيحة";
    $error = true;
  }

  if(empty($endDate)){
    $endDateErr = "الرجاء إدخال تاريخ النهاية";
    $error = true;
  }
  else if($startDate>$endDate) {
    $endDateErr = "الرجاء إدخال تواريخ صحيحة";
    $error = true;
  }

  if(empty($type)){
    $typeErr = "الرجاء إدخال نوع الغياب";
    $error = true;
  }

  if($error == false){

    if ($stmt->execute()) {

      $successMsg = "تم تسجيل الغياب بنجاح";

    } else {
      $errorMsg = "لم يتم تسجيل الغياب";
    }

  }
  $stmt->close();
}

?>

    <div class="container">
      <div class="row">
        <?php
          require_once 'includes/side-bar.php';
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">إنشاء طلب إجازة</h1>

          <form class="form-custom" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="col-sm-6">

                <label for="">تاريخ البداية</label>
                <input type="date" id="startDate" name="startDate" class="form-control" value="<?php echo isset($_POST['startDate']) ? htmlspecialchars($_POST['startDate'], ENT_QUOTES) : ''; ?>" autofocus onkeydown="return false">
                <?php
                  if(!empty($startDateErr)){
                    echo '<div class="invalid-feedback">'.$startDateErr.'</div>';
                  }
                ?>

                <label for="">تاريخ النهاية</label>
                <input type="date" id="endDate" name="endDate" class="form-control" value="<?php echo isset($_POST['endDate']) ? htmlspecialchars($_POST['endDate'], ENT_QUOTES) : ''; ?>" autofocus onkeydown="return false">
                <?php
                  if(!empty($endDateErr)){
                    echo '<div class="invalid-feedback">'.$endDateErr.'</div>';
                  }
                ?>

                <label for="">وقت البداية</label>
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

                <label for="ContractType">نوع الغياب</label>
                <select class="form-control" name="type" >
                  <?php
                    $sqlD = "select * from leave_types";
                    $resultD = $conn->query($sqlD);

                    if ($resultD->num_rows > 0) {
                      $types = mysqli_fetch_all($resultD, MYSQLI_ASSOC);
                      foreach ($types as $leaveType) {
                  ?>
                        <option value="<?php echo $leaveType['id'] ?>"><?php echo $leaveType['leave_type']; ?></option>
                  <?php
                      }
                    }
                  ?>
                </select>

                <label for="">السبب</label>
                <input type="text" id="reason" name="reason" class="form-control" value="<?php echo isset($_POST['reason']) ? htmlspecialchars($_POST['reason'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($reasonErr)){
                    echo '<div class="invalid-feedback">'.$reasonErr.'</div>';
                  }
                ?>

                <button class="btn btn-lg btn-primary btn-block btn-custom" type="submit">إرسال</button>

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
