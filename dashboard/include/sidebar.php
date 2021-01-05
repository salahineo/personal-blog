<?php
// Get Admin Image
$query = 'SELECT admin_picture FROM admin';
$stmt = $db->prepare($query);
$stmt->execute();
$row = $stmt->fetch();
?>
<!-- Start Menu Links -->
<div class="col-lg-3 menu-links">
  <div class="admin">
    <img class="img-fluid rounded-circle" src="../images/admin/<?php echo $row['admin_picture']; ?>" alt="Admin Photo">
  </div>
  <div class="responsive-collapse d-flex align-items-center justify-content-between justify-content-lg-center">
    <h4 class="text-center font-weight-bold">
      <a href="dashboard.php">لوحة التحكم</a>
    </h4>
    <i class="fas fa-bars d-block d-lg-none text-light" data-toggle="collapse" data-target="#adminNavLinks"></i>
  </div>
  <ul class="mt-4" id="adminNavLinks">
    <li>
      <a href="profile.php">
        <span class="ico"><i class="fas fa-user"></i></span>
        <span class="link">الملف الشخصي</span>
      </a>
    </li>
    <li>
      <a href="categories.php">
        <span class="ico"><i class="fas fa-tag"></i></span>
        <span class="link">التصنيفات</span>
      </a>
    </li>
    <li data-toggle="collapse" data-target="#menu">
      <a href="#">
        <span class="ico"><i class="fas fa-pencil-alt"></i></span>
        <span class="link">المقالات</span>
      </a>
    </li>
    <ul class="collapse" id="menu">
      <li>
        <a href="new-post.php">
          <span class="ico"><i class="fas fa-edit"></i></span>
          <span class="link">مقال جديد</span>
        </a>
      </li>
      <li>
        <a href="posts.php">
          <span class="ico"><i class="fas fa-th-large"></i></span>
          <span class="link">كل المقالات</span>
        </a>
      </li>
    </ul>
    <li>
      <a href="messages.php">
        <span class="ico"><i class="fas fa-comment-alt"></i></span>
        <span class="link">الرسائل</span>
      </a>
    </li>
    <li>
      <a href="../" target="_blank">
        <span class="ico"><i class="fas fa-globe"></i></span>
        <span class="link">عرض الموقع</span>
      </a>
    </li>
    <li>
      <a href="logout.php">
        <span class="ico"><i class="fas fa-sign-out-alt"></i></span>
        <span class="link">تسجيل الخروج</span>
      </a>
    </li>
  </ul>
</div>
<!-- End Menu Links -->
