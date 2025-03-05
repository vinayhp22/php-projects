<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../public/index.php');
    exit;
}
require_once '../config/db.php';

$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) {
    header('Location: index.php');
    exit;
}

$stmtQuiz = $pdo->prepare("SELECT * FROM quizzes WHERE quiz_id = ?");
$stmtQuiz->execute([$quiz_id]);
$quiz = $stmtQuiz->fetch(PDO::FETCH_ASSOC);
if (!$quiz) {
    die("Quiz not found.");
}

$stmtQuestions = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$stmtQuestions->execute([$quiz_id]);
$questions = $stmtQuestions->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "View Questions - Quizify Admin";
include '../public/header.php';
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h2>Questions for: <?= htmlspecialchars($quiz['title']) ?></h2>
  </div>
  <div class="card-body">
    <div class="mb-3">
      <a href="add_question.php?quiz_id=<?= $quiz_id ?>" class="btn btn-primary">Add New Question</a>
      <a href="import_questions.php?quiz_id=<?= $quiz_id ?>" class="btn btn-success">Import Questions from Excel</a>
      <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
    <table class="table table-bordered">
      <thead>
         <tr>
           <th>Question ID</th>
           <th>Question Text</th>
           <th>Type</th>
           <th>Actions</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach($questions as $question): ?>
         <tr>
           <td><?= $question['question_id'] ?></td>
           <td><?= htmlspecialchars($question['question_text']) ?></td>
           <td><?= $question['question_type'] ?></td>
           <td>
             <a href="edit_question.php?question_id=<?= $question['question_id'] ?>&quiz_id=<?= $quiz_id ?>" class="btn btn-sm btn-warning">Edit</a>
           </td>
         </tr>
         <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../public/footer.php'; ?>
