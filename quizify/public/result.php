<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Total questions count
$stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM questions WHERE quiz_id = ?");
$stmtTotal->execute([$quiz_id]);
$total_questions = $stmtTotal->fetchColumn();

// Correct answers for multiple_choice and true_false questions
$stmtCorrect = $pdo->prepare("
    SELECT COUNT(*) FROM user_answers ua
    JOIN answers a ON ua.answer_id = a.answer_id
    WHERE ua.quiz_id = ? AND ua.user_id = ? AND a.is_correct = 1
");
$stmtCorrect->execute([$quiz_id, $user_id]);
$mc_tf_correct = $stmtCorrect->fetchColumn();

// For fill_blank questions, add your custom checking logic if needed.
$stmtFill = $pdo->prepare("
    SELECT q.question_id, ua.fill_blank_response
    FROM questions q
    JOIN user_answers ua ON q.question_id = ua.question_id
    WHERE q.quiz_id = ? AND q.question_type = 'fill_blank' AND ua.user_id = ?
");
$stmtFill->execute([$quiz_id, $user_id]);
$fill_rows = $stmtFill->fetchAll(PDO::FETCH_ASSOC);

$fill_correct = 0;
foreach ($fill_rows as $row) {
    // Replace 'expected answer' with your logic or store a reference answer.
    $correctAnswer = 'expected answer';
    if (strcasecmp(trim($row['fill_blank_response']), trim($correctAnswer)) === 0) {
        $fill_correct++;
    }
}
$total_correct = $mc_tf_correct + $fill_correct;

$pageTitle = "Quiz Results";
include 'header.php';
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h2>Your Quiz Results</h2>
  </div>
  <div class="card-body">
    <p>Total Questions: <?= $total_questions ?></p>
    <p>Correct Answers: <?= $total_correct ?></p>
    <p>Score: <?= number_format(($total_correct / $total_questions) * 100, 2) ?>%</p>
    <div class="mt-4">
      <a href="view_answers.php?quiz_id=<?= $quiz_id ?>" class="btn btn-info">View Detailed Answers</a>
      <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </div>
  </div>
</div>

<?php 
unset($_SESSION['quiz_questions']);
unset($_SESSION['current_question_index']);
include 'footer.php'; 
?>
