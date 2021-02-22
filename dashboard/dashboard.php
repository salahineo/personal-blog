<?php
// Start the session
session_start();

//Include Connection
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
  header("Location: dashboard.php");
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
      <div class="col-lg-9 menu-content main">
        <!-- Stats -->
        <div class="stats">
          <h3>الاحصائيات</h3>
          <div class="row">
            <div class="posts col-lg-4">
              <?php
              // Fetch Number Of Posts
              $stmt = $db->query('SELECT post_id FROM posts');
              $postsCount = $stmt->rowCount();
              ?>
              <div class="content">
                <h2 class="value"><?php echo $postsCount; ?></h2>
                <span class="name">المقالات</span>
                <i class="fas fa-th-large"></i>
              </div>
            </div>
            <div class="categories col-lg-4">
              <?php
              // Fetch Number Of Categories
              $stmt = $db->query('SELECT category_id FROM categories');
              $categoriesCount = $stmt->rowCount();
              ?>
              <div class="content">
                <h2 class="value"><?php echo $categoriesCount; ?></h2>
                <span class="name">التصنيفات</span>
                <i class="fas fa-tag"></i>
              </div>
            </div>
            <div class="messages col-lg-4">
              <?php
              // Fetch Number Of Messages
              $stmt = $db->query('SELECT message_id FROM messages');
              $messagesCount = $stmt->rowCount();
              ?>
              <div class="content">
                <h2 class="value"><?php echo $messagesCount; ?></h2>
                <span class="name">الرسائل</span>
                <i class="fas fa-comment-alt"></i>
              </div>
            </div>
          </div>
        </div>
        <!-- Latest Posts -->
        <div class="latest-posts">
          <h3>احدث المقالات</h3>
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
                // Posts Virtual ID Counter
                $categoryVirtualID = 0;
                // Get Total Pages
                $stmt = $db->query('SELECT * FROM posts');
                // Fetch Categories
                $stmt = $db->query('SELECT * FROM posts ORDER BY post_id DESC LIMIT 0, 4');
                while($row = $stmt->fetch()) {
                  ?>
                  <tr>
                    <td><?php echo $categoryVirtualID += 1; ?></td>
                    <td><?php echo $row['post_title']; ?></td>
                    <td><img class="img-fluid" src="../images/posts/<?php echo $row['post_picture']; ?>"
                             alt="Post Image">
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
        </div>
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
