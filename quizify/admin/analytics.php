<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php");
    exit;
}
require_once '../config/db.php';

// Compute analytics for each quiz created by the logged-in admin
$stmt = $pdo->prepare("
    SELECT q.quiz_id, q.title,
      COUNT(DISTINCT ua.user_id) AS total_users,
      (SELECT COUNT(*) FROM user_answers ua2 JOIN answers a2 ON ua2.answer_id = a2.answer_id 
       WHERE ua2.quiz_id = q.quiz_id AND a2.is_correct = 1) AS total_correct_answers,
      (SELECT COUNT(*) FROM questions WHERE quiz_id = q.quiz_id) AS total_questions
    FROM quizzes q
    LEFT JOIN user_answers ua ON q.quiz_id = ua.quiz_id
    WHERE q.created_by = ?
    GROUP BY q.quiz_id
");
$stmt->execute([$_SESSION['user_id']]);
$analytics = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Quiz Analytics - Quizify Admin";
include '../public/header.php';
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h2>Quiz Analytics</h2>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
         <tr>
           <th>Quiz Title</th>
           <th>Total Users Attempted</th>
           <th>Total Correct Answers</th>
           <th>Total Questions</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach($analytics as $row): ?>
         <tr>
           <td><?= htmlspecialchars($row['title']) ?></td>
           <td><?= $row['total_users'] ?></td>
           <td><?= $row['total_correct_answers'] ?></td>
           <td><?= $row['total_questions'] ?></td>
         </tr>
         <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../public/footer.php'; ?>
