<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
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

$user_id = $_SESSION['user_id'];
$stmtAnswers = $pdo->prepare("
    SELECT 
        q.question_text, 
        q.question_type,
        ua.answer_id, 
        ua.fill_blank_response,
        ua_ans.answer_text AS user_answer_text,
        (
            SELECT answer_text 
            FROM answers 
            WHERE question_id = q.question_id AND is_correct = 1 
            LIMIT 1
        ) AS correct_answer
    FROM user_answers ua
    LEFT JOIN questions q ON ua.question_id = q.question_id
    LEFT JOIN answers ua_ans ON ua.answer_id = ua_ans.answer_id
    WHERE ua.quiz_id = ? AND ua.user_id = ?
");
$stmtAnswers->execute([$quiz_id, $user_id]);
$user_answers = $stmtAnswers->fetchAll(PDO::FETCH_ASSOC);
$total_questions = count($user_answers);
$correct_count = 0;

$pageTitle = "View Answers - " . htmlspecialchars($quiz['title']);
include 'header.php';
?>
<div class="card shadow-sm">
  <div class="card-header">
    <h2>Answers for: <?= htmlspecialchars($quiz['title']) ?></h2>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Question</th>
          <th>Your Answer</th>
          <th>Correct Answer</th>
          <th>Result</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($user_answers as $ua): ?>
            <?php 
                $is_correct = false;
                if ($ua['question_type'] === 'fill_blank') {
                    $user_answer = $ua['fill_blank_response'];
                    if (strcasecmp(trim($ua['fill_blank_response']), trim($ua['correct_answer'])) === 0) {
                        $is_correct = true;
                    }
                } else {
                    $user_answer = $ua['user_answer_text'];
                    if (strcasecmp(trim($ua['user_answer_text']), trim($ua['correct_answer'])) === 0) {
                        $is_correct = true;
                    }
                }
                if ($is_correct) {
                    $correct_count++;
                }
            ?>
            <tr>
              <td><?= htmlspecialchars($ua['question_text']) ?></td>
              <td><?= htmlspecialchars($user_answer) ?></td>
              <td><?= htmlspecialchars($ua['correct_answer']) ?></td>
              <td>
                <?php if ($is_correct): ?>
                  <span class="text-success">Correct</span>
                <?php else: ?>
                  <span class="text-danger">Wrong</span>
                <?php endif; ?>
              </td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if ($total_questions > 0): ?>
        <div class="alert alert-info">
          <p>Total Questions: <?= $total_questions ?></p>
          <p>Correct Answers: <?= $correct_count ?></p>
          <p>Score: <?= number_format(($correct_count / $total_questions) * 100, 2) ?>%</p>
        </div>
    <?php else: ?>
        <p>No answers found for this quiz.</p>
    <?php endif; ?>
    <div class="d-flex justify-content-between mt-4">
       <a href="retake_test.php?quiz_id=<?= $quiz_id ?>" class="btn btn-warning">Retake Test</a>
       <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
