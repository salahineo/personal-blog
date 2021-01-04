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
$categoryID = $_GET['id'];

// Check If There Is A Get Request Variable
if(!isset($_GET['id'])) {
  // Return To Category Page If It Is Not Exist
  header('Location: categories.php');
  exit();
}

// Check If There Is A POST Request
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get Post Variable
  $categoryName = $_POST['category'];
  // Check For Input Data
  if(empty($categoryName)) {
    $message = '<div class="alert alert-danger"><strong>خطأ!</strong> من فضلك ادخل اسم التصنيف</div>';
  } elseif(strlen($categoryName) > 100) {
    // Check For Input Length
    $message = '<div class="alert alert-danger"><strong>خطأ!</strong> اسم التصنيف كبير جدا</div>';
  } else {
    // Insert Data If There Is No Error
    $query = 'UPDATE categories SET category_name = :categoryName WHERE category_id = :categoryID';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':categoryName', $categoryName);
    $stmt->bindParam(':categoryID', $categoryID);
    $stmt->execute();
    // Return To Category Page
    header('Location: categories.php');
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
      <div class="col-lg-9 menu-content categories">
        <?php
        // Print Error Message If There
        if(isset($message)) {
          echo $message;
        }
        // Get Category Name
        $query = 'SELECT category_id, category_name FROM categories WHERE category_id = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$categoryID]);
        $row = $stmt->fetch();
        ?>
        <form action="?id=<?php echo $row['category_id']; ?>" method="post">
          <label for="category">تعديل التصنيف</label>
          <input type="text" class="form-control" id="category" name="category"
                 value="<?php echo $row['category_name']; ?>">
          <input type="submit" class="d-block btn btn-primary mt-3" id="editCategory" name="editCategory"
                 value="تعديل التصنيف">
        </form>
        <!-- End Menu Content -->
      </div>
    </div>
  </div>
</div>
<!-- End Content -->

<?php
// Include Footer
include 'include/footer.php';
?>

