<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
require_once '../config/config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title            = $_POST['title'];
    $author           = $_POST['author'];
    $genre            = $_POST['genre'];
    $publication_year = $_POST['publication_year'];
    $stock            = $_POST['stock'];
    $barcode          = $_POST['barcode'];
    $stmt = $pdo->prepare("INSERT INTO books (title, author, genre, publication_year, stock, barcode) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $author, $genre, $publication_year, $stock, $barcode]);
    header('Location: index.php');
    exit;
}
include '../includes/header.php';
?>
<h2>Add New Book</h2>
<form method="POST">
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" required class="form-control" placeholder="Book title">
    </div>
    <div class="form-group">
        <label>Author</label>
        <input type="text" name="author" required class="form-control" placeholder="Book author">
    </div>
    <div class="form-group">
        <label>Genre</label>
        <input type="text" name="genre" required class="form-control" placeholder="Genre">
    </div>
    <div class="form-group">
        <label>Publication Year</label>
        <input type="number" name="publication_year" required class="form-control" placeholder="e.g., 2023">
    </div>
    <div class="form-group">
        <label>Stock</label>
        <input type="number" name="stock" required class="form-control" placeholder="Number of copies">
    </div>
    <div class="form-group">
        <label>Barcode</label>
        <input type="text" name="barcode" id="barcodeInput" required class="form-control" placeholder="Barcode value">
        <div id="barcodeScanner" class="mt-2" style="width:100%; height:300px; border:1px solid #ccc;"></div>
    </div>
    <button type="submit" class="btn btn-primary">Add Book</button>
</form>
<?php include '../includes/footer.php'; ?>
