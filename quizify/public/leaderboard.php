<?php
session_start();
require_once '../config/db.php';

$stmt = $pdo->query("
  SELECT u.username, 
         SUM(CASE WHEN a.is_correct = 1 THEN 1 ELSE 0 END) AS total_correct
  FROM user_answers ua
  JOIN users u ON ua.user_id = u.user_id
  LEFT JOIN answers a ON ua.answer_id = a.answer_id
  GROUP BY ua.user_id
  ORDER BY total_correct DESC
  LIMIT 10
");
$leaders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Leaderboard";
include 'header.php';
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h2>Leaderboard</h2>
  </div>
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Username</th>
          <th>Total Correct Answers</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($leaders as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= $row['total_correct'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary">Back to Home</a>
  </div>
</div>

<?php include 'footer.php'; ?>
