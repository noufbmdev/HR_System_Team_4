<?php
session_start();

if( $_SESSION["loggedin"] == false){
  header("location: login.php");
  exit;
}

if (isset($_GET['page_no']) && $_GET['page_no']!="") {
	$page_no = $_GET['page_no'];
} else {
		$page_no = 1;
}
$total_records_per_page = 5;
$offset = ($page_no-1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";

// Include config file
require_once "config/config.php";

require_once 'includes/header.php';

require_once 'includes/nav-bar.php';
?>

    <div class="container">
      <div class="row">
        <?php
          require_once 'includes/side-bar.php';
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">سجل الحضور</h1>
          <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row" style="margin-top: 30px;">
              <div class="col-sm-2">
                <a href="attendance_new.php" class="btn btn-contract-custom">إنشاء +</a>
              </div>
            </div>
          </form>
          <div class="table-responsive" style="margin-top: 30px;">
            <?php
              $sql1 = "select * from timesheet where Employee_ID = ".$_SESSION['user_id'];
              $result1 = $conn->query($sql1);

              $total_records = $result1->num_rows;
              $total_no_of_pages = ceil($total_records / $total_records_per_page);
              $second_last = $total_no_of_pages - 1; // total page minus 1

              $sql = "select * from timesheet where Employee_ID = ".$_SESSION['user_id']." LIMIT $offset, $total_records_per_page";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                // Fetch all
                $arrTimes = mysqli_fetch_all($result, MYSQLI_ASSOC);

            ?>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>التاريخ</th>
                  <th>وقت البداية</th>
                  <th>وقت النهاية</th>
                  <th>مجموع ساعات العمل</th>
                  <th>ساعات عمل الإضافية</th>
                  <th>تحديث</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($arrTimes as $timesheet) {
                  $startTime = strtotime($timesheet['Start_Time']);
                  $endTime = strtotime($timesheet['End_Time']);
                  $realEndTime = strtotime('16:00');
                ?>
                <tr>
                  <td><?php echo $timesheet['Date']; ?></td>
                  <td><?php echo date('H:i A', $startTime); ?></td>
                  <td><?php echo date('H:i A', $endTime); ?></td>
                  <td><?php echo round(($endTime-$startTime)/3600, 2); ?></td>
                  <td><?php if($endTime>$realEndTime){ echo round(($endTime-$realEndTime)/3600,2); } ?></td>
                  <?php
                  if(round(($endTime-$startTime)/3600, 2) < 8 && strtotime("now") < strtotime(date('Y-m-d 16:00'))  ){
                  ?>
                    <td><a href="attendance_excuse.php?id=<?php echo $timesheet['Timesheet_ID']; ?>"><span class="glyphicon glyphicon-edit"></span></a></td>
                  <?php
                  }
                  else{
                  ?>
                    <td></td>
                  <?php
                  }
                  ?>

                </tr>
                <?php
                }
                ?>
              </tbody>
            </table>

              <?php
              include_once('includes/pagination.php');
              }
              ?>
          </div>
        </div>
      </div>
    </div>

 <?php
 require_once 'includes/footer.php';
 ?>
