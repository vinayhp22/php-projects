<?php
// export.php
require 'auth.php';  // Ensure user is authenticated
require 'db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=submissions.csv');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, array('ID', 'Name', 'Email', 'Subject', 'Message', 'Attachment', 'Submitted At'));

// Fetch the submissions
$stmt = $pdo->query("SELECT * FROM submissions");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}
fclose($output);
exit();
?>
