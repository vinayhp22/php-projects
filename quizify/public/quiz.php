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

$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) {
    die("Quiz not found.");
}

if (!isset($_SESSION['quiz_questions'])) {
    $stmtQ = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY RAND()");
    $stmtQ->execute([$quiz_id]);
    $questions = $stmtQ->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['quiz_questions'] = $questions;
} else {
    $questions = $_SESSION['quiz_questions'];
}
$_SESSION['current_question_index'] = $_SESSION['current_question_index'] ?? 0;
$currentIndex = $_SESSION['current_question_index'];

if ($currentIndex >= count($questions)) {
    header("Location: result.php?quiz_id=" . $quiz_id);
    exit;
}

$currentQuestion = $questions[$currentIndex];
$answers = [];
if ($currentQuestion['question_type'] !== 'fill_blank') {
    $stmtA = $pdo->prepare("SELECT * FROM answers WHERE question_id = ?");
    $stmtA->execute([$currentQuestion['question_id']]);
    $answers = $stmtA->fetchAll(PDO::FETCH_ASSOC);
}

$pageTitle = "Take Quiz: " . htmlspecialchars($quiz['title']);
include 'header.php';
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h2><?= htmlspecialchars($quiz['title']) ?></h2>
  </div>
  <div class="card-body">
    <h5 class="card-title">Question <?= $currentIndex + 1 ?> of <?= count($questions) ?></h5>
    <p class="card-text"><?= htmlspecialchars($currentQuestion['question_text']) ?></p>
    <div id="timer" class="text-danger h5 mb-3"></div>
    <form id="answerForm" method="POST" action="process_answer.php">
       <input type="hidden" name="question_id" value="<?= $currentQuestion['question_id'] ?>">
       <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
       <?php if ($currentQuestion['question_type'] === 'multiple_choice'): ?>
           <?php foreach($answers as $ans): ?>
             <div class="form-check">
                <input class="form-check-input" type="radio" name="answer_id" value="<?= $ans['answer_id'] ?>" required>
                <label class="form-check-label">
                    <?= htmlspecialchars($ans['answer_text']) ?>
                </label>
             </div>
           <?php endforeach; ?>
       <?php elseif ($currentQuestion['question_type'] === 'true_false'): ?>
           <?php foreach($answers as $ans): ?>
             <div class="form-check">
                <input class="form-check-input" type="radio" name="answer_id" value="<?= $ans['answer_id'] ?>" required>
                <label class="form-check-label">
                    <?= htmlspecialchars($ans['answer_text']) ?>
                </label>
             </div>
           <?php endforeach; ?>
       <?php elseif ($currentQuestion['question_type'] === 'fill_blank'): ?>
           <div class="mb-3">
             <input type="text" name="fill_blank_response" class="form-control" placeholder="Type your answer..." required>
           </div>
       <?php endif; ?>
       <button type="submit" class="btn btn-primary mt-3">Next</button>
    </form>
  </div>
</div>

<script>
  let timeLeft = 15;
  let timerDisplay = document.getElementById('timer');
  let answerForm = document.getElementById('answerForm');
  let countdown = setInterval(function() {
    timerDisplay.textContent = 'Time Remaining: ' + timeLeft + 's';
    timeLeft--;
    if (timeLeft < 0) {
      clearInterval(countdown);
      answerForm.submit();
    }
  }, 1000);
</script>

<?php include 'footer.php'; ?>
