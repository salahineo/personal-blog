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
  // Get Post Current Image
  $query = 'SELECT post_picture FROM posts WHERE post_id = :postID';
  $stmt = $db->prepare($query);
  $stmt->bindParam(':postID', $_GET['id']);
  $stmt->execute();
  $row = $stmt->fetch();
  // Remove Post Image
  unlink(realpath(dirname(getcwd())) . "/images/posts/" . $row['post_picture']);

  // Delete Data
  $query = 'DELETE FROM posts WHERE post_id = :postID';
  $stmt = $db->prepare($query);
  $stmt->bindParam(':postID', $_GET['id']);
  $stmt->execute();

  // Return To Categories Page Without Parameters
  header("Location: posts.php");
  exit();
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
      <div class="col-lg-9 menu-content all-posts">
        <h3>جميع المقالات</h3>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-dark">
              <tr>
                <th>#</th>
                <th>عنوان المقال</th>
                <th>صورة المقال</th>
                <th>التصنيف</th>
                <th>تاريخ الاضافة</th>
                <th>الاجراء</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Set Current Page Variable
              isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
              // Posts Virtual ID Counter
              $categoryVirtualID = 0;
              // Start & Limit Of Retrieved Date
              $limit = 4;
              $start = ($page - 1) * $limit;
              // Get Total Pages
              $stmt = $db->query('SELECT * FROM posts');
              $totalPages = ceil($stmt->rowCount() / $limit);
              // Fetch Categories
              $stmt = $db->query('SELECT * FROM posts ORDER BY post_id DESC LIMIT ' . $start . ', ' . $limit);
              while($row = $stmt->fetch()) {
                ?>
                <tr>
                  <td><?php echo $categoryVirtualID += 1; ?></td>
                  <td><?php echo $row['post_title']; ?></td>
                  <td><img class="img-fluid" src="../images/posts/<?php echo $row['post_picture']; ?>" alt="Post Image">
                  </td>
                  <td><?php echo $row['post_category']; ?></td>
                  <td><?php echo explode(' ', $row['post_date'])[0];; ?></td>
                  <td>
                    <a href="edit-post.php?id=<?php echo $row['post_id']; ?>" class="text-dark">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="?id=<?php echo $row['post_id']; ?>" class="text-dark delete-btn">
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
