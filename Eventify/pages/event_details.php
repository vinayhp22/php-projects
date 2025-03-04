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

$tags = $eventObj->getEventTags($event_id);

// Check if user is already registered (optional)
$userId = $_SESSION['user_id'] ?? null;
$isRegistered = false;
if ($userId) {
    $checkStmt = $pdo->prepare("
        SELECT attendee_id 
        FROM attendees 
        WHERE event_id = :event_id AND user_id = :user_id
    ");
    $checkStmt->execute([
        ':event_id' => $event_id,
        ':user_id'  => $userId
    ]);
    $isRegistered = $checkStmt->fetchColumn();
}
?>

<div class="card shadow-sm">
  <div class="card-header bg-dark text-light">
    <h3 class="card-title mb-0"><?php echo htmlspecialchars($event['title']); ?></h3>
  </div>
  <div class="card-body">
    <p class="text-muted">
      <strong>Event Date & Time:</strong> 
      <?php echo date('Y-m-d H:i', strtotime($event['event_date'])); ?>
      <br>
      <strong>Location:</strong> 
      <?php echo htmlspecialchars($event['location']); ?>
    </p>
    <p class="card-text">
      <?php echo nl2br(htmlspecialchars($event['description'])); ?>
    </p>
    
    <hr>

    <?php if(!empty($tags)): ?>
      <p>
        <strong>Tags:</strong>
        <?php foreach($tags as $tg) {
          echo "<span class='badge bg-secondary me-1'>".htmlspecialchars($tg['name'])."</span>";
        } ?>
      </p>
    <?php endif; ?>

    <p>
      <strong>Max Attendees:</strong> 
      <?php echo $event['max_attendees']; ?>
    </p>

    <!-- Social sharing links -->
    <p>
      <strong>Share this event:</strong><br>
      <a class="btn btn-sm btn-outline-primary me-1" 
         href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://example.com/?event_id='.$event_id); ?>" 
         target="_blank">
         Facebook
      </a>
      <a class="btn btn-sm btn-outline-info" 
         href="https://twitter.com/intent/tweet?text=<?php echo urlencode($event['title'].' http://example.com/?event_id='.$event_id); ?>"
         target="_blank">
         Twitter
      </a>
    </p>

    <hr>

    <!-- Registration logic -->
    <?php if (!isset($_SESSION['user_id'])): ?>
      <p class="text-danger">
        Please <a href="../auth/login.php">login</a> to register for this event.
      </p>
    <?php else: ?>
      <?php if ($isRegistered): ?>
        <div class="alert alert-success">
          You are already registered for this event. We look forward to your attendance and hope you have a wonderful experience!
        </div>
      <?php else: ?>
        <a class="btn btn-primary" href="index.php?action=register&event_id=<?php echo $event_id; ?>">
          Register Now
        </a>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>
