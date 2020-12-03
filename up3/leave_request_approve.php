<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if( $_SESSION["loggedin"] == false){
    header("location: login.php");
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

require_once 'config/config.php';

if(($_SESSION['role_id'] == 4 || $_SESSION['role_id'] == 3)){

    $stmt = $conn->prepare("update `leave` set Status = ? where Leave_ID = ? ");
    $stmt->bind_param("si", $status, $leaveId);
    $status = $_GET['status'];
    $leaveId = $_GET['leave_id'];

    $stmt->execute();

    $sql = "SELECT employee.Email, `leave`.Employee_ID FROM `leave` INNER JOIN employee ON `leave`.Employee_ID = employee.ID WHERE `leave`.Leave_ID = ".$leaveId;

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $mail = new PHPMailer();
      $mail->IsSMTP();

      $mail->SMTPDebug  = 0;
      $mail->SMTPAuth   = TRUE;
      $mail->SMTPSecure = "tls";
      $mail->Port       = 587;
      $mail->Host       = "smtp.gmail.com";
      $mail->Username   = "no.reply.adhdhrsystem@gmail.com";
      $mail->Password   = "hrsystem9898";

      $mail->IsHTML(true);
      $mail->AddAddress($row['Email'], "Test");
      $mail->SetFrom("no.reply.adhdhrsystem@gmail.com", "admin");
      $mail->Subject = "طلب الإجازة

       ".$status;
      $content = "طلب الإجازة الخاص بك هو ".$status;

      $mail->MsgHTML($content);
      $mail->Send();
    }

    $stmt->close();
}

$conn->close();

header("location: leave_request_history.php");
exit;

?>
