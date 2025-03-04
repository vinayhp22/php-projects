<?php include_once '../includes/header.php'; ?>

<?php
if(!isset($_GET['event_id'])) {
    echo "<div class='alert alert-danger'>No event specified.</div>";
    include_once '../includes/footer.php';
    exit;
}
$event_id = $_GET['event_id'];
$event = $eventObj->getEventById($event_id);
if(!$event) {
    echo "<div class='alert alert-danger'>Event not found.</div>";
    include_once '../includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("
    SELECT attendee_name, attendee_email
    FROM attendees
    WHERE event_id = :event_id AND is_waitlisted = 1
");
$stmt->execute([':event_id' => $event_id]);
$waitlisted = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-3">Waitlist for "<?php echo htmlspecialchars($event['title']); ?>"</h2>

<?php if(!$waitlisted): ?>
  <div class="alert alert-info">No one is on the waitlist yet.</div>
<?php else: ?>
  <ul class="list-group">
  <?php foreach($waitlisted as $w): ?>
    <li class="list-group-item d-flex justify-content-between align-items-center">
      <span>
        <strong><?php echo htmlspecialchars($w['attendee_name']); ?></strong> 
        (<?php echo htmlspecialchars($w['attendee_email']); ?>)
      </span>
    </li>
  <?php endforeach; ?>
  </ul>
<?php endif; ?>

<a href="index.php?action=list" class="btn btn-secondary mt-3">Back to Events</a>

<?php include_once '../includes/footer.php'; ?>
