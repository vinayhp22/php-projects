<?php
// export_pdf.php
require('fpdf/fpdf.php');
include 'db.php';

// Get the poll ID from the URL
$poll_id = isset($_GET['poll_id']) ? intval($_GET['poll_id']) : 0;

// Fetch poll details
$pollResult = $conn->query("SELECT * FROM polls WHERE id = $poll_id");
if ($pollResult->num_rows == 0) {
    die("Poll not found.");
}
$poll = $pollResult->fetch_assoc();

// Create a new FPDF instance and add a page
$pdf = new FPDF();
$pdf->AddPage();

// Set title font and print the poll title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, $poll['title'] . " - Results", 0, 1, 'C');
$pdf->Ln(5);

// Set table header font
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, "Option", 1);
$pdf->Cell(50, 10, "Votes", 1);
$pdf->Ln();

// Set table data font
$pdf->SetFont('Arial', '', 12);

// Fetch poll options and their vote counts
$optionsResult = $conn->query("SELECT id, option_text FROM poll_options WHERE poll_id = $poll_id");
while ($option = $optionsResult->fetch_assoc()) {
    $option_id = $option['id'];
    $countResult = $conn->query("SELECT COUNT(*) as total FROM votes WHERE poll_id = $poll_id AND option_id = $option_id");
    $countRow = $countResult->fetch_assoc();
    
    $pdf->Cell(100, 10, $option['option_text'], 1);
    $pdf->Cell(50, 10, $countRow['total'], 1);
    $pdf->Ln();
}

// Output the PDF to the browser
$pdf->Output("I", "poll_" . $poll_id . "_results.pdf");
exit;
?>
