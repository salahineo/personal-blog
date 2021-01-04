<?php
// Start the session
session_start();

// Include Connection
include 'include/connection.php';
// Include Header
include 'include/header.php';

// Check Session
if(!isset($_SESSION['admin'])) {
  header('Location: index.php');
  exit();
}

// Check If There Is A POST Request
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get Post Current Image
  $query = 'SELECT admin_picture FROM admin';
  $stmt = $db->prepare($query);
  $stmt->execute();
  $row = $stmt->fetch();

  // Get Post Variable
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $image = $_FILES['image'];

  // Get Post Image Attributes
  $image_name = $image['name'];
  $image_type = $image['type'];
  $image_temp = $image['tmp_name'];
  $image_error = $image['error'];

  // Image Extensions Array
  $allowed_extensions = array('jpg', 'jpeg', 'png');

  // If Image Not Uploaded
  if($image_error == 4) {
    // Use The Current Image
    $randomImageName = $row['admin_picture'];
  } else {
    // Else: Use The New Image
    $image_extension_array = explode('.', $image_name);
    $image_extension = strtolower(end($image_extension_array));
    $randomImageName = rand(0, 100000000) . '.' . $image_extension;
  }

  // Check For Input Data
  if(empty($username) || empty($email) || empty($password)) {
    $message = '<div class="alert alert-danger">من فضلك ادخل جميع البيانات</div>';
  } elseif(strlen($username) > 100) {
    // Check For Input Length
    $message = '<div class="alert alert-danger">اسم المستخدم كبير جدا</div>';
  } elseif(strlen($email) > 100) {
    // Check For Input Length
    $message = '<div class="alert alert-danger">البريد الالكتروني كبير جدا</div>';
  } elseif($image_error == 0 && !in_array($image_extension, $allowed_extensions)) {
    // Check If Image Upload & If Extension Suitable
    $message = '<div class="alert alert-danger">امتداد الصورة غير مناسب</div>';
  } else {
    // Move Uploaded Picture
    move_uploaded_file($image_temp, realpath(dirname(getcwd())) . '/images/admin/' . $randomImageName);

    // Insert Data If There Is No Error
    $query = 'UPDATE admin SET admin_username = :username, admin_email = :email, admin_password = :password, admin_picture = :image WHERE admin_id = 0';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':image', $randomImageName);
    $stmt->execute();

    // Remove Old Image In Case Of New Image Uploaded Successfully
    unlink(realpath(dirname(getcwd())) . "/images/admin/" . $row['admin_picture']);

    // Return To Posts Page
    header('Location: profile.php');
    exit();
  }

}

?>

<!-- Start Content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <?php
      // Include Sidebar
      include 'include/sidebar.php';
      ?>
      <!-- Start Menu Content -->
      <div class="col-lg-9 menu-content profile">
        <?php
        // Print Message If Exist
        if(isset($message)) {
          echo $message;
        }
        // Get Admin Info
        $query = 'SELECT * FROM admin';
        $stmt = $db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="username">اسم المستخدم</label>
            <input class="form-control" type="text" id="username" name="username"
                   value="<?php echo $row['admin_username']; ?>">
          </div>
          <div class="form-group">
            <label for="email">البريد الالكتروني</label>
            <input class="form-control" type="email" id="email" name="email" value="<?php echo $row['admin_email']; ?>">
          </div>
          <div class="form-group">
            <div class="current-image">
              <span>الصورة الحالية</span>
              <img class="img-fluid" src="../images/admin/<?php echo $row['admin_picture']; ?>" alt="Admin Image">
            </div>
            <label for="image">صورة المستخدم</label>
            <input class="form-control" type="file" id="image" name="image">
          </div>
          <div class="form-group password">
            <label for="password">كلمة السر</label>
            <input class="form-control" type="password" id="password" name="password"
                   value="<?php echo $row['admin_password']; ?>">
            <i class="fas fa-eye"></i>
          </div>
          <input type="submit" class="btn btn-primary" value="تعديل البيانات">
        </form>
      </div>
      <!-- End Menu Content -->
    </div>
  </div>
</div>
<!-- End Content -->

<?php
// Include Show Password Script
include 'include/password_script.php';
// Include Footer
include 'include/footer.php';
?>
