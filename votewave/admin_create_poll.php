<?php
// admin_create_poll.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include 'includes/header.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title       = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $email       = $conn->real_escape_string($_POST['email']);
    
    // Insert poll
    $conn->query("INSERT INTO polls (title, description, email) VALUES ('$title', '$description', '$email')");
    $poll_id = $conn->insert_id;
    
    // Insert options
    if (isset($_POST['options']) && is_array($_POST['options'])) {
        foreach ($_POST['options'] as $option) {
            $option = $conn->real_escape_string($option);
            if (!empty($option)) {
                $conn->query("INSERT INTO poll_options (poll_id, option_text) VALUES ($poll_id, '$option')");
            }
        }
    }
    
    header("Location: admin_manage_results.php?poll_id=$poll_id");
    exit;
}
?>
<main>
    <div class="vw-container">
        <h2 class="vw-heading">Create Poll</h2>
        <div class="vw-card vw-card-poll">
            <form method="post" action="admin_create_poll.php">
                <div class="vw-form-group">
                    <label for="poll-title">Poll Title:</label>
                    <input type="text" id="poll-title" name="title" placeholder="Enter poll title" required>
                </div>
                <div class="vw-form-group">
                    <label for="poll-description">Poll Description:</label>
                    <textarea id="poll-description" name="description" placeholder="Enter poll description" required></textarea>
                </div>
                <div class="vw-form-group">
                    <label for="contact-email">Contact Email:</label>
                    <input type="email" id="contact-email" name="email" placeholder="Enter contact email" required>
                </div>
                <div id="vw-options">
                    <div class="vw-form-group option-group vw-option-row">
                        <label for="option-1">Option 1:</label>
                        <input type="text" id="option-1" name="options[]" placeholder="Enter option text" required>
                        <!-- First option does not have a remove button -->
                    </div>
                </div>
                <button type="button" id="vw-addOption" class="vw-btn">Add Option</button>
                <br><br>
                <input type="submit" class="vw-btn" value="Create Poll">
            </form>
        </div>
    </div>
</main>
<script>
// Function to attach click handlers to all remove buttons
function attachRemoveButtonHandlers() {
    var removeButtons = document.querySelectorAll('.vw-removeOption');
    removeButtons.forEach(function(button) {
        button.addEventListener('click', function(){
            var group = this.parentNode;
            var groups = document.querySelectorAll('.option-group');
            if (groups.length > 1) {
                group.parentNode.removeChild(group);
                updateOptionLabels();
            }
        });
    });
}

// Update the labels and IDs for option groups
function updateOptionLabels() {
    var groups = document.querySelectorAll('.option-group');
    groups.forEach(function(group, index) {
        var newIndex = index + 1;
        var label = group.querySelector('label');
        label.textContent = "Option " + newIndex + ":";
        var input = group.querySelector('input');
        input.id = "option-" + newIndex;
        label.setAttribute("for", "option-" + newIndex);
    });
}

// Handler for adding a new option group
document.getElementById('vw-addOption').addEventListener('click', function(){
    var optionsDiv = document.getElementById('vw-options');
    var optionCount = optionsDiv.getElementsByTagName('input').length + 1;
    var newGroup = document.createElement('div');
    newGroup.className = "vw-form-group option-group vw-option-row";
    
    var newLabel = document.createElement('label');
    newLabel.textContent = "Option " + optionCount + ":";
    newLabel.setAttribute("for", "option-" + optionCount);
    
    var newInput = document.createElement('input');
    newInput.type = "text";
    newInput.name = "options[]";
    newInput.id = "option-" + optionCount;
    newInput.placeholder = "Enter option text";
    newInput.required = true;
    
    // Create the remove button for the new option group
    var removeButton = document.createElement('button');
    removeButton.type = "button";
    removeButton.className = "vw-btn vw-removeOption vw-remove-btn";
    removeButton.textContent = "Remove";
    
    newGroup.appendChild(newLabel);
    newGroup.appendChild(newInput);
    newGroup.appendChild(removeButton);
    optionsDiv.appendChild(newGroup);
    
    updateOptionLabels();
    attachRemoveButtonHandlers();
});

// Attach remove handlers on page load
attachRemoveButtonHandlers();
</script>
<?php include 'includes/footer.php'; ?>
