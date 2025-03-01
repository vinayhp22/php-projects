<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
require_once '../config/config.php';
$borrowerIsAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
if ($borrowerIsAdmin) {
    $stmtUsers = $pdo->prepare("SELECT id, username FROM users WHERE role = 'user'");
    $stmtUsers->execute();
    $users = $stmtUsers->fetchAll();
    if (empty($users)) {
        echo "<div class='alert alert-warning'>Please register users first before borrowing.</div>";
        include '../includes/footer.php';
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'];
    $borrower = $borrowerIsAdmin ? $_POST['borrower'] : $_SESSION['username'];
    $borrow_date = date('Y-m-d');
    $stmt = $pdo->prepare("INSERT INTO borrowings (book_id, borrower, borrow_date) VALUES (?, ?, ?)");
    $stmt->execute([$book_id, $borrower, $borrow_date]);
    $stmt = $pdo->prepare("UPDATE books SET stock = stock - 1 WHERE id = ? AND stock > 0");
    $stmt->execute([$book_id]);
    header('Location: index.php');
    exit;
}
$stmtBooks = $pdo->query("SELECT * FROM books WHERE stock > 0");
$availableBooks = $stmtBooks->fetchAll();
include '../includes/header.php';
?>
<h2>Borrow a Book</h2>
<form method="POST">
    <div class="form-group">
        <label>Select Book</label>
        <select name="book_id" required class="form-control">
            <?php foreach($availableBooks as $book): ?>
            <option value="<?= $book['id'] ?>"><?= htmlspecialchars($book['title']) ?> (Stock: <?= htmlspecialchars($book['stock']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if ($borrowerIsAdmin): ?>
        <div class="form-group">
            <label>Select Borrower</label>
            <select name="borrower" required class="form-control">
                <?php foreach($users as $user): ?>
                <option value="<?= htmlspecialchars($user['username']) ?>"><?= htmlspecialchars($user['username']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php else: ?>
        <input type="hidden" name="borrower" value="<?= htmlspecialchars($_SESSION['username']) ?>">
        <p>Borrowing as: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>
    <?php endif; ?>
    <button type="submit" class="btn btn-success">Borrow</button>
</form>
<?php include '../includes/footer.php'; ?>
