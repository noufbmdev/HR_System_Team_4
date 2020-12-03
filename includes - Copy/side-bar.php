<div class="col-sm-3 col-md-2 sidebar">
  <!--<ul class="nav nav-sidebar">
    <li class="active"><a href="#">Overview <span class="sr-only">(current)</span></a></li>
    <li><a href="#">Reports</a></li>
    <li><a href="#">Analytics</a></li>
    <li><a href="#">Export</a></li>
  </ul>-->
  <div class="user-info">
      <img src="images/img_avatar.png" class="img img-responsive" alt="Avatar"><h3 id="employeename"><?php echo $_SESSION["username"]."(".$_SESSION["role"].")" ?></h3>
      <a href="logout.php"><span class="glyphicon glyphicon-log-out" style="color: fff;" aria-hidden="true"></span></a>
  </div>
  <ul class="nav nav-sidebar">
      <li><a href="dashboard.php"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span><strong>لوحة التحكم</strong></a></li>
      <li><a href="my_account.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><strong>الحساب</strong></a></li>
      <li><a href="account_history.php"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span><strong>الموارد البشرية</strong></a></li>
      <li><a href="leave_request_history.php"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span><strong>طلبات الأجازة</strong></a></li>
      <li><a href="attendance_history.php"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span><strong>الحضور</strong></a></li>
      <li><a href="contract_history.php"><span class="glyphicon glyphicon-file" aria-hidden="true"></span><strong>العقود</strong></a></li>
      <!--<li><a href="logout.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><strong>Logout</strong></a></li>-->
  </ul>

</div>
