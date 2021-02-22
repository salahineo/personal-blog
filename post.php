<?php
// Start the session
session_start();

// Include Connection
include 'dashboard/include/connection.php';
// Include Header
include 'include/header.php';
// Include Navbar
include 'include/navbar.php';

// Store Get Variable
$postID = $_GET['id'];

// Check If There Is A Get Request Variable
if(!isset($_GET['id'])) {
  // Return To Category Page If It Is Not Exist
  header('Location: index.php');
  exit();
}

?>

<!-- Start Content -->
<div class="content">
  <div class="container">
    <div class="row">
      <!-- Post -->
      <div class="single-post col-lg-9">
        <?php
        // Get Category Name
        $query = 'SELECT * FROM posts WHERE post_id = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$postID]);
        $row = $stmt->fetch();
        ?>
        <div class="post-img">
          <img class="img-fluid" src="images/posts/<?php echo $row['post_picture']; ?>" alt="Post Image">
        </div>
        <div class="post-details border-top-0 bg-white">
          <div class="post-meta text-secondary text-center">
            <i class="far fa-calendar-alt"></i>
            <span class="date"><?php echo explode(' ', $row['post_date'])[0];; ?></span>
            <i class="fas fa-tags"></i>
            <span class="tag"><?php echo $row['post_category']; ?></span>
          </div>
          <div class="post-title">
            <h2><?php echo $row['post_title']; ?></h2>
          </div>
          <div class="post-content text-dark">
            <p><?php echo $row['post_content']; ?></p>
          </div>
        </div>
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

