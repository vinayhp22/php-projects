<?php include_once '../includes/header.php'; ?>

<?php
// Retrieve the selected tag_id (if you're doing tag-based filtering)
$selectedTagId = isset($_GET['tag_id']) ? $_GET['tag_id'] : null;

// If you want a dropdown filter or some approach, you can do it here. 
// For simplicity, let's just fetch all events or filter them:
if ($selectedTagId) {
    $stmt = $pdo->prepare("
        SELECT e.*, c.name AS category_name 
        FROM events e
        JOIN categories c ON e.category_id = c.category_id
        JOIN event_tags et ON e.event_id = et.event_id
        WHERE et.tag_id = :tag_id
        ORDER BY e.event_date ASC
    ");
    $stmt->execute([':tag_id' => $selectedTagId]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $events = $eventObj->getEvents();
}

// If you want to display all tags as a filter, fetch them here
$allTags = $tagObj->getAllTags();
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <h2 class="mb-0">All Events</h2>

  <!-- Example Tag Filter (Dropdown) -->
  <form method="GET" action="index.php" class="d-flex align-items-center">
    <!-- If your router expects action=list for default, include that too -->
    <input type="hidden" name="action" value="list">

    <label for="tagFilter" class="me-2">Filter by Tag:</label>
    <select 
      name="tag_id" 
      id="tagFilter" 
      class="form-select me-2"
      style="min-width:200px;"
      onchange="this.form.submit()"
    >
      <option value="">-- All Tags --</option>
      <?php foreach($allTags as $tg): ?>
        <option 
          value="<?php echo $tg['tag_id']; ?>"
          <?php if($selectedTagId == $tg['tag_id']) echo 'selected'; ?>
        >
          <?php echo htmlspecialchars($tg['name']); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>
</div>

<?php if(!$events): ?>
  <div class="alert alert-warning">
    No events found.
  </div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>Title</th>
          <th>Category</th>
          <th>Date</th>
          <th>Location</th>
          <th style="width:300px;">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($events as $evt): ?>
        <tr>
          <td class="fw-semibold"><?php echo htmlspecialchars($evt['title']); ?></td>
          <td><?php echo htmlspecialchars($evt['category_name']); ?></td>
          <td><?php echo date('Y-m-d H:i', strtotime($evt['event_date'])); ?></td>
          <td><?php echo htmlspecialchars($evt['location']); ?></td>
          <td>
            <a class="btn btn-sm btn-outline-info" 
               href="index.php?action=details&event_id=<?php echo $evt['event_id']; ?>">
               View
            </a>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
              <a class="btn btn-sm btn-outline-warning" 
                 href="index.php?action=edit&event_id=<?php echo $evt['event_id']; ?>">
                 Edit
              </a>
              <a class="btn btn-sm btn-outline-primary"
                href="index.php?action=waitlist&event_id=<?php echo $evt['event_id']; ?>"
                >
                Manage Waitlist
              </a>
              <a class="btn btn-sm btn-outline-danger" 
                 href="index.php?action=delete&event_id=<?php echo $evt['event_id']; ?>"
                 onclick="return confirm('Are you sure you want to delete this event?')">
                 Delete
              </a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php include_once '../includes/footer.php'; ?>
