<?php
session_start();

if( $_SESSION["loggedin"] == false){
  header("location: login.php");
  exit;
}

if( isset($_SESSION["role_id"]) && ($_SESSION["role_id"] == 2 || $_SESSION["role_id"] == 3 || $_SESSION["role_id"] == 5 )){
  header("location: not_allowed.php");
  exit;
}

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

// Include config file
require_once "config/config.php";

require_once 'includes/header.php';

require_once 'includes/nav-bar.php';

$where = " where is_active = 0 ";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if($_POST['filter_contract_type'] != ""){
      $where .= " AND Type = '".$_POST['filter_contract_type']."'";
    }
}
?>

    <div class="container">
      <div class="row">
        <?php
          require_once 'includes/side-bar.php';
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">سجل العقود</h1>
          <form class="form-signin" name="formContractsFilter" id="formContractsFilter" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row" style="margin-top: 30px;">
              <div class="col-sm-2">
                <select name="filter_contract_type" class="form-control filter_contract_type">
                  <option value="">نوع العقد</option>
                  <option value="Saudi Employment">سعودي </option>
                  <option value="Non-Saudi Employment">غير سعودي</option>
                </select>
              </div>
              <?php
                if( $_SESSION["role_id"] == 1){
              ?>
              <div class="col-sm-2">
                <a href="contract_new.php" class="btn btn-contract-custom">أنشاء عقد +</a>
              </div>
              <?php
                }
              ?>
            </div>
          </form>
          <div class="table-responsive" style="margin-top: 30px;">
            <?php
              $sql1 = "select c.*, e.Employee_ID, e.First_Name, e.Last_Name, e.National_ID, emp.Employee_ID AS ManagerName from contract c
              left join employee e on c.Contract_ID = e.Contract_ID
              LEFT JOIN employee emp ON emp.ID = e.Manager_ID ".$where;
              $result1 = $conn->query($sql1);

              $total_records = $result1->num_rows;
              $total_no_of_pages = ceil($total_records / $total_records_per_page);
              $second_last = $total_no_of_pages - 1; // total page minus 1

              $sql = "select c.*, e.Employee_ID, e.First_Name, e.Last_Name, e.National_ID, emp.Employee_ID AS ManagerName from contract c
              left join employee e on c.Contract_ID = e.Contract_ID
              LEFT JOIN employee emp ON emp.ID = e.Manager_ID ".$where." LIMIT $offset, $total_records_per_page";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                // Fetch all
                $arrContracts = mysqli_fetch_all($result, MYSQLI_ASSOC);

            ?>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>رمز الوظيفي</th>
                  <th>الاسم</th>
                  <th>المدير</th>
                  <th>نوع العقد</th>
                  <th>المسمى الوظيفي</th>
                  <th>تاريخ البداية</th>
                  <th>تاريخ النهاية</th>
                  <th>الراتب</th>
                  <?php
                   if( $_SESSION["role_id"] == 1){
                  ?>
                  <th>تحديث</th>
                  <th>حذف</th>
                  <?php
                   }
                   ?>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($arrContracts as $contract) {
                ?>
                <tr>
                  <td  style="text-align: center;"><?php echo $contract['Employee_ID']; ?></td>
                  <td><?php echo $contract['First_Name']." ".$contract['Last_Name']."(".$contract['National_ID'].")"; ?></td>
                  <td style="text-align: center;"><?php echo $contract['ManagerName']; ?></td>
                  <td><?php echo $contract['Type']; ?></td>
                  <td><?php echo $contract['Job_Position']; ?></td>
                  <td><?php echo $contract['Start_Date']; ?></td>
                  <td><?php echo $contract['End_Date']; ?></td>
                  <td><?php echo $contract['Salary']; ?></td>
                  <?php
                   if( $_SESSION["role_id"] == 1){
                  ?>
                  <td class="text-center"><a href="contract_extend.php?contract_id=<?php echo $contract['Contract_ID']; ?>"><span class="glyphicon glyphicon-edit"></span></a></td>
                  <td class="text-center"><a href="#" data-id="<?php echo $contract['Contract_ID']; ?>" data-toggle="modal" data-target="#myModal" class="contract-remove"><span class="glyphicon glyphicon-remove"></span></a></td>
                  <?php
                   }
                   ?>
                </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
                <!-- Modal -->
                <div id="myModal" class="modal fade" role="dialog">
                  <div class="modal-dialog">
                  <form class="form-popup" name="formContractRemove" id="formContractRemove" action="contract_remove.php" method="post">
                    <input type="text" id="frmContractId" name="frmContractId" style="display: none;" />
                  </form>
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">حذف العقد</h4>
                      </div>
                      <div class="modal-body">
                        <p>هل تريد حذف هذا العقد؟</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary delete-contract">نعم</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                      </div>
                    </div>

                  </div>
                </div>
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
