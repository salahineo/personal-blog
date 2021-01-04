<?php
// Start the session
session_start();

// Include Connection
include 'dashboard/include/connection.php';
// Include Header
include 'include/header.php';
// Include Navbar
include 'include/navbar.php';

// Check If There Is A POST Request
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get Post Variable
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  // Check For Input Data
  if(empty($name) || empty($email) || empty($message)) {
    $message = '<div class="alert alert-danger">من فضلك ادخل جميع البينات</div>';
  } elseif(strlen($name) > 100) {
    // Check For Input Length
    $message = '<div class="alert alert-danger">الاسم الذي ادخلته كبير جدا</div>';
  } elseif(strlen($email) > 100) {
    // Check For Input Length
    $message = '<div class="alert alert-danger">البريد الالكتروني الذي ادخلته كبير جدا</div>';
  } else {
    // Insert Data If There Is No Error
    $query = 'INSERT INTO messages(sender_name, sender_email, message_content) VALUES (:name, :email, :message)';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);
    $stmt->execute();

    // Success Message
    $message = '<div class="alert alert-success">تم ارسال الرسالة بنجاح</div>';
  }
}

?>

<!-- Start Content -->
<div class="content">
  <div class="container">
    <div class="row">
      <!-- Posts Column -->
      <div class="contact col-lg-9">
        <h3 class="mb-5">تواصل معنا</h3>
        <?php
        // Print Error Message If There
        if(isset($message)) {
          echo $message;
        }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <div class="row">
            <div class="form-group col-md-6">
              <label for="name">الاسم</label>
              <input class="form-control" type="text" id="name" name="name" placeholder="اكتب اسمك">
            </div>
            <div class="form-group col-md-6">
              <label for="email">البريد لالكتروني</label>
              <input class="form-control" type="email" id="email" name="email" placeholder="اكتب بريدك الالكتروني">
            </div>
            <div class="form-group col-12">
              <label for="message">الرسالة</label>
              <textarea class="form-control" name="message" id="message" cols="30" rows="10"
                        placeholder="اكتب رسالتك"></textarea>
            </div>
            <input class="btn btn-primary" type="submit" name="submit" id="submit">
          </div>
        </form>
      </div>
      <!-- Sidebar Column -->
      <div class="d-none d-lg-block col-lg-3">
        <?php
        // Include Sidebar
        include 'include/sidebar.php';
        ?>
      </div>
    </div>
  </div>
</div>
<!-- End Content -->

<?php
// Include Footer
include 'include/footer.php';
?>
