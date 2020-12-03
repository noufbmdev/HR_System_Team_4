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

if (isset($_GET['get_leaves']) && $_GET['get_leaves']!="") {
	$get_others = 1;
} else {
		$get_others = 0;
}

$empId = $_SESSION['user_id'];

$total_records_per_page = 6;
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
          <h1 class="page-header">سجل طلبات الإجازة</h1>
          <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row" style="margin-top: 30px;">
              <div class="col-sm-2">
                <a href="leave_request_new.php" class="btn btn-contract-custom">إنشاء +</a>
              </div>
              <?php
              if($_SESSION["role_id"] == 3){
              ?>
                  <div class="col-sm-4">
                    <a href="leave_request_history.php?get_leaves=1" class="btn btn-contract-custom">طلبات الاجازة</a>
                  </div>
              <?php
              }
              ?>
            </div>
          </form>
            <?php
              if($get_others == 1){
                $sql1 = "select * from `leave` where Employee_ID IN (select ID from employee where Manager_ID = ".$empId.")";
              }
              else{
                $sql1 = "select * from `leave` where Employee_ID = ".$empId;
              }
              $result1 = $conn->query($sql1);

              $total_records = $result1->num_rows;
              $total_no_of_pages = ceil($total_records / $total_records_per_page);
              $second_last = $total_no_of_pages - 1; // total page minus 1

              if($get_others == 1){
                $sql = "select *, leave_type, employee.First_Name from `leave`
                inner join leave_types on `leave`.Type = leave_types.id
                INNER JOIN employee ON employee.ID = `leave`.Employee_ID
                WHERE `leave`.Employee_ID IN (select ID from employee where Manager_ID = ".$empId.") LIMIT $offset, $total_records_per_page";
              }
              else {
                $sql = "select *, leave_type, employee.First_Name from `leave`
                inner join leave_types on `leave`.Type = leave_types.id
                INNER JOIN employee ON employee.ID = `leave`.Employee_ID
                WHERE `leave`.Employee_ID = ".$empId."  LIMIT $offset, $total_records_per_page";
              }
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                // Fetch all
                $arrLeaves = mysqli_fetch_all($result, MYSQLI_ASSOC);

                foreach ($arrLeaves as $leave) {
                   $startTime = str_replace(".000000","", $leave['Start_Time']);
                   $endTime = str_replace(".000000","", $leave['End_Time']);
                ?>
                <div class="col-sm-4" style="margin-top: 20px;">
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="col-sm-12 leave-type">
                        <?php echo $leave['First_Name'].' - '.$leave['leave_type'] ?>
                      </div>
                      <div class="col-sm-6 leave-dates">
                          <?php echo $leave['Start_Date']; ?>
                          <br/>
                          <?php echo $startTime; ?>
                      </div>
                      <div class="col-sm-6 leave-dates">
                          <?php echo $leave['End_Date']; ?>
                          <br/>
                          <?php echo $endTime; ?>
                      </div>

                      <?php
                      if(($_SESSION['role_id'] == 4 || $_SESSION['role_id'] == 3) && $leave['Status'] == "Pending" && $get_others == 1){
                      ?>
                            <div class="col-sm-6 leave-approve"><a href="leave_request_approve.php?leave_id=<?php echo $leave['Leave_ID']; ?>&status=Approved">مقبول</a></div>
                            <div class="col-sm-6 leave-reject"><a href="leave_request_approve.php?leave_id=<?php echo $leave['Leave_ID']; ?>&status=Rejected">مرفوض</a></div>

                      <?php
                        }

                          else{
                          $leaveClass = "leave-pending";
                          $leaveMsg = "قيد الانتظار";
                          if($leave['Status'] == "Approved"){
                              $leaveClass = "leave-approve";
                              $leaveMsg = "مقبولة";
                          }
                          else if($leave['Status'] == "Rejected"){
                            $leaveClass = "leave-reject";
                            $leaveMsg = "Rejected";
                          }
                      ?>
                          <div class="col-sm-12 <?php echo $leaveClass; ?>">
                            <?php echo $leaveMsg; ?>
                          </div> 
                      <?php
                        }
                      ?>

                      <div class="col-sm-12 leave-reason">
                          <?php echo $leave['Reason']; ?>
                      </div>

                    </div>

                  </div>
                </div>
                <?php
                }
                include_once('includes/pagination.php');
              }
              else {
                echo "لا يوجد معلومات";
              }
              ?>
        </div>
      </div>
    </div>

 <?php
 require_once 'includes/footer.php';
 ?>
