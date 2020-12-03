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

$excuseErr = "";

$successMsg = "";

$id = $_GET['id'];

if($_SERVER["REQUEST_METHOD"] == "POST")
{

  $error = false;

  $stmt = $conn->prepare("update `timesheet` set `Excuse` = ? where Timesheet_ID = ?");

  $stmt->bind_param("si", $excuse, $timesheetId);


  $excuse = $_POST['strExcuse'];
  $timesheetId = $id;

  if(empty($excuse)){
    $excuseErr = "الرجاء إدخال العذر";
    $error = true;
  }

  if($error == false){

    if ($stmt->execute()) {
      $successMsg = "تم التسجيل بنجاح";

    } else {
      $errorMsg = "لم يتم التسجيل";
    }

  }
  $stmt->close();
}

$sql = "select * from timesheet where Timesheet_ID = ".$id;
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $timesheet = mysqli_fetch_array($result,MYSQLI_ASSOC);
  } else {
    //header("location: dashboard.php");
  }

  $conn->close();

?>

    <div class="container">
      <div class="row">
        <?php
          require_once 'includes/side-bar.php';
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">إضافة عذر التأخير</h1>

          <form class="form-custom" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $id; ?>" method="post">

            <div class="col-sm-6">
                <?php
                $startTime = strtotime($timesheet['Start_Time']);
                $endTime = strtotime($timesheet['End_Time']);
                ?>
                <label for="">تاريخ البداية</label>
                <input type="text" id="strDate" name="strDate" class="form-control" value="<?php echo $timesheet['Date']; ?>" readonly>

                <label for="">وقت البداية</label>
                <input type="text" id="startTime" name="startTime" class="form-control" value="<?php echo date('H:i A', $startTime); ?>" readonly>

                <label for="">وقت النهاية</label>
                <input type="text" id="endTime" name="endTime" class="form-control" value="<?php echo date('H:i A', $endTime); ?>" readonly>

                <label for="">العذر</label>
                <textarea class="form-control" name="strExcuse"><?php echo isset($_POST['strExcuse']) ? htmlspecialchars($_POST['strExcuse'], ENT_QUOTES) : $timesheet['Excuse']; ?></textarea>
                <?php
                  if(!empty($excuseErr)){
                    echo '<div class="invalid-feedback">'.$excuseErr.'</div>';
                  }
                ?>

                <button class="btn btn-lg btn-primary btn-block" type="submit">إرسال</button>

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
