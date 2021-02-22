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

// Check If There Is A Get Request
if(isset($_GET['id'])) {
  // Delete Data
  $query = 'DELETE FROM categories WHERE category_id = :categoryID';
  $stmt = $db->prepare($query);
  $stmt->bindParam(':categoryID', $_GET['id']);
  $stmt->execute();
  // Return To Categories Page Without Parameters
  header("Location: categories.php");
  exit();
}

// Check If There Is A POST Request
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get Post Variable
  $categoryName = $_POST['category'];
  // Check For Input Data
  if(empty($categoryName)) {
    $message = '<div class="alert alert-danger">من فضلك ادخل اسم التصنيف</div>';
  } elseif(strlen($categoryName) > 100) {
    // Check For Input Length
    $message = '<div class="alert alert-danger">اسم التصنيف كبير جدا</div>';
  } else {
    // Insert Data If There Is No Error
    $query = 'INSERT INTO categories(category_name) VALUES (:categoryName)';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':categoryName', $categoryName);
    $stmt->execute();

    // Success Message
    $message = '<div class="alert alert-success">تمت اضافة التصنيف بنجاح</div>';
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
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <label for="category">تصنيف جديد</label>
          <input type="text" class="form-control" id="category" name="category">
          <input type="submit" class="d-block btn btn-primary mt-3" id="addCategory" name="addCategory"
                 value="اضافة التصنيف">
        </form>
        <h3>جميع التصنيفات</h3>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-dark">
              <tr>
                <th>#</th>
                <th>التصنيف</th>
                <th>تاريخ الاضافة</th>
                <th>عدد المقالات</th>
                <th>الاجراء</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Set Current Page Variable
              isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
              // Category Virtual ID Counter
              $categoryVirtualID = 0;
              // Start & Limit Of Retrieved Date
              $limit = 4;
              $start = ($page - 1) * $limit;
              // Get Total Pages
              $stmt = $db->query('SELECT * FROM categories');
              $totalPages = ceil($stmt->rowCount() / $limit);
              // Fetch Categories
              $stmt = $db->query('SELECT * FROM categories ORDER BY category_id DESC LIMIT ' . $start . ', ' . $limit);
              while($row = $stmt->fetch()) {
                // Get Number Of Posts Related To Current Category
                $query = 'SELECT * FROM posts WHERE post_category = ?';
                $stmt2 = $db->prepare($query);
                $stmt2->execute([$row['category_name']]);
                $postsCount = $stmt2->rowCount();
                ?>
                <tr>
                  <td><?php echo $categoryVirtualID += 1; ?></td>
                  <td><?php echo $row['category_name']; ?></td>
                  <td><?php echo explode(' ', $row['category_date'])[0];; ?></td>
                  <td><?php echo $postsCount; ?></td>
                  <td>
                    <a href="edit-category.php?id=<?php echo $row['category_id']; ?>" class="text-dark">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="?id=<?php echo $row['category_id']; ?>" class="text-dark delete-btn">
                      <i class="mr-3 fas fa-trash-alt"></i>
                    </a>
                  </td>
                </tr>
                <?php
              }
              ?>
            </tbody>
          </table>
        </div>
        <!-- Pagination -->
        <ul class="pagination d-flex justify-content-center mt-5">
          <li class="page-item <?php if($page - 1 == 0) echo 'disabled'; ?>">
            <a class="page-link" href="?page=<?php echo $page - 1; ?>">السابق</a>
          </li>
          <?php
          // Loop To Get All Pages Links
          for($i = 1; $i <= $totalPages; $i++) {
            ?>
            <li class="page-item <?php if($page == $i) echo 'active'; ?>">
              <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php
          }
          ?>
          <li class="page-item <?php if($page + 1 > $totalPages) echo 'disabled'; ?>">
            <a class="page-link" href="?page=<?php echo $page + 1; ?>">التالي</a>
          </li>
        </ul>
      </div>
      <!-- End Menu Content -->
    </div>
  </div>

</div>
<!-- End Content -->

<?php
// Include Delete Script
include 'include/delete_script.php';
// Include Footer
include 'include/footer.php';
?>
