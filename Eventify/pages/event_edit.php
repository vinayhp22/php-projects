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

// For pre-selecting tags currently assigned to this event
$currentTags = $eventObj->getEventTags($event_id);
// e.g. each item is ['tag_id' => X, 'name' => '...']
$currentTagIds = array_map(function($t) { 
    return $t['tag_id']; 
}, $currentTags);

// If the form was submitted...
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $title         = $_POST['title'];
    $description   = $_POST['description'];
    $category_id   = $_POST['category_id'];
    $event_date    = $_POST['event_date'];
    $location      = $_POST['location'];
    $max_attendees = $_POST['max_attendees'];

    // Gather the selected existing tags
    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];

    // Handle the "Add new tag" field
    if (!empty($_POST['new_tag'])) {
        $newTagName = trim($_POST['new_tag']);
        // Insert the new tag into the 'tags' table
        $tagStmt = $pdo->prepare("INSERT INTO tags (name) VALUES (:tname)");
        $tagStmt->execute([':tname' => $newTagName]);
        $newTagId = $pdo->lastInsertId();
        
        // Add this newly-created tag ID to our $tags array
        $tags[] = $newTagId;
    }

    // Now update the event data, including the tags array
    $eventObj->updateEvent(
        $event_id,
        $title,
        $description,
        $category_id,
        $event_date,
        $location,
        $max_attendees,
        $tags
    );

    echo "<div class='alert alert-success'>
            Event updated. 
            <a href='index.php?action=list'>Go back</a>
          </div>";
    // You could exit here if you want, so it doesn't re-show the form
    // exit;
}

// Fetch categories, all tags for the form
$categories = $categoryObj->getAllCategories();
$allTags = $tagObj->getAllTags();
?>

<div class="card shadow">
  <div class="card-header bg-dark text-light">
    <h4 class="card-title mb-0">Edit Event</h4>
  </div>
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input 
          required 
          type="text" 
          name="title" 
          class="form-control"
          value="<?php echo htmlspecialchars($event['title']); ?>"
        >
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea 
          name="description" 
          class="form-control" 
          rows="4"
        ><?php echo htmlspecialchars($event['description']); ?></textarea>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select" required>
            <?php foreach($categories as $cat): ?>
              <option 
                value="<?php echo $cat['category_id']; ?>"
                <?php if($event['category_id'] == $cat['category_id']) echo 'selected'; ?>
              >
                <?php echo htmlspecialchars($cat['name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Event Date & Time</label>
          <input 
            type="datetime-local"
            name="event_date"
            class="form-control"
            value="<?php echo date('Y-m-d\TH:i', strtotime($event['event_date'])); ?>"
            required
          >
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Location</label>
        <input 
          type="text" 
          name="location" 
          class="form-control"
          value="<?php echo htmlspecialchars($event['location']); ?>"
        >
      </div>
      <div class="mb-3">
        <label class="form-label">Max Attendees</label>
        <input 
          type="number" 
          name="max_attendees" 
          class="form-control"
          value="<?php echo $event['max_attendees']; ?>"
        >
      </div>

      <!-- Existing Tags checkboxes -->
      <div class="mb-3">
        <label class="form-label d-block">Tags</label>
        <?php foreach($allTags as $tg): ?>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="checkbox"
              name="tags[]"
              value="<?php echo $tg['tag_id']; ?>"
              id="tag_<?php echo $tg['tag_id']; ?>"
              <?php if(in_array($tg['tag_id'], $currentTagIds)) echo 'checked'; ?>
            >
            <label 
              class="form-check-label"
              for="tag_<?php echo $tg['tag_id']; ?>"
            >
              <?php echo htmlspecialchars($tg['name']); ?>
            </label>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Create a new Tag -->
      <div class="mb-3">
        <label class="form-label">Add a New Tag</label>
        <input 
          type="text"
          name="new_tag"
          class="form-control"
          placeholder="Type a new tag here..."
        >
        <small class="text-muted">
          If provided, this will be created and assigned to the event.
        </small>
      </div>

      <button type="submit" class="btn btn-primary">Update Event</button>
    </form>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>
