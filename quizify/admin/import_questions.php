<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php");
    exit;
}
require_once '../config/db.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_id = $_POST['quiz_id'] ?? null;
    if (!$quiz_id) {
        die("Quiz ID not specified.");
    }
    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
        die("Error uploading file.");
    }
    $filePath = $_FILES['excel_file']['tmp_name'];
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestRow();
    $startRow = 2;
    for ($row = $startRow; $row <= $highestRow; $row++) {
        $questionText   = trim((string)$sheet->getCellByColumnAndRow(1, $row)->getValue());
        $questionType   = trim((string)$sheet->getCellByColumnAndRow(2, $row)->getValue());
        $answersString  = trim((string)$sheet->getCellByColumnAndRow(3, $row)->getValue());
        $correctAnswer  = trim((string)$sheet->getCellByColumnAndRow(4, $row)->getValue());
        if ($questionText === '') {
            continue;
        }
        $stmtQ = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, question_type) VALUES (?, ?, ?)");
        $stmtQ->execute([$quiz_id, $questionText, $questionType]);
        $question_id = $pdo->lastInsertId();
        if ($questionType === 'multiple_choice' || $questionType === 'true_false') {
            $answerArray = explode(';', $answersString);
            foreach ($answerArray as $ansText) {
                $ansText = trim($ansText);
                if ($ansText === '') continue;
                $isCorrect = (strtolower($ansText) === strtolower($correctAnswer)) ? 1 : 0;
                $stmtA = $pdo->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
                $stmtA->execute([$question_id, $ansText, $isCorrect]);
            }
        } elseif ($questionType === 'fill_blank') {
            if (!empty($correctAnswer)) {
                $stmtA = $pdo->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
                $stmtA->execute([$question_id, $correctAnswer, 1]);
            }
        }
    }
    header("Location: index.php");
    exit;
}

$pageTitle = "Import Questions - Quizify Admin";
include '../public/header.php';
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h2>Import Questions from Excel</h2>
  </div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Quiz ID</label>
        <input type="text" name="quiz_id" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Select Excel File (XLSX)</label>
        <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Import</button>
    </form>
    <hr>
    <h5>Sample Excel Format</h5>
    <p>The Excel file should have the following columns (Row 1 as headers):</p>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>A: question_text</th>
          <th>B: question_type</th>
          <th>C: answers</th>
          <th>D: correct_answer</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>What is the capital of France?</td>
          <td>multiple_choice</td>
          <td>Paris;London;Berlin;Rome</td>
          <td>Paris</td>
        </tr>
        <tr>
          <td>The sky is green.</td>
          <td>true_false</td>
          <td>True;False</td>
          <td>False</td>
        </tr>
        <tr>
          <td>The color of bananas is _____.</td>
          <td>fill_blank</td>
          <td>(N/A)</td>
          <td>yellow</td>
        </tr>
      </tbody>
    </table>
    <p>Note: Row 1 should be headers. Data starts from Row 2.</p>
    <a href="sample_questions_format.xlsx" class="btn btn-info mt-3" download>Download Sample Excel Format</a>
  </div>
</div>

<?php include '../public/footer.php'; ?>
