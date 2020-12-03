<?php
session_start();

if( $_SESSION["loggedin"] == false){
  header("location: login.php");
  exit;
}

require_once 'includes/header.php';

require_once 'includes/nav-bar.php';
?>

    <div class="container">
      <div class="row">
        <?php
          require_once 'includes/side-bar.php';
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">حسابك غير مصرح  </h1>
        </div>
      </div>
    </div>

 <?php
 require_once 'includes/footer.php';
 ?>
