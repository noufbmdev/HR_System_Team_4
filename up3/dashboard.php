<?php

session_start();

if( $_SESSION["loggedin"] == false){
  header("location: login.php");
  exit;
}
require_once "config/config.php";

require_once 'includes/header.php';

require_once 'includes/nav-bar.php';

$empId = $_SESSION['user_id'];

$sqlLeaves = "select *, leave_type, employee.First_Name from `leave`
inner join leave_types on `leave`.Type = leave_types.id
INNER JOIN employee ON employee.ID = `leave`.Employee_ID
WHERE `leave`.Status = 'Approved' and `leave`.Employee_ID = ".$empId;
$resultLeaves = $conn->query($sqlLeaves);

$numberOfLeavesTaken = $resultLeaves->num_rows;
?>

    <div class="container">
      <div class="row">
        <?php
          require_once 'includes/side-bar.php';
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Dashboard</h1>
          <div class="col-md-5">

          <div class="row text-center" style="margin-bottom: 10px;">
              <h4><strong>Welcome back.<?php echo $_SESSION["username"];?><br/>Insert your attendance here.</strong></h4>
              <a href="attendance_new.php" class="btn btn-primary">GO</a>
          </div>

            <div class="row">
              <div class="panel" style="background: #e7eaf0;padding-bottom:10px;">
                <div class="panel-heading">Days Taken</div>
                <div class="panel-body" style="height: 50px; font-size: 40px;"><?php echo $numberOfLeavesTaken; ?>/30</div>
              </div>
            </div>

            <div class="row" style="background: #323376;">
              <h4 style="text-align: center; color: #fff;">Leaves Taken <?php echo $numberOfLeavesTaken; ?></h4>
            </div>
          <div class="row">
            <!-- Carousel container -->
            <div id="my-pics" class="carousel slide" data-ride="carousel" style="width:100%;margin-top:10px;">
              <!-- Content -->
              <div class="carousel-inner" role="listbox">
                  <?php
                    $arrLeaves = mysqli_fetch_all($resultLeaves, MYSQLI_ASSOC);
                    $x = 0;
                    foreach ($arrLeaves as $leave) {

                        $startTime = str_replace(".000000","", $leave['Start_Time']);
                        $endTime = str_replace(".000000","", $leave['End_Time']);
                  ?>
                  <div class="item <?php if($x == 0){ echo 'active'; } ?>">
                    <div class="">
                      <div class="col-md-12 text-center dashboardLeavesItem">
                        <h4><strong><?php echo $leave['leave_type'] ?></strong></h4>
                      </div>
                      <div class="col-md-6 text-center dashboardLeavesItem">
                            <p><?php echo $leave['Start_Date']; ?>
                            <br/>
                            <?php echo $startTime; ?></p>
                      </div>
                      <div class="col-md-6 text-center dashboardLeavesItem">
                            <p><?php echo $leave['End_Date']; ?>
                            <br/>
                            <?php echo $endTime; ?></p>
                      </div>
                    </div>
                  </div>
                  <?php
                    $x++;
                    }
                  ?>
              </div>
              <!-- Previous/Next controls -->
              <a style="background: none;" class="right carousel-control" href="#my-pics" role="button" data-slide="prev">
              <span class="icon-prev" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
              </a>
              <a style="background: none;" class="left carousel-control" href="#my-pics" role="button" data-slide="next">
              <span class="icon-next" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
              </a>
            </div>
          </div>

          </div>

          <div class="col-md-7">
          <table data-id="flexbox-bar-graph" class="dashboard-table" style="background: #323376;  border-radius: 15px;">
              <?php
                $sunday = strtotime("last sunday");
                $sunday = date('w', $sunday)==date('w') ? $sunday+7*86400 : $sunday;

                $monday = strtotime(date("Y-m-d",$sunday)." +6 days");
                $this_week_sd = date("d-m-Y",$sunday);
                $this_week_ed = date("d-m-Y",$monday);

              ?>
              <caption style="color: #fff;">Work Week<br/>From <?php echo $this_week_sd; ?> to <?php echo $this_week_ed; ?></caption>

              <tbody style="background: #323376;">
                <?php
                  for($i=1; $i<=7; $i++ ){

                    if($i==1){
                      $day = "Sun";
                      $today = date('w', $monday)==date('w') ? $monday+7*86400 : $sunday;
                    }
                    else if($i==2){
                      $day = "الإثنين";
                      $today = strtotime(date("Y-m-d",$sunday)." +1 days");
                    }
                    else if($i==3){
                      $day = "Tue";
                      $today = strtotime(date("Y-m-d",$sunday)." +2 days");
                    }
                    else if($i==4){
                      $day = "Wed";
                      $today = strtotime(date("Y-m-d",$sunday)." +3 days");
                    }
                    else if($i==5){
                      $day = "Thu";
                      $today = strtotime(date("Y-m-d",$sunday)." +4 days");
                    }
                    else if($i==6){
                      $day = "Fri";
                      $today = strtotime(date("Y-m-d",$sunday)." +5 days");
                    }
                    else if($i==7){
                      $day = "Sat";
                      $today = strtotime(date("Y-m-d",$sunday)." +6 days");
                    }

                    $todayDate = date("Y-m-d",$today);

                    $sqlTimes = "SELECT * FROM timesheet WHERE Date = '".$todayDate."' AND Employee_ID = ".$empId;

                    $resultTimes = $conn->query($sqlTimes);

                    if($resultTimes->num_rows > 0)
                    {
                      $arrTimes = mysqli_fetch_all($resultTimes, MYSQLI_ASSOC);
                      foreach ($arrTimes as $timesheet) {
                        $startTime = strtotime($timesheet['Start_Time']);
                        $endTime = strtotime($timesheet['End_Time']);
                      }
                      $hours = round(($endTime-$startTime)/3600, 2);
                    }
                    else {
                      $hours = 0;
                    }
                ?>
                <tr>
                  <th style="color: #fff !important;"><?php echo $day; ?></th>
                  <td>
                    <span style="--data-set:<?php echo $hours; ?>/12;"></span>
                    <p style="color: #fff;"><?php echo $hours; ?></p>
                  </td>
                </tr>
                <?php
                  }
                ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

 <?php
 require_once 'includes/footer.php';
 ?>
