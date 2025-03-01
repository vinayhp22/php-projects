<?php
require_once '../config/config.php';
include '../includes/header.php';

// Fetch all books from the database
$stmt = $pdo->query("SELECT * FROM books");
$books = $stmt->fetchAll();
?>
<h1 class="mb-4">BookHaven Inventory</h1>
<div class="mb-3">
  <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
     <a href="add_book.php" class="btn btn-primary">Add New Book</a>
  <?php endif; ?>
  <a href="borrow_book.php" class="btn btn-success">Borrow Book</a>
  <a href="reports.php" class="btn btn-info">View Reports</a>
</div>
<div class="table-responsive">
  <table class="table table-striped table-hover">
    <thead class="thead-dark">
       <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Author</th>
          <th>Genre</th>
          <th>Year</th>
          <th>Stock</th>
          <th>Barcode</th>
          <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <th>Actions</th>
          <?php endif; ?>
       </tr>
    </thead>
    <tbody>
       <?php foreach($books as $book): ?>
       <tr>
           <td><?= htmlspecialchars($book['id']) ?></td>
           <td><?= htmlspecialchars($book['title']) ?></td>
           <td><?= htmlspecialchars($book['author']) ?></td>
           <td><?= htmlspecialchars($book['genre']) ?></td>
           <td><?= htmlspecialchars($book['publication_year']) ?></td>
           <td><?= htmlspecialchars($book['stock']) ?></td>
           <td><?= htmlspecialchars($book['barcode']) ?></td>
           <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
           <td>
               <a href="edit_book.php?id=<?= $book['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
               <a href="delete_book.php?id=<?= $book['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
           </td>
           <?php endif; ?>
       </tr>
       <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include '../includes/footer.php'; ?>
