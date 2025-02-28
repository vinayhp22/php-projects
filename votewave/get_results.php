<?php
// get_results.php
include 'db.php';

$poll_id = isset($_GET['poll_id']) ? intval($_GET['poll_id']) : 0;
$data = [];

// Fetch all options for the poll and count votes for each
$optionsResult = $conn->query("SELECT id, option_text FROM poll_options WHERE poll_id = $poll_id");
while($option = $optionsResult->fetch_assoc()){
    $option_id = $option['id'];
    $countResult = $conn->query("SELECT COUNT(*) as total FROM votes WHERE poll_id = $poll_id AND option_id = $option_id");
    $countRow = $countResult->fetch_assoc();
    $data[] = [
        'option' => $option['option_text'],
        'votes'  => (int)$countRow['total']
    ];
}
header('Content-Type: application/json');
echo json_encode($data);
?>
