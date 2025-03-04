<?php include_once '../includes/header.php'; ?>

<?php
// Only admin can create events (likely enforced via router)
// Make sure $pdo, $tagObj, $categoryObj, etc. are available

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Grab data from the form
    $title         = $_POST['title'];
    $description   = $_POST['description'];
    $category_id   = $_POST['category_id'];
    $event_date    = $_POST['event_date'];
    $location      = $_POST['location'];
    $max_attendees = $_POST['max_attendees'];

    // The user_id is the ADMIN currently logged in
    $user_id = $_SESSION['user_id'];  // from session

    // 1) Insert the new event
    $stmt = $pdo->prepare("
        INSERT INTO events (title, description, category_id, user_id, event_date, location, max_attendees)
        VALUES (:title, :desc, :cat, :uid, :dt, :loc, :max)
    ");
    $stmt->execute([
        ':title' => $title,
        ':desc'  => $description,
        ':cat'   => $category_id,
        ':uid'   => $user_id,
        ':dt'    => $event_date,
        ':loc'   => $location,
        ':max'   => $max_attendees
    ]);

    // Get the newly created event_id
    $newEventId = $pdo->lastInsertId();

    // 2) Gather all selected tags from checkboxes
    $selectedTags = isset($_POST['tags']) ? $_POST['tags'] : [];

    // 3) Handle "Create new tag" input
    //    If you only want one new tag, we treat it as a single input field "new_tag"
    //    If you want multiple new tags, you could parse them if separated by commas, etc.
    if (!empty($_POST['new_tag'])) {
        $newTagName = trim($_POST['new_tag']);

        // Create the new tag in DB (assuming you have a createTag method)
        // e.g. $newTagId = $tagObj->createTag($newTagName);

        $tagInsert = $pdo->prepare("INSERT INTO tags (name) VALUES (:tname)");
        $tagInsert->execute([':tname' => $newTagName]);
        $newTagId = $pdo->lastInsertId(); 

        // Add this new tag's ID to the $selectedTags array
        $selectedTags[] = $newTagId;
    }

    // 4) Insert into event_tags junction table
    //    For each tag ID in $selectedTags, create a row referencing the new event
    foreach ($selectedTags as $tagId) {
        $stmtTags = $pdo->prepare("
            INSERT INTO event_tags (event_id, tag_id) 
            VALUES (:eid, :tid)
        ");
        $stmtTags->execute([
            ':eid' => $newEventId,
            ':tid' => $tagId
        ]);
    }

    echo "<div class='alert alert-success'>
            Event created successfully. 
            <a href='index.php?action=list'>Go to events</a>
          </div>";
}

// Fetch categories and existing tags to show in the form
$categories = $categoryObj->getAllCategories();
$allTags = $tagObj->getAllTags();
?>

<div class="card shadow">
  <div class="card-header bg-dark text-light">
    <h4 class="card-title mb-0">Create Event</h4>
  </div>
  <div class="card-body">
    <form action="" method="POST">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input 
          required 
          type="text" 
          name="title" 
          class="form-control" 
          placeholder="Enter event title"
        >
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea 
          name="description" 
          class="form-control" 
          rows="4" 
          placeholder="Describe the event"
        ></textarea>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select" required>
            <option value="">Select Category</option>
            <?php foreach($categories as $cat): ?>
              <option value="<?php echo $cat['category_id']; ?>">
                <?php echo htmlspecialchars($cat['name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Event Date & Time</label>
          <input required type="datetime-local" name="event_date" class="form-control">
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Location</label>
        <input type="text" name="location" class="form-control" placeholder="e.g. New York City">
      </div>
      <div class="mb-3">
        <label class="form-label">Max Attendees</label>
        <input type="number" name="max_attendees" class="form-control" value="50">
      </div>

      <!-- Existing Tags checkboxes -->
      <div class="mb-3">
        <label class="form-label d-block">Select Existing Tags</label>
        <?php foreach($allTags as $tg): ?>
          <div class="form-check form-check-inline mb-2">
            <input 
              class="form-check-input" 
              type="checkbox" 
              name="tags[]" 
              value="<?php echo $tg['tag_id']; ?>"
              id="tag_<?php echo $tg['tag_id']; ?>"
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
        <label class="form-label">Or Create a New Tag</label>
        <input 
          type="text" 
          name="new_tag" 
          class="form-control"
          placeholder="Type new tag name here..."
        >
        <small class="text-muted">
          This will be added to the database and assigned to this event.
        </small>
      </div>

      <button type="submit" class="btn btn-success">Create Event</button>
    </form>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>
