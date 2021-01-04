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
  // Get Post Variable
  $postTitle = $_POST['post_title'];
  $postCategory = $_POST['post_category'];
  $postContent = $_POST['post_text'];
  $postImage = $_FILES['post_image'];

  // Get Post Image Attributes
  $image_name = $postImage['name'];
  $image_type = $postImage['type'];
  $image_temp = $postImage['tmp_name'];
  $image_error = $postImage['error'];
  $image_extension_array = explode('.', $image_name);
  $image_extension = strtolower(end($image_extension_array));
  $randomImageName = rand(0, 100000000) . '.' . $image_extension;

  // Image Extensions Array
  $allowed_extensions = array('jpg', 'jpeg', 'png');

  // Check For Input Data
  if(empty($postTitle) || empty($postCategory) || empty($postContent)) {
    $message = '<div class="alert alert-danger">من فضلك ادخل جميع البيانات</div>';
  } elseif(strlen($postTitle) > 100) {
    // Check For Input Length
    $message = '<div class="alert alert-danger">اسم المقال كبير جدا</div>';
  } elseif(strlen($postContent) > 5000) {
    // Check For Input Length
    $message = '<div class="alert alert-danger">محتوي المقال كبير جدا</div>';
  } elseif($image_error == 4) {
    // Check If Image Not Upload
    $message = '<div class="alert alert-danger">برجاء رفع صورة المقال</div>';
  } elseif(!in_array($image_extension, $allowed_extensions)) {
    // Check If Image Extension Not Supported
    $message = '<div class="alert alert-danger">امتداد الصورة غير مناسب</div>';
  } else {
    // Move Uploaded Picture
    move_uploaded_file($image_temp, realpath(dirname(getcwd())) . '/images/posts/' . $randomImageName);

    // Insert Data If There Is No Error
    $query = 'INSERT INTO posts(post_title, post_category, post_content, post_picture) VALUES (:postTitle, :postCategory, :postContent, :postPicture)';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':postTitle', $postTitle);
    $stmt->bindParam(':postCategory', $postCategory);
    $stmt->bindParam(':postContent', $postContent);
    $stmt->bindParam(':postPicture', $randomImageName);
    $stmt->execute();

    // Success Message
    $message = '<div class="alert alert-success">تم نشر المقال بنجاح</div>';
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
      <div class="col-lg-9 menu-content new-post">
        <?php
        // Print Message If Exist
        if(isset($message)) {
          echo $message;
        }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="post_title">عنوان المقال</label>
            <input class="form-control" type="text" id="post_title" name="post_title"
                   value="<?php if(isset($postTitle)) echo $postTitle; ?>">
          </div>
          <div class="form-group">
            <label for="post_image">صورة المقال</label>
            <input class="form-control" type="file" id="post_image" name="post_image">
          </div>
          <div class="form-group">
            <label for="post_category">التصنيف</label>
            <select class="form-control" name="post_category" id="post_category">
              <?php
              // Get Categories
              $stmt = $db->query('SELECT category_name FROM categories ORDER BY  category_name DESC');
              while($row = $stmt->fetch()) {
                ?>
                <option value="<?php echo $row['category_name']; ?>"><?php echo $row['category_name']; ?></option>
                <?php
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="post_text">نص المقال</label>
            <textarea class="form-control" id="post_text" name="post_text"
                      rows="12"><?php if(isset($postContent)) echo $postContent; ?></textarea>
          </div>
          <input type="submit" class="btn btn-primary" value="انشر المقال">
        </form>
      </div>
      <!-- End Menu Content -->
    </div>
  </div>

</div>
<!-- End Content -->

<?php
// Include Footer
include 'include/footer.php';
?>
