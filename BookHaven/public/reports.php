<?php
session_start();
require_once '../config/config.php';
$lowStockThreshold = 5;
$stmt = $pdo->prepare("SELECT * FROM books WHERE stock <= ?");
$stmt->execute([$lowStockThreshold]);
$lowStockBooks = $stmt->fetchAll();
$stmt2 = $pdo->prepare("SELECT b.title, br.borrower, br.borrow_date FROM borrowings br JOIN books b ON br.book_id = b.id WHERE br.borrow_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
$stmt2->execute();
$recentBorrowings = $stmt2->fetchAll();
include '../includes/header.php';
?>
<h2>Reports</h2>
<h3>Low Stock Books</h3>
<div class="table-responsive">
  <table class="table table-bordered table-striped">
      <thead class="thead-dark">
          <tr>
              <th>Title</th>
              <th>Stock</th>
          </tr>
      </thead>
      <tbody>
          <?php foreach($lowStockBooks as $book): ?>
          <tr>
              <td><?= htmlspecialchars($book['title']) ?></td>
              <td><?= htmlspecialchars($book['stock']) ?></td>
          </tr>
          <?php endforeach; ?>
      </tbody>
  </table>
</div>
<h3>Recent Borrowings (Last 30 Days)</h3>
<div class="table-responsive">
  <table class="table table-bordered table-striped">
      <thead class="thead-dark">
          <tr>
              <th>Title</th>
              <th>Borrower</th>
              <th>Borrow Date</th>
          </tr>
      </thead>
      <tbody>
          <?php foreach($recentBorrowings as $borrowing): ?>
          <tr>
              <td><?= htmlspecialchars($borrowing['title']) ?></td>
              <td><?= htmlspecialchars($borrowing['borrower']) ?></td>
              <td><?= htmlspecialchars($borrowing['borrow_date']) ?></td>
          </tr>
          <?php endforeach; ?>
      </tbody>
  </table>
</div>
<?php include '../includes/footer.php'; ?>
