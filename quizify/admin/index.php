<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../public/index.php');
    exit;
}
require_once '../config/db.php';

$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE created_by = ?");
$stmt->execute([$_SESSION['user_id']]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Admin Dashboard - Quizify";
include '../public/header.php';
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h2>Admin Dashboard</h2>
  </div>
  <div class="card-body">
    <div class="mb-3">
      <a href="add_quiz.php" class="btn btn-primary">Add New Quiz</a>
      <a href="analytics.php" class="btn btn-secondary">View Analytics</a>
    </div>
    <table class="table table-bordered">
      <thead>
         <tr>
           <th>Quiz ID</th>
           <th>Title</th>
           <th>Created At</th>
           <th>Actions</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach($quizzes as $quiz): ?>
         <tr>
           <td><?= $quiz['quiz_id'] ?></td>
           <td><?= htmlspecialchars($quiz['title']) ?></td>
           <td><?= $quiz['created_at'] ?></td>
           <td>
             <a href="add_question.php?quiz_id=<?= $quiz['quiz_id'] ?>" class="btn btn-sm btn-warning">Add Questions</a>
             <a href="view_questions.php?quiz_id=<?= $quiz['quiz_id'] ?>" class="btn btn-sm btn-info">View Questions</a>
           </td>
         </tr>
         <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../public/footer.php'; ?>
