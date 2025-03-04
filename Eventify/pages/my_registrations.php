<?php include_once '../includes/header.php'; ?>

<?php
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>You must be logged in to view your registrations.</div>";
    include_once '../includes/footer.php';
    exit;
}

$userId = $_SESSION['user_id'];
$sql = "
    SELECT e.*
    FROM events e
    INNER JOIN attendees a ON e.event_id = a.event_id
    WHERE a.user_id = :user_id
    ORDER BY e.event_date DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $userId]);
$registeredEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4">My Registered Events</h2>

<?php if(!$registeredEvents): ?>
  <div class="alert alert-info">You haven't registered for any events yet.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>Event Title</th>
          <th>Date & Time</th>
          <th>Location</th>
          <th>Max Attendees</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($registeredEvents as $evt): ?>
        <tr>
          <td>
            <a href="index.php?action=details&event_id=<?php echo $evt['event_id']; ?>" class="fw-semibold">
              <?php echo htmlspecialchars($evt['title']); ?>
            </a>
          </td>
          <td><?php echo date('Y-m-d H:i', strtotime($evt['event_date'])); ?></td>
          <td><?php echo htmlspecialchars($evt['location']); ?></td>
          <td><?php echo $evt['max_attendees']; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php include_once '../includes/footer.php'; ?>
