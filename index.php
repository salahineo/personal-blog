<?php
// Start the session
session_start();

// Include Connection
include 'dashboard/include/connection.php';
// Include Header
include 'include/header.php';
// Include Navbar
include 'include/navbar.php';

?>

<!-- Start Content -->
<div class="content">
  <div class="container">
    <div class="row">
      <!-- Posts Column -->
      <div class="col-lg-9">
        <!-- Row 1 -->
        <div class="row">
          <?php
          // Set Current Page Variable
          isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
          // Start & Limit Of Retrieved Date
          $limit = 6;
          $start = ($page - 1) * $limit;
          // Get Total Pages
          $stmt = $db->query('SELECT * FROM posts');
          $totalPages = ceil($stmt->rowCount() / $limit);
          // Fetch Categories
          $stmt = $db->query('SELECT * FROM posts ORDER BY post_id DESC LIMIT ' . $start . ', ' . $limit);
          while($row = $stmt->fetch()) {
            ?>
            <!-- Post -->
            <div class="post col-md-6">
              <div class="post-img">
                <a href="post.php?id=<?php echo $row['post_id']; ?>">
                  <img class="img-fluid" src="images/posts/<?php echo $row['post_picture']; ?>" alt="Post Image">
                </a>
              </div>
              <div class="post-details border-top-0 bg-white">
                <div class="post-meta text-secondary text-center">
                  <i class="far fa-calendar-alt"></i>
                  <span class="date"><?php echo explode(' ', $row['post_date'])[0];; ?></span>
                  <i class="fas fa-tags"></i>
                  <span class="tag"><?php echo $row['post_category']; ?></span>
                </div>
                <div class="post-title">
                  <h4>
                    <a href="post.php?id=<?php echo $row['post_id']; ?>"><?php echo $row['post_title']; ?></a>
                  </h4>
                </div>
                <div class="post-content text-secondary">
                  <p>
                    <?php echo substr($row['post_content'], 0, 269) . ' ...'; ?>
                  </p>
                  <a class="btn btn-primary btn-sm" href="post.php?id=<?php echo $row['post_id']; ?>">قراءة المزيد</a>
                </div>
              </div>
            </div>
            <?php
          }
          ?>
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
