<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
require_once '../config/config.php';
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$book_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();
if (!$book) {
    echo "Book not found!";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $publication_year = $_POST['publication_year'];
    $stock = $_POST['stock'];
    $barcode = $_POST['barcode'];
    $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, genre = ?, publication_year = ?, stock = ?, barcode = ? WHERE id = ?");
    $stmt->execute([$title, $author, $genre, $publication_year, $stock, $barcode, $book_id]);
    header('Location: index.php');
    exit;
}
include '../includes/header.php';
?>
<h2>Edit Book</h2>
<form method="POST">
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" required class="form-control" value="<?= htmlspecialchars($book['title']) ?>">
    </div>
    <div class="form-group">
        <label>Author</label>
        <input type="text" name="author" required class="form-control" value="<?= htmlspecialchars($book['author']) ?>">
    </div>
    <div class="form-group">
        <label>Genre</label>
        <input type="text" name="genre" required class="form-control" value="<?= htmlspecialchars($book['genre']) ?>">
    </div>
    <div class="form-group">
        <label>Publication Year</label>
        <input type="number" name="publication_year" required class="form-control" value="<?= htmlspecialchars($book['publication_year']) ?>">
    </div>
    <div class="form-group">
        <label>Stock</label>
        <input type="number" name="stock" required class="form-control" value="<?= htmlspecialchars($book['stock']) ?>">
    </div>
    <div class="form-group">
        <label>Barcode</label>
        <input type="text" name="barcode" id="barcodeInput" required class="form-control" value="<?= htmlspecialchars($book['barcode']) ?>">
        <div id="barcodeScanner" class="mt-2" style="width:100%; height:300px; border:1px solid #ccc;"></div>
    </div>
    <button type="submit" class="btn btn-primary">Update Book</button>
</form>
<?php include '../includes/footer.php'; ?>
