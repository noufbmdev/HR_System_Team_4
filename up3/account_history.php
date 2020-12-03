<?php
session_start();
if( $_SESSION["loggedin"] == false){
  header("location: login.php");
  exit;
}

if( isset($_SESSION["role_id"]) && ($_SESSION["role_id"] == 2 || $_SESSION["role_id"] == 3 || $_SESSION["role_id"] == 4)){
  header("location: not_allowed.php");
  exit;
}
// Include config file
require_once "config/config.php";

require_once 'includes/header.php';

require_once 'includes/nav-bar.php';

$where = " where 1=1";

if (isset($_GET['page_no']) && $_GET['page_no']!="") {
	$page_no = $_GET['page_no'];
} else {
		$page_no = 1;
}

$total_records_per_page = 10;
$offset = ($page_no-1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";



if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if($_POST['filter_role'] != ""){
      $where .= " AND r.id = ".$_POST['filter_role'];
    }

    if($_POST['filter_department'] != ""){
      $where .= " AND d.Department_ID = ".$_POST['filter_department'];
    }

    if($_POST['filter_job_position'] != ""){
      $where .= " AND c.Job_Position = '".$_POST['filter_job_position']."'";
    }
}

?>

    <div class="container">
      <div class="row">
        <?php
          require_once 'includes/side-bar.php';
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">سجل الحساب</h1>
          <form class="form-signin" name="formAccounts" id="formAccounts" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row" style="margin-top: 30px;">
              <div class="col-sm-2">
                <select name="filter_role" class="form-control acc-filter-select">
                  <option value="">الدور</option>
                  <?php
                    $sqlR = "select * from roles";
                    $resultR = $conn->query($sqlR);

                    if ($resultR->num_rows > 0) {
                      $arrRoles = mysqli_fetch_all($resultR, MYSQLI_ASSOC);
                      foreach ($arrRoles as $role) {

                        $selected = "";
                        if($_SERVER["REQUEST_METHOD"] == "POST")
                        {
                            if($role['id'] == $_POST['filter_role']){
                              $selected = "selected";
                            }
                        }
                  ?>
                      <option <?php echo $selected; ?> value="<?php echo $role['id']; ?>"><?php echo $role['role_name']; ?></option>
                  <?php
                      }
                    }

                  ?>
                </select>
              </div>
              <div class="col-sm-2">
                <select name="filter_department" class="form-control acc-filter-select">
                  <option value="">القسم</option>
                  <?php
                    $sqlD = "select * from department";
                    $resultD = $conn->query($sqlD);

                    if ($resultD->num_rows > 0) {
                      $arrDepts = mysqli_fetch_all($resultD, MYSQLI_ASSOC);
                      foreach ($arrDepts as $dept) {

                        $selected = "";
                        if($_SERVER["REQUEST_METHOD"] == "POST")
                        {
                            if($dept['Department_ID'] == $_POST['filter_department']){
                              $selected = "selected";
                            }
                        }
                  ?>
                      <option <?php echo $selected; ?> value="<?php echo $dept['Department_ID']; ?>"><?php echo $dept['Name']; ?></option>
                  <?php
                      }
                    }

                  ?>
                </select>
              </div>
              <div class="col-sm-2">
                <select name="filter_job_position" class="form-control acc-filter-select">
                  <option value="">المسمى الوظيفي</option>
                  <?php
                    $sqlJ = "select distinct `Job_Position` from `contract`";
                    $resultJ = $conn->query($sqlJ);

                    if ($resultJ->num_rows > 0) {
                      $arrJobs = mysqli_fetch_all($resultJ, MYSQLI_ASSOC);
                      foreach ($arrJobs as $job) {

                        $selected = "";
                        if($_SERVER["REQUEST_METHOD"] == "POST")
                        {
                            if($job['Job_Position'] == $_POST['filter_job_position']){
                              $selected = "selected";
                            }
                        }
                  ?>
                      <option <?php echo $selected; ?> value="<?php echo $job['Job_Position']; ?>"><?php echo $job['Job_Position']; ?></option>
                  <?php
                      }
                    }

                  ?>
                </select>
              </div>
              <!--<div class="col-sm-2">
                <a href="account_new.php" class="btn btn-contract-custom">Create +</a>
              </div>-->
            </div>
          </form>
          <div class="table-responsive" style="margin-top: 30px;">
            <?php

              $sql1 = "select e.ID as emp_id, e.`First_Name`, e.`Last_Name`, e.`Email`, d.`Name`, e.`Employee_ID`, r.`role_name`, c.`Job_Position` from `employee` e left join `department` d on d.`Department_ID` = e.`Department_ID` left join roles r on r.`id` = e.`Role` left join `contract` c on c.`Contract_ID` = e.`Contract_ID`".$where;
              $result1 = $conn->query($sql1);

              $total_records = $result1->num_rows;
              $total_no_of_pages = ceil($total_records / $total_records_per_page);
              $second_last = $total_no_of_pages - 1; // total page minus 1

              $sql = "select e.ID as emp_id, e.`First_Name`, e.`Last_Name`, e.`Email`, d.`Name`, e.`Employee_ID`, r.`role_name`, c.`Job_Position` from `employee` e left join `department` d on d.`Department_ID` = e.`Department_ID` left join roles r on r.`id` = e.`Role` left join `contract` c on c.`Contract_ID` = e.`Contract_ID`".$where." LIMIT $offset, $total_records_per_page";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                // Fetch all
                $arrEmployees = mysqli_fetch_all($result, MYSQLI_ASSOC);

            ?>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>الاسم</th>
                  <th>الدور</th>
                  <th>القسم</th>
                  <th>المسمى الوظيفي</th>
                  <th>البريد الاكتروني</th>
                  <th>رمز الوظيفي</th>
                  <?php
                    if($_SESSION["role_id"] == 5){
                      echo '<th>Edit</th>';
                    }
                  ?>

                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($arrEmployees as $employee) {
                ?>
                <tr>
                  <td><?php echo $employee['First_Name']." ".$employee['Last_Name']; ?></td>
                  <td><?php echo $employee['role_name']; ?></td>
                  <td><?php echo $employee['Name']; ?></td>
                  <td><?php echo $employee['Job_Position']; ?></td>
                  <td><?php echo $employee['Email']; ?></td>
                  <td style="text-align: center;"><?php echo $employee['Employee_ID']; ?></td>
                  <?php
                  if($_SESSION["role_id"] == 5){
                  ?>
                  <td><a href="account_first_edit.php?account_id=<?php echo $employee['emp_id']; ?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                  <?php
                  }
                  ?>
                </tr>
                <?php
                }
                ?>
              </tbody>
            </table>

                <?php include_once('includes/pagination.php'); ?>

              <?php
              }
              else {
              ?>
              <p>لا توجد سجلات بالمعلومات المحددة</p>
              <?php
              }
              ?>
          </div>
        </div>
      </div>
    </div>

 <?php
 require_once 'includes/footer.php';
 ?>
