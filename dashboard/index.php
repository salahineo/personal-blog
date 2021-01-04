<?php
// Start the session
session_start();

// Include Header
include 'include/connection.php';
// Include Header
include 'include/header.php';

// Check Session
if(isset($_SESSION['admin'])) {
  header('Location: dashboard.php');
  exit();
}

// Get Request Method Variables
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get POST Values
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Check For Input Data
  if(empty($username) || empty($password)) {
    $message = '<div class="alert alert-danger">من فضلك ادخل البيانات</div>';
  } else {
    // Get Data From Database
    $query = 'SELECT * FROM admin WHERE admin_username = :username AND admin_password = :password';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    // Get Rows Count
    $count = $stmt->rowCount();
    // If Admin Info Found
    if($count > 0) {
      $_SESSION['admin'] = $username;
      header('Location:dashboard.php');
      exit();
    } else {
      $message = '<div class="alert alert-danger">البيانات غير متطابقة</div>';
    }
  }

}
?>

<!-- Start Content -->
<div class="content">
  <div class="login-form mt-5">
    <h3 class="text-center mb-5">تسجيل الدخول</h3>
    <?php
    // Print Message If Exist
    if(isset($message)) echo $message;
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <div class="form-group">
        <label for="username">اسم المستخدم</label>
        <input class="form-control" type="text" id="username" name="username">
      </div>
      <div class="form-group password">
        <label for="password">كلمة السر</label>
        <input class="form-control" type="password" id="password" name="password">
        <i class="fas fa-eye"></i>
      </div>
      <input class="btn btn-primary" type="submit" value="دخول">
    </form>
  </div>
</div>
<!-- End Content -->

<?php
// Include Show Password Script
include 'include/password_script.php';
// Include Footer
include 'include/footer.php';
?>

