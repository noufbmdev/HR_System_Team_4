<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

session_start();

if( $_SESSION["loggedin"] == false){
  header("location: login.php");
  exit;
}

require_once 'config/config.php';

require_once 'includes/header.php';

require_once 'includes/nav-bar.php';

$accountId = $_SESSION['user_id'];
$sessRoleId = $_SESSION['role_id'];

$firstNameErr = $lastNameErr = $middleNameErr = $nationalIdErr = $streetErr = $neighborhoodErr = $passwordErr = $cityErr = $nationalityErr = $emailErr = $phoneNumberErr = "";

$successMsg = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
  if($sessRoleId !=5 && $sessRoleId != 1){

    $stmt = $conn->prepare("update employee set `Password` = ?, `Street` = ?, `Neighborhood` = ?, `City` = ?, `Nationality` = ?, `Email` = ?, `Phone Number` = ?, `Gender` = ? where `ID` = ?");

    $stmt->bind_param("ssssssisi", $strPassword, $street, $neighborhood, $city, $nationality, $email, $phoneNumber, $gender, $ftAccountId);

  }
  else {

    $stmt = $conn->prepare("update employee set `First_Name` = ?, `Middle_Name` = ?, `Last_Name` = ?, `National_ID` = ?, `Password` = ?, `Street` = ?, `Neighborhood` = ?, `City` = ?, `Nationality` = ?, `Email` = ?, `Phone Number` = ?, `Gender` = ? where `ID` = ?");

    $stmt->bind_param("ssssssssssisi", $firstName, $middleName, $lastName, $nationalId, $strPassword, $street, $neighborhood, $city, $nationality, $email, $phoneNumber, $gender, $ftAccountId);
    $firstName = $_POST["firstName"];
    $middleName = $_POST["middleName"];
    $lastName = $_POST["lastName"];
    $nationalId = $_POST["nationalId"];

    if(empty($firstName)){
      $firstNameErr = "الرجاء إدخال الاسم الاول";
      $error = true;
    }

    if(empty($middleName)){
      $middleNameErr = "الرجاد إدخال الاسم الاوسط";
      $error = true;
    }

    if(empty($lastName)){
      $lastNameErr = "الرجاء إدخال اسم الاخير";
      $error = true;
    }

    if(empty($nationalId)){
      $nationalityErr = "الرجاء إدخال الجنسية";
      $error = true;
    }
  }

  $strPassword = $_POST["strPassword"];
  $street = $_POST["street"];
  $neighborhood = $_POST["neighborhood"];
  $city = $_POST["city"];
  $nationality = $_POST["nationality"];
  $email = $_POST["email"];
  $phoneNumber = $_POST["phoneNumber"];
  $gender = $_POST["gender"];
  $ftAccountId = $accountId;

  $error = false;

  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $emailErr = "الرجاء إدخال بريد إلكتروني صحيح";
    $error = true;
  }

  if(strlen($strPassword) < 8 || !is_numeric($strPassword) ){
    $passwordErr = "الرجاء إدخال كلمة سر تتكون من ٨ أحرف أو أكثر";
    $error = true;
  }

  if( (strlen($phoneNumber) < 10 || strlen($phoneNumber) > 15 ) || !is_numeric($phoneNumber) ){
    $phoneNumberErr = "الرجاء إدخال رقم الهاتف الصحيح";
    $error = true;
  }

  if(empty($nationality)){
    $nationalityErr = "الرجاء إدخال الجنسية";
    $error = true;
  }

  if(empty($street)){
    $streetErr = "الرجاء إدحال الشارع";
    $error = true;
  }

  if(empty($neighborhood)){
    $neighborhoodErr = "الرجاء إدخال الحي";
    $error = true;
  }

  if(empty($city)){
    $cityErr = "الرجاء إدخال المدينة";
    $error = true;
  }

  if($error == false){

    if ($stmt->execute()) {
      $successMsg = "تم تحديث المعلومات بنجاح";

    } else {
      $errorMsg = "لم يتم تحديث المعلومات";
    }

  }
  $stmt->close();
}

$sql = "select *, contract.Job_Position from employee inner join contract on contract.Contract_ID = employee.Contract_ID where ID = ".$accountId;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $user = mysqli_fetch_array($result,MYSQLI_ASSOC);
} else {
  //header("location: dashboard.php");
}

//$conn->close();

