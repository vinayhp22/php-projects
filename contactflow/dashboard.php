<?php
// dashboard.php
require 'auth.php';
require 'db.php';

// Fetch submissions ordered by latest first.
$stmt = $pdo->query("SELECT * FROM submissions ORDER BY created_at DESC");
$submissions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ContactFlow - Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .table-responsive {
      margin-top: 20px;
    }
  </style>
</head>
<body>
<?php include 'header.php'; ?>
  <div class="container mt-5">
    <h2 class="mb-4">Submissions Dashboard</h2>
    <div class="mb-3">
      <a href="export.php" class="btn btn-success">Export as CSV</a>
    </div>
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Attachment</th>
            <th>Submitted At</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($submissions as $submission): ?>
          <tr>
            <td><?= htmlspecialchars($submission['id']) ?></td>
            <td><?= htmlspecialchars($submission['name']) ?></td>
            <td><?= htmlspecialchars($submission['email']) ?></td>
            <td><?= htmlspecialchars($submission['subject']) ?></td>
            <td><?= nl2br(htmlspecialchars($submission['message'])) ?></td>
            <td>
              <?php if ($submission['attachment']): ?>
                <a href="uploads/<?= htmlspecialchars($submission['attachment']) ?>" target="_blank">View File</a>
              <?php else: ?>
                N/A
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($submission['created_at']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php include 'footer.php'; ?>
