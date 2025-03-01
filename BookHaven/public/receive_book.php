<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
require_once '../config/config.php';
if (isset($_GET['id'])) {
    $borrow_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT book_id FROM borrowings WHERE id = ? AND return_date IS NULL");
    $stmt->execute([$borrow_id]);
    $borrowing = $stmt->fetch();
    if ($borrowing) {
        $stmtUpdate = $pdo->prepare("UPDATE borrowings SET return_date = CURDATE() WHERE id = ?");
        $stmtUpdate->execute([$borrow_id]);
        $stmtBook = $pdo->prepare("UPDATE books SET stock = stock + 1 WHERE id = ?");
        $stmtBook->execute([$borrowing['book_id']]);
    }
    header("Location: receive_book.php");
    exit;
}
$stmt = $pdo->query("SELECT br.id, b.title, br.borrower, br.borrow_date FROM borrowings br JOIN books b ON br.book_id = b.id WHERE br.return_date IS NULL");
$borrowings = $stmt->fetchAll();
include '../includes/header.php';
?>
<h2>Receive Returned Books</h2>
<?php if (!empty($borrowings)): ?>
<div class="table-responsive">
  <table class="table table-bordered table-striped">
      <thead class="thead-dark">
          <tr>
              <th>Borrowing ID</th>
              <th>Book Title</th>
              <th>Borrower</th>
              <th>Borrow Date</th>
              <th>Action</th>
          </tr>
      </thead>
      <tbody>
          <?php foreach($borrowings as $borrow): ?>
          <tr>
              <td><?= htmlspecialchars($borrow['id']) ?></td>
              <td><?= htmlspecialchars($borrow['title']) ?></td>
              <td><?= htmlspecialchars($borrow['borrower']) ?></td>
              <td><?= htmlspecialchars($borrow['borrow_date']) ?></td>
              <td>
                  <a href="receive_book.php?id=<?= $borrow['id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Mark this book as received?');">Receive</a>
              </td>
          </tr>
          <?php endforeach; ?>
      </tbody>
  </table>
</div>
<?php else: ?>
  <div class="alert alert-info">No borrowed books awaiting return.</div>
<?php endif; ?>
<?php include '../includes/footer.php'; ?>
