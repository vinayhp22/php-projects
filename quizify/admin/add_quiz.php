<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../public/index.php');
    exit;
}
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $desc  = $_POST['description'] ?? '';
    $createdBy = $_SESSION['user_id']; // store the admin's user ID

    $stmt = $pdo->prepare("INSERT INTO quizzes (title, description, created_by) VALUES (?, ?, ?)");
    $stmt->execute([$title, $desc, $createdBy]);
    header('Location: index.php');
    exit;
}

$pageTitle = "Add Quiz - Quizify Admin";
include '../public/header.php';
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h2>Add New Quiz</h2>
  </div>
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Quiz Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control"></textarea>
      </div>
      <button type="submit" class="btn btn-primary w-100">Create Quiz</button>
    </form>
  </div>
</div>

<?php include '../public/footer.php'; ?>
