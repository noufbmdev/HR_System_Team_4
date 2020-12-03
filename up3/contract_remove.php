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

$stmt = $conn->prepare("update `contract` set is_active = 1 where Contract_ID = ? ");
$stmt->bind_param("i", $contractID);
$contractID = $_POST['frmContractId'];
$stmt->execute();
$stmt->close();
$conn->close();   
//echo $contractID;
header("location: contract_history.php");
exit;

?>
 
