<?php
session_start();
require_once '../config/config.php';
include '../includes/header.php';
$searchQuery = "";
$genre = "";
$year = "";
$results = [];
if ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['search']) || isset($_GET['genre']) || isset($_GET['year']))) {
    $searchQuery = trim($_GET['search']);
    $genre = trim($_GET['genre']);
    $year = trim($_GET['year']);
    $sql = "SELECT * FROM books WHERE 1=1";
    $params = [];
    if (!empty($searchQuery)) {
        $sql .= " AND (title LIKE ? OR author LIKE ?)";
        $params[] = "%$searchQuery%";
        $params[] = "%$searchQuery%";
    }
    if (!empty($genre)) {
        $sql .= " AND genre = ?";
        $params[] = $genre;
    }
    if (!empty($year)) {
        $sql .= " AND publication_year = ?";
        $params[] = $year;
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
}
?>
<h2>Advanced Search</h2>
<form method="GET" action="search.php" class="mb-4">
    <div class="form-group">
        <label for="search">Title or Author</label>
        <input type="text" id="search" name="search" class="form-control" placeholder="Search by title or author" value="<?= htmlspecialchars($searchQuery) ?>">
    </div>
    <div class="form-group">
        <label for="genre">Genre</label>
        <input type="text" id="genre" name="genre" class="form-control" placeholder="Enter genre" value="<?= htmlspecialchars($genre) ?>">
    </div>
    <div class="form-group">
        <label for="year">Publication Year</label>
        <input type="number" id="year" name="year" class="form-control" placeholder="Enter publication year" value="<?= htmlspecialchars($year) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Search</button>
</form>
<?php if(!empty($results)): ?>
<h3>Search Results</h3>
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
          </tr>
      </thead>
      <tbody>
          <?php foreach($results as $book): ?>
          <tr>
              <td><?= htmlspecialchars($book['id']) ?></td>
              <td><?= htmlspecialchars($book['title']) ?></td>
              <td><?= htmlspecialchars($book['author']) ?></td>
              <td><?= htmlspecialchars($book['genre']) ?></td>
              <td><?= htmlspecialchars($book['publication_year']) ?></td>
              <td><?= htmlspecialchars($book['stock']) ?></td>
              <td><?= htmlspecialchars($book['barcode']) ?></td>
          </tr>
          <?php endforeach; ?>
      </tbody>
  </table>
</div>
<?php elseif($_SERVER['REQUEST_METHOD'] == 'GET'): ?>
<p>No results found.</p>
<?php endif; ?>
<?php include '../includes/footer.php'; ?>
