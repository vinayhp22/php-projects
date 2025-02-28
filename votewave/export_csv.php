<?php
// export_csv.php
include 'db.php';

$poll_id = isset($_GET['poll_id']) ? intval($_GET['poll_id']) : 0;

// Fetch poll (for error checking)
$pollResult = $conn->query("SELECT * FROM polls WHERE id = $poll_id");
if($pollResult->num_rows == 0){
    die("Poll not found.");
}

// Set headers to download CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="poll_'.$poll_id.'_results.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Option', 'Votes']);

// Fetch options and their vote counts
$optionsResult = $conn->query("SELECT id, option_text FROM poll_options WHERE poll_id = $poll_id");
while($option = $optionsResult->fetch_assoc()){
    $option_id = $option['id'];
    $countResult = $conn->query("SELECT COUNT(*) as total FROM votes WHERE poll_id = $poll_id AND option_id = $option_id");
    $countRow = $countResult->fetch_assoc();
    fputcsv($output, [$option['option_text'], $countRow['total']]);
}
fclose($output);
exit;
?>