?>

    <div class="container">
      <div class="row">
        <?php
          require_once 'includes/side-bar.php';
        ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">الحساب الخاص</h1>

          <form class="form-custom" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="col-sm-6">

                <label for="">رمز الوظيفي</label>
                <input type="text" class="form-control" value="<?php echo $user['Employee_ID'] ?>" disabled autofocus>

                <label for="">اسم الاول</label>
                <input type="text" id="firstName" name="firstName" class="form-control" <?php if($sessRoleId !=5 && $sessRoleId != 1){ echo 'disabled'; }?> value="<?php echo $user['First_Name'] ?>" autofocus>

                <?php
                  if(!empty($firstNameErr)){
                    echo '<div class="invalid-feedback">'.$firstNameErr.'</div>';
                  }
                ?>

                <label for="">اسم الاوسط</label>
                <input type="text" id="middleName" name="middleName" class="form-control" <?php if($sessRoleId !=5 && $sessRoleId != 1){ echo 'disabled'; }?> value="<?php echo $user['Middle_Name'] ?>" autofocus>

                <?php
                  if(!empty($middleNameErr)){
                    echo '<div class="invalid-feedback">'.$middleNameErr.'</div>';
                  }
                ?>

                <label for="">اسم الاخير</label>
                <input type="text" id="lastName" name="lastName" class="form-control" <?php if($sessRoleId !=5 && $sessRoleId != 1){ echo 'disabled'; }?> value="<?php echo $user['Last_Name'] ?>" autofocus>

                <?php
                  if(!empty($lastNameErr)){
                    echo '<div class="invalid-feedback">'.$lastNameErr.'</div>';
                  }
                ?>

                <label for="">رقم الهوية/الإقامة</label>
                <input type="text" class="form-control" name="nationalId" id="nationalId" <?php if($sessRoleId !=5 && $sessRoleId != 1){ echo 'disabled'; }?> value="<?php echo $user['National_ID'] ?>" autofocus>

                <?php
                  if(!empty($nationalIdErr)){
                    echo '<div class="invalid-feedback">'.$nationalIdErr.'</div>';
                  }
                ?>

                <label for="">الجنسية</label>
                <input type="text" id="nationality" name="nationality" class="form-control" value="<?php echo $user['Nationality'] ?>" autofocus>
                <?php
                  if(!empty($nationalityErr)){
                    echo '<div class="invalid-feedback">'.$nationalityErr.'</div>';
                  }
                ?>

                <label for="">البريد الالكتروني</label>
                <input type="text" id="email" name="email" class="form-control"  value="<?php echo $user['Email'] ?>" autofocus>
                <?php
                  if(!empty($emailErr)){
                    echo '<div class="invalid-feedback">'.$emailErr.'</div>';
                  }
                ?>

                <label for="">رقم الهاتف</label>
                <input type="text" id="phoneNumber" name="phoneNumber" class="form-control"  value="<?php echo $user['Phone Number'] ?>" autofocus>
                <?php
                  if(!empty($phoneNumberErr)){
                    echo '<div class="invalid-feedback">'.$phoneNumberErr.'</div>';
                  }
                ?>

                <label for="">كلمة المرور</label>
                <input type="password" id="strPassword" name="strPassword" class="form-control" value="<?php echo $user['Password'] ?>" autofocus>
                <?php
                  if(!empty($passwordErr)){
                    echo '<div class="invalid-feedback">'.$passwordErr.'</div>';
                  }
                ?>
            </div>

            <div class="col-sm-6">

                <label for="">الجنس</label>
                <select class="form-control" name="gender">
                  <option value="Male">ذكر</option>
                  <option value="Female">انثى</option>
                </select>

                <label for="">الشارع</label>
                <input type="text" id="street" name="street" class="form-control" value="<?php echo $user['Street'] ?>" autofocus>
                <?php
                  if(!empty($streetErr)){
                    echo '<div class="invalid-feedback">'.$streetErr.'</div>';
                  }
                ?>

                <label for="">الحي</label>
                <input type="text" id="neighborhood" name="neighborhood" class="form-control" value="<?php echo $user['Neighborhood'] ?>" autofocus>
                <?php
                  if(!empty($neighborhoodErr)){
                    echo '<div class="invalid-feedback">'.$neighborhoodErr.'</div>';
                  }
                ?>

                <label for="">المدينة</label>
                <input type="text" id="city" name="city" class="form-control" value="<?php echo $user['City'] ?>" autofocus>
                <?php
                  if(!empty($cityErr)){
                    echo '<div class="invalid-feedback">'.$cityErr.'</div>';
                  }
                ?>

                <label for="JobTitle">مسمى الوظيفي</label>
                <input type="text" id="inputJobTitle" disabled name="inputJobTitle" class="form-control" value="<?php echo $user['Job_Position'] ?>" autofocus>

                <label for="ContractType">القسم</label>
                <select class="form-control" name="departmenId" disabled>
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
                <select class="form-control" name="managerID" disabled>
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
                <select class="form-control" name="role" disabled>
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

            </div>

            <div class="col-sm-12">
                <button class="btn btn-lg btn-primary btn-block" type="submit">تحديث معلومات الحساب</button>
                <?php
                if(!empty($successMsg)){
                  echo '<div class="alert alert-success" style="margin-top: 20px;">'.$successMsg.'</div>';
                }
                ?>
            </div>

          </form>

        </div>
      </div>
    </div>

 <?php
 require_once 'includes/footer.php';
 ?>
