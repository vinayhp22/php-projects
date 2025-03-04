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

// Count how many are not waitlisted
$stmt = $pdo->prepare("
    SELECT COUNT(*) as total 
    FROM attendees 
    WHERE event_id = :event_id AND is_waitlisted = 0
");
$stmt->execute([':event_id' => $event_id]);
$currentCount = $stmt->fetchColumn();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['attendee_name'];
    $email = $_POST['attendee_email'];

    // Check capacity
    $isWaitlisted = ($currentCount >= $event['max_attendees']) ? 1 : 0;

    $regStmt = $pdo->prepare("
        INSERT INTO attendees (event_id, user_id, attendee_name, attendee_email, is_waitlisted)
        VALUES (:event_id, :user_id, :attendee_name, :attendee_email, :is_waitlisted)
    ");
    $regStmt->execute([
        ':event_id'       => $event_id,
        ':user_id'        => $_SESSION['user_id'] ?? 0, // or fetch properly
        ':attendee_name'  => $name,
        ':attendee_email' => $email,
        ':is_waitlisted'  => $isWaitlisted
    ]);

    if($isWaitlisted) {
        echo "<div class='alert alert-warning'>
                Registration successful, but you are on the waitlist.
              </div>";
    } else {
        echo "<div class='alert alert-success'>Registration successful!</div>";
    }
}
?>

<div class="card shadow-sm">
  <div class="card-header bg-dark text-light">
    <h4 class="card-title mb-0">Register for <?php echo htmlspecialchars($event['title']); ?></h4>
  </div>
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Your Name</label>
        <input required type="text" name="attendee_name" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Your Email</label>
        <input required type="email" name="attendee_email" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Register</button>
      <a href="index.php?action=details&event_id=<?php echo $event_id; ?>" 
         class="btn btn-secondary">
         Back to Event
      </a>
    </form>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>
