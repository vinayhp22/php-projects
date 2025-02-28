<?php
// create_poll.php
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
    if(isset($_POST['options']) && is_array($_POST['options'])){
        foreach($_POST['options'] as $option){
            $option = $conn->real_escape_string($option);
            if(!empty($option)){
                $conn->query("INSERT INTO poll_options (poll_id, option_text) VALUES ($poll_id, '$option')");
            }
        }
    }
    
    header("Location: vote.php?poll_id=$poll_id");
    exit;
}
?>
<main>
    <h2>Create Poll</h2>
    <form method="post" action="create_poll.php">
        <label>Poll Title:</label>
        <input type="text" name="title" required>
        
        <label>Poll Description:</label>
        <textarea name="description" required></textarea>
        
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <div id="options">
            <label>Option 1:</label>
            <input type="text" name="options[]" required>
        </div>
        <button type="button" id="addOption">Add Option</button>
        <br><br>
        <input type="submit" value="Submit">
    </form>
</main>
<script>
document.getElementById('addOption').addEventListener('click', function(){
    var optionsDiv = document.getElementById('options');
    var optionCount = optionsDiv.getElementsByTagName('input').length + 1;
    var newOptionLabel = document.createElement('label');
    newOptionLabel.textContent = "Option " + optionCount + ":";
    var newOptionInput = document.createElement('input');
    newOptionInput.type = "text";
    newOptionInput.name = "options[]";
    newOptionInput.required = true;
    optionsDiv.appendChild(newOptionLabel);
    optionsDiv.appendChild(newOptionInput);
});
</script>
<?php include 'includes/footer.php'; ?>
