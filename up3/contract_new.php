<?php

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

if( isset($_SESSION["role_id"]) && $_SESSION["role_id"] != 1){
  header("location: not_allowed.php");
  exit;
}

require_once 'config/config.php';

require_once 'includes/header.php';

require_once 'includes/nav-bar.php';

$firstNameErr = $middleNameErr = $lastNameErr = $streetErr = $neighborhoodErr = $cityErr = $genderErr = $nationalityErr = $emailErr = $nationalIDErr = $phoneNumberErr = "";

$startDateErr = $endDateErr = $jobPositionErr = $salaryErr =  $housingAllowanceErr = $transportAllowanceErr = "";

$successMsg = "";
$errorMsg = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{

   $error = false;

  $stmt = $conn->prepare("insert into `contract`(`Type`, `Job_Position`, `Salary`, `Housing_Allowance`, `Transportation_Allowance`, `Start_Date`, `End_Date`) values(?, ?, ?, ?, ?, ?, ?)");

  $stmt->bind_param("ssdiiss", $type, $jobPosition, $salary, $housingAllowance, $transportAllowance, $startDate, $endDate);

  $stmt1 = $conn->prepare("insert into `employee` (`Contract_ID`, `Manager_ID`, `Department_ID`, `Phone Number`, `Email`, `National_ID`, `First_Name`, `Middle_Name`, `Last_Name`, `Street`, `Neighborhood`, `City`, `Gender`, `Nationality`, `Role`, `Password`) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

  $stmt1->bind_param("iiiissssssssssis", $contractId, $managerId, $departmentId, $phoneNumber, $email, $nationalID, $firstName, $middleName, $lastName, $street, $neighborhood, $city, $gender, $nationality, $roleId, $password);

  $stmt2 = $conn->prepare("update employee set `Employee_ID` = ? where `ID` = ?");

  $stmt2->bind_param("si", $employeeId, $empId);


  $firstName = $_POST['inputFirstName'];
  $lastName = $_POST['inputLastName'];
  $middleName = $_POST["inputMiddleName"];
  $street = $_POST["inputStreet"];
  $neighborhood = $_POST["inputNeighborhood"];
  $city = $_POST["inputCity"];
  $gender = $_POST["inputGender"];
  $nationality = $_POST["inputNationality"];
  $email = $_POST["inputEmail"];
  $nationalID = $_POST["inputNationalID"];
  $phoneNumber = $_POST["inputPhoneNumber"];
  $managerId = $_POST["managerID"];
  $departmentId = $_POST["departmenId"];
  $roleId = $_POST["role"];
  $password = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);

  $type = $_POST['ContractType'];
  $startDate = $_POST['inputStartDate'];
  $endDate = $_POST["inputEndDate"];
  $jobPosition = $_POST["inputJobTitle"];
  $housingAllowance = $_POST["inputHousingAllowance"];
  $transportAllowance = $_POST["inputTransportationAllowance"];
  $salary = $_POST["inputSalary"];

  if(empty($firstName)){
    $firstNameErr = "الرجاء ادخل الاسم الاول";
    $error = true;
  }

  if(empty($lastName)){
    $lastNameErr = "الرجاء ادخل الاسم الاخير";
    $error = true;
  }

  if(empty($middleName)){
    $middleNameErr = "الرجاء ادخل الاسم الاوسط";
    $error = true;
  }

  if(empty($street)){
    $streetErr = "الرجاء ادخل الشارع";
    $error = true;
  }

  if(empty($neighborhood)){
    $neighborhoodErr = "الرجاء ادخل الالحي";
    $error = true;
  }

  if(empty($city)){
    $cityErr = "الرجاء ادخل المدينة";
    $error = true;
  }

  if(empty($gender)){
    $genderErr = "الرجاء ادخل الجنس";
    $error = true;
  }

  if(empty($nationality)){
    $nationalityErr = "الرجاء ادخل الجنسية";
    $error = true;
  }

  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $emailErr = "الرجاء ادخل بريد الالكتروني الصحيح";
    $error = true;
  }

  if(strlen($nationalID) != 10 && !is_numeric($nationalID) ){
    $nationalIDErr = "الرجاء ادخل الهوية/الاقامة الصحيحة";
    $error = true;
  }

  if(strlen($phoneNumber) < 10 && !is_numeric($phoneNumber)){
    $phoneNumberErr = "الرجاء ادخل رقم الهاتف الصحيح";
    $error = true;
  }

  if(empty($startDate)){
    $startDateErr = "الرجاء إدخال تاريخ البدء";
    $error = true;
  }
  else if($startDate>$endDate){
    $startDateErr = "يجب أن يكون تاريخ البدء قبل من تاريخ الانتهاء";
    $error = true;
  }

  if(empty($endDate)){
    $endDateErr = "الرجاء إدخال تاريخ الانتهاء";
    $error = true;
  }

  if(empty($jobPosition)){
    $jobPositionErr = "الرجاء إدخال المسمى الوظيفي";
    $error = true;
  }

  if(empty($salary)){
    $salaryErr = "الرجاء إدخال الراتب";
    $error = true;
  }

  if(empty($housingAllowance)){
    $housingAllowanceErr = "الرجاء إدخال بدل السكن";
    $error = true;
  }

  if(empty($transportAllowance)){
    $transportAllowanceErr = "الرجاء إدخال بدل النقل";
    $error = true;
  }

  if($error == false){

    /* check for email, national id, phone number */
    $sqlCheck = "select * from employee where Email = '".$email."' OR National_ID = '".$nationalID."' OR `Phone Number` = ".$phoneNumber;
    $resultCheck = $conn->query($sqlCheck);

    if ($resultCheck->num_rows > 0) {
      $error = true;
      $errorMsg = "رقم الهاتف او الهوية/الاقامه او البريد الالكتروني مدخله من قبل";
    }else{

      if ($stmt->execute()) {
        $contractId = $stmt->insert_id;

        if($stmt1->execute()){
          $empId = $stmt1->insert_id;
          $employeeId = 'E'.$empId;

          $stmt2->execute();

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
          $mail->AddAddress($email, "Test");
          $mail->SetFrom("bhagyamadh892@gmail.com", "admin");
          $mail->Subject = "User Account Details";
          $content = "<b>مرحبا بك لجمعية إشراق. الرجاء استخدام كلمة المرور لتسجيل دخول لمنصة الموارد البشرية<br/>UserId: ".$employeeId."<br/>كلمة المرور:  ".$password."</b>";

          $mail->MsgHTML($content);
          $mail->Send();


          $successMsg = "تم تحديث المعلرمات بنجاح";
        }

      } else {
        $errorMsg = "لم يتم تحديث المعلومات";
      }

    }

  }
  $stmt->close();
  $stmt1->close();
  $stmt2->close();
  //$conn->close();
}

