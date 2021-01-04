<!-- Start Navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">تدويناتي</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse border-secondary" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">الرئيسية</a>
        </li>
        <?php
        // Fetch Categories
        $stmt = $db->query('SELECT category_name FROM categories ORDER BY category_id LIMIT 0, 3');
        while($row = $stmt->fetch()) {
          ?>
          <li class="nav-item">
            <a class="nav-link" href="categories.php?category=<?php echo $row['category_name']; ?>">
              <?php echo $row['category_name']; ?>
            </a>
          </li>
          <?php
        }
        ?>
        <li class="nav-item">
          <a class="nav-link" href="contact.php">تواصل معنا</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->
