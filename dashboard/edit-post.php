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

// Store Get Variable
$postID = $_GET['id'];

// Check If There Is A Get Request Variable
if(!isset($_GET['id'])) {
  // Return To Category Page If It Is Not Exist
  header('Location: posts.php');
  exit();
}

// Check If There Is A POST Request
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get Post Current Image
  $query = 'SELECT post_picture FROM posts WHERE post_id = :postID';
  $stmt = $db->prepare($query);
  $stmt->bindParam(':postID', $postID);
  $stmt->execute();
  $row = $stmt->fetch();

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

  // Image Extensions Array
  $allowed_extensions = array('jpg', 'jpeg', 'png');

  // If Image Not Uploaded
  if($image_error == 4) {
    // Use The Current Image
    $randomImageName = $row['post_picture'];
  } else {
    // Else: Use The New Image
    $image_extension_array = explode('.', $image_name);
    $image_extension = strtolower(end($image_extension_array));
    $randomImageName = rand(0, 100000000) . '.' . $image_extension;
    // Remove Old Image In Case Of New Image Uploaded Successfully
    unlink(realpath(dirname(getcwd())) . "/images/posts/" . $row['post_picture']);
  }

  // Check For Input Data
  if(empty($postTitle) || empty($postCategory) || empty($postContent)) {
    $message = '<div class="alert alert-danger">من فضلك ادخل جميع البيانات</div>';
  } elseif(strlen($postTitle) > 100) {
    // Check For Input Length
    $message = '<div class="alert alert-danger">اسم المقال كبير جدا</div>';
  } elseif(strlen($postContent) > 5000) {
    // Check For Input Length
    $message = '<div class="alert alert-danger">محتوي المقال كبير جدا</div>';
  } elseif($image_error == 0 && !in_array($image_extension, $allowed_extensions)) {
    // Check If Image Upload & If Extension Suitable
    $message = '<div class="alert alert-danger">امتداد الصورة غير مناسب</div>';
  } else {
    // Move Uploaded Picture
    move_uploaded_file($image_temp, realpath(dirname(getcwd())) . '/images/posts/' . $randomImageName);

    // Insert Data If There Is No Error
    $query = 'UPDATE posts SET post_title = :postTitle, post_category = :postCategory, post_content = :postContent, post_picture = :postPicture WHERE post_id = :postID';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':postID', $postID);
    $stmt->bindParam(':postTitle', $postTitle);
    $stmt->bindParam(':postCategory', $postCategory);
    $stmt->bindParam(':postContent', $postContent);
    $stmt->bindParam(':postPicture', $randomImageName);
    $stmt->execute();

    // Return To Posts Page
    header('Location: posts.php');
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
      <div class="col-lg-9 menu-content edit-post">
        <?php
        // Print Error Message If There
        if(isset($message)) {
          echo $message;
        }

        // Get Category Name
        $query = 'SELECT * FROM posts WHERE post_id = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$postID]);
        $row = $stmt->fetch();
        ?>
        <form action="?id=<?php echo $row['post_id']; ?>" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="post_title">عنوان المقال</label>
            <input class="form-control" type="text" id="post_title" name="post_title"
                   value="<?php echo $row['post_title']; ?>">
          </div>
          <div class="form-group">
            <div class="current-image">
              <span>الصورة الحالية</span>
              <img class="img-fluid" src="../images/posts/<?php echo $row['post_picture']; ?>" alt="Post Image">
            </div>
            <label for="post_image">صورة المقال</label>
            <input class="form-control" type="file" id="post_image" name="post_image">
          </div>
          <div class="form-group">
            <label for="post_category">التصنيف</label>
            <select class="form-control" name="post_category" id="post_category">
              <?php
              // Get Categories
              $stmt = $db->query('SELECT category_name FROM categories ORDER BY  category_name DESC');
              while($row2 = $stmt->fetch()) {
                ?>
                <option
                  value="<?php echo $row2['category_name']; ?>" <?php if($row2['category_name'] === $row['post_category']) echo 'selected'; ?>><?php echo $row2['category_name']; ?></option>
                <?php
              }
              ?>
            </select>
          </div>
          <div class=" form-group">
            <label for="post_text">نص المقال</label>
            <textarea class="form-control" id="post_text" name="post_text"
                      rows="10"><?php echo $row['post_content']; ?></textarea>
          </div>
          <input type="submit" class="btn btn-primary" value="تعديل المقال">
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
