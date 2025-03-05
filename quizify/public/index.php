<?php
session_start();
require_once '../config/db.php';

// Handle login
$error = '';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && md5($password) == $user['password_hash']) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header('Location: ../admin/index.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $error = 'Invalid credentials.';
    }
}

// Fetch quizzes
$stmt = $pdo->query("SELECT * FROM quizzes ORDER BY quiz_id DESC");
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If logged in, get list of completed quizzes
$completed_quizzes = [];
if (isset($_SESSION['user_id'])) {
    $stmtCompleted = $pdo->prepare("SELECT DISTINCT quiz_id FROM user_answers WHERE user_id = ?");
    $stmtCompleted->execute([$_SESSION['user_id']]);
    $completed_quizzes = $stmtCompleted->fetchAll(PDO::FETCH_COLUMN);
}

$pageTitle = "Quizify - Home";
include 'header.php';
?>

<hr>
<h2 class="mt-4">Available Quizzes</h2>
<ul class="list-group">
  <?php foreach($quizzes as $quiz): ?>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <?= htmlspecialchars($quiz['title']) ?>
    <?php if (isset($_SESSION['user_id'])): ?>
       <?php if (in_array($quiz['quiz_id'], $completed_quizzes)): ?>
           <a href="view_answers.php?quiz_id=<?= $quiz['quiz_id'] ?>" class="btn btn-sm btn-info">View Answers</a>
       <?php else: ?>
           <a href="quiz.php?quiz_id=<?= $quiz['quiz_id'] ?>" class="btn btn-sm btn-success">Start Quiz</a>
       <?php endif; ?>
    <?php else: ?>
       <span><a class="btn btn-success" href="login.php">Login to start quiz</a> </span>
    <?php endif; ?>
  </li>
  <?php endforeach; ?>
</ul>
<a href="leaderboard.php" class="btn btn-info mt-4">Leaderboard</a>

<?php include 'footer.php'; ?>
