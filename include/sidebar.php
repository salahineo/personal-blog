<!-- Search -->
<div class="sidebar-item search">
  <ul class="list-group">
    <li class="list-group-item active">
      <i class="fas fa-search"></i>
      <span class="category-name">ابحث عن مقاله</span>
    </li>
    <li class="list-group-item">
      <form action="search.php" method="get">
        <div class="form-group">
          <input class="form-control" type="text" id="search" name="search" placeholder="ابحث في المقالات">
          <button type="submit" id="submit" class="btn btn-primary d-block btn-block">ابحث</button>
        </div>
      </form>
    </li>
  </ul>
</div>
<!-- Latest Posts -->
<div class="sidebar-item latest-posts">
  <ul class="list-group">
    <li class="list-group-item active">
      <i class="fas fa-pencil-alt"></i>
      <span class="category-name">احدث التدوينات</span>
    </li>
    <?php
    // Start & Limit Of Retrieved Date
    $limit = 3;
    $start = 0;
    // Fetch Latest Posts
    $stmt = $db->query('SELECT post_id,post_picture, post_title FROM posts ORDER BY post_id DESC LIMIT ' . $start . ', ' . $limit);
    while($row = $stmt->fetch()) {
      ?>
      <li class="list-group-item">
        <a class="text-center" href="post.php?id=<?php echo $row['post_id']; ?>">
          <img class="d-block mb-2 img-fluid" src="images/posts/<?php echo $row['post_picture']; ?>" alt="Post Image">
          <span><?php echo $row['post_title']; ?></span>
        </a>
      </li>
      <?php
    }
    ?>
  </ul>
</div>
<!-- Categories -->
<div class="sidebar-item categories">
  <ul class="list-group">
    <li class="list-group-item active">
      <i class="fas fa-tags"></i>
      <span class="category-name">التصنيفات</span>
    </li>
    <?php
    // Fetch Categories
    $stmt = $db->query('SELECT category_name FROM categories ORDER BY category_id');
    while($row = $stmt->fetch()) {
      ?>
      <li class="list-group-item">
        <a href="categories.php?category=<?php echo $row['category_name']; ?>">
          <i class="fas fa-tag"></i>
          <span><?php echo $row['category_name']; ?></span>
        </a>
      </li>
      <?php
    }
    ?>
  </ul>
</div>
