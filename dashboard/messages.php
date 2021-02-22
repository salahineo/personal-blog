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
  // Delete Data
  $query = 'DELETE FROM messages WHERE message_id = :messageID';
  $stmt = $db->prepare($query);
  $stmt->bindParam(':messageID', $_GET['id']);
  $stmt->execute();
  // Return To Categories Page Without Parameters
  header("Location: messages.php");
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
      <div class="col-lg-9 menu-content messages">
        <h3>جميع الرسائل</h3>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-dark">
              <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>البريد الالكتروني</th>
                <th>الرسالة</th>
                <th>تاريخ الارسال</th>
                <th>حذف</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Set Current Page Variable
              isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
              // Messages Virtual ID Counter
              $categoryVirtualID = 0;
              // Start & Limit Of Retrieved Date
              $limit = 4;
              $start = ($page - 1) * $limit;
              // Get Total Pages
              $stmt = $db->query('SELECT * FROM messages');
              $totalPages = ceil($stmt->rowCount() / $limit);
              // Fetch Categories
              $stmt = $db->query('SELECT * FROM messages ORDER BY message_id DESC LIMIT ' . $start . ', ' . $limit);
              while($row = $stmt->fetch()) {
                ?>
                <tr>
                  <td><?php echo $categoryVirtualID += 1; ?></td>
                  <td><?php echo $row['sender_name']; ?></td>
                  <td><?php echo $row['sender_email']; ?></td>
                  <td><?php echo $row['message_content']; ?></td>
                  <td><?php echo explode(' ', $row['message_date'])[0];; ?></td>
                  <td>
                    <a href="?id=<?php echo $row['message_id']; ?>" class="text-dark delete-btn">
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