?>

    <div class="container">
      <div class="row">
        <?php
          require_once 'includes/side-bar.php';
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">إنشاء عقد جديد</h1>

          <form class="form-custom" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="col-sm-6">
                <label for="FirstName">اسم الاول</label>
                <input type="text" id="inputFirstName" name="inputFirstName" class="form-control" value="<?php echo isset($_POST['inputFirstName']) ? htmlspecialchars($_POST['inputFirstName'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($firstNameErr)){
                    echo '<div class="invalid-feedback">'.$firstNameErr.'</div>';
                  }
                ?>

                <label for="MiddleName">اسم الاوسط</label>
                <input type="text" id="inputMiddleName" name="inputMiddleName" class="form-control" value="<?php echo isset($_POST['inputMiddleName']) ? htmlspecialchars($_POST['inputMiddleName'], ENT_QUOTES) : ''; ?>"  autofocus>
                <?php
                  if(!empty($middleNameErr)){
                    echo '<div class="invalid-feedback">'.$middleNameErr.'</div>';
                  }
                ?>

                <label for="LastName">اسم الاخير</label>
                <input type="text" id="inputLastName" name="inputLastName" class="form-control" value="<?php echo isset($_POST['inputLastName']) ? htmlspecialchars($_POST['inputLastName'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($lastNameErr)){
                    echo '<div class="invalid-feedback">'.$lastNameErr.'</div>';
                  }
                ?>

                <label for="LastName">البريد الالكتروني</label>
                <input type="text" id="inputEmail" name="inputEmail" class="form-control" value="<?php echo isset($_POST['inputEmail']) ? htmlspecialchars($_POST['inputEmail'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($emailErr)){
                    echo '<div class="invalid-feedback">'.$emailErr.'</div>';
                  }
                ?>

                <label for="NationalID">رقم الهوية/الاقامة</label>
                <input type="text" id="inputNationalID" name="inputNationalID" class="form-control" value="<?php echo isset($_POST['inputNationalID']) ? htmlspecialchars($_POST['inputNationalID'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($nationalIDErr)){
                    echo '<div class="invalid-feedback">'.$nationalIDErr.'</div>';
                  }
                ?>

                <label for="Sex">الجنس</label>
                <select class="form-control" name="inputGender">
                  <option value="Male">ذكر</option>
                  <option value="Female">انثى</option>
                </select>

                <label for="Nationality">الجنسية</label>
                <input type="text" id="inputNationality" name="inputNationality" class="form-control" value="<?php echo isset($_POST['inputNationality']) ? htmlspecialchars($_POST['inputNationality'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($nationalityErr)){
                    echo '<div class="invalid-feedback">'.$nationalityErr.'</div>';
                  }
                ?>

                <label for="Neighborhood">الحي</label>
                <input type="text" id="inputNeighborhood" name="inputNeighborhood" class="form-control" value="<?php echo isset($_POST['inputNeighborhood']) ? htmlspecialchars($_POST['inputNeighborhood'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($neighborhoodErr)){
                    echo '<div class="invalid-feedback">'.$neighborhoodErr.'</div>';
                  }
                ?>

                <label for="Street">الشارع</label>
                <input type="text" id="inputStreet" name="inputStreet" class="form-control" value="<?php echo isset($_POST['inputStreet']) ? htmlspecialchars($_POST['inputStreet'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($streetErr)){
                    echo '<div class="invalid-feedback">'.$streetErr.'</div>';
                  }
                ?>

                <label for="Street">المدينة</label>
                <input type="text" id="inputCity" name="inputCity" class="form-control" value="<?php echo isset($_POST['inputCity']) ? htmlspecialchars($_POST['inputCity'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($cityErr)){
                    echo '<div class="invalid-feedback">'.$cityErr.'</div>';
                  }
                ?>

                <label for="PhoneNumber">رقم الهاتف</label>
                <input type="text" id="inputPhoneNumber" name="inputPhoneNumber" class="form-control" value="<?php echo isset($_POST['inputPhoneNumber']) ? htmlspecialchars($_POST['inputPhoneNumber'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($phoneNumberErr)){
                    echo '<div class="invalid-feedback">'.$phoneNumberErr.'</div>';
                  }
                ?>
            </div>

            <div class="col-sm-6">

                <label for="ContractType">نوع العقد</label>
                <select class="form-control" name="ContractType">
                  <option value="Saudi Employment">سعودي</option>
                  <option value="Non-Saudi Employment">غير سعودي</option>
                </select>

                <label for="StartDate">تاريخ البداية</label>
                <input type="date" id="inputStartDate" name="inputStartDate" class="form-control" value="<?php echo isset($_POST['inputStartDate']) ? htmlspecialchars($_POST['inputStartDate'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($startDateErr)){
                    echo '<div class="invalid-feedback">'.$startDateErr.'</div>';
                  }
                ?>

                <label for="EndDate">تاريخ النهاية</label>
                <input type="date" id="inputEndDate" name="inputEndDate" class="form-control" value="<?php echo isset($_POST['inputEndDate']) ? htmlspecialchars($_POST['inputEndDate'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($endDateErr)){
                    echo '<div class="invalid-feedback">'.$endDateErr.'</div>';
                  }
                ?>

                <label for="JobTitle">المسمى الوظيفي</label>
                <input type="text" id="inputJobTitle" name="inputJobTitle" class="form-control" value="<?php echo isset($_POST['inputJobTitle']) ? htmlspecialchars($_POST['inputJobTitle'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($jobPositionErr)){
                    echo '<div class="invalid-feedback">'.$jobPositionErr.'</div>';
                  }
                ?>

                <label for="ContractType">القسم</label>
                <select class="form-control" name="departmenId">
                  <?php
                    $sqlD = "select * from department";
                    $resultD = $conn->query($sqlD);

                    if ($resultD->num_rows > 0) {
                      $departments = mysqli_fetch_all($resultD, MYSQLI_ASSOC);
                      foreach ($departments as $depatment) {
                  ?>
                        <option value="<?php echo $depatment['Department_ID'] ?>"><?php echo $depatment['Name']; ?></option>
                  <?php
                      }
                    }
                  ?>
                </select>

                <label for="ContractType">المدير</label>
                <select class="form-control" name="managerID">
                  <option value="0">لا يوجد</option>
                  <?php
                    $sqlU = "select * from employee";
                    $resultU = $conn->query($sqlU);

                    if ($resultU->num_rows > 0) {
                      $managers = mysqli_fetch_all($resultU, MYSQLI_ASSOC);
                      foreach ($managers as $manager) {
                  ?>
                        <option value="<?php echo $manager['ID'] ?>"><?php echo $manager['Employee_ID']; ?></option>
                  <?php
                      }
                    }
                  ?>
                </select>


                <label for="ContractType">الدور</label>
                <select class="form-control" name="role">
                  <?php
                    $sqlRole = "select * from roles";
                    $resultRole = $conn->query($sqlRole);

                    if ($resultRole->num_rows > 0) {
                      $roles = mysqli_fetch_all($resultRole, MYSQLI_ASSOC);
                      foreach ($roles as $role) {
                  ?>
                        <option value="<?php echo $role['id'] ?>"><?php echo $role['role_name']; ?></option>
                  <?php
                      }
                    }
                  ?>
                </select>

                <label for="Salary">الراتب</label>
                <input type="text" id="inputSalary" name="inputSalary" class="form-control" value="<?php echo isset($_POST['inputSalary']) ? htmlspecialchars($_POST['inputSalary'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($salaryErr)){
                    echo '<div class="invalid-feedback">'.$salaryErr.'</div>';
                  }
                ?>

                <label for="HousingAllowance">بدل سكن</label>
                <input type="text" id="inputHousingAllowance" name="inputHousingAllowance" class="form-control" value="<?php echo isset($_POST['inputHousingAllowance']) ? htmlspecialchars($_POST['inputHousingAllowance'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($housingAllowanceErr)){
                    echo '<div class="invalid-feedback">'.$housingAllowanceErr.'</div>';
                  }
                ?>

                <label for="TransportationAllowance">بدل مواصلات</label>
                <input type="text" id="inputTransportationAllowance" name="inputTransportationAllowance" class="form-control" value="<?php echo isset($_POST['inputTransportationAllowance']) ? htmlspecialchars($_POST['inputTransportationAllowance'], ENT_QUOTES) : ''; ?>" autofocus>
                <?php
                  if(!empty($transportAllowanceErr)){
                    echo '<div class="invalid-feedback">'.$transportAllowanceErr.'</div>';
                  }
                ?>

            </div>

            <button class="btn btn-lg btn-primary btn-block btn-custom" type="submit" style="margin-bottom: 20px;">إنشاء العقد</button>

            <?php
            if(!empty($successMsg)){
              echo '<div class="alert alert-success" style="margin-top: 20px;">'.$successMsg.'</div>';
            ?>
                  <?php
                    $fileName = "download/2.docx";
                    if($type == "Saudi Employment"){
                      $fileName = "read.php?contract_id=".$contractId;
                    }
                  ?>
                <div class="col-sm-4">
                  <a class="btn btn-lg btn-primary btn-block btn-custom" href="<?php echo $fileName; ?>" target="_blank">العرض و التحميل</a>
                </div>
            <?php
            }
            if(!empty($errorMsg)){
              echo '<div class="alert alert-danger" style="margin-top: 20px;">'.$errorMsg.'</div>';
            }
            ?>
          </form>

        </div>
      </div>
    </div>

 <?php
 require_once 'includes/footer.php';
 ?>
