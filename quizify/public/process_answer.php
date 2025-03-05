<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$question_id = $_POST['question_id'] ?? null;
$quiz_id     = $_POST['quiz_id'] ?? null;
$user_id     = $_SESSION['user_id'];

$fill_blank_response = $_POST['fill_blank_response'] ?? null;
$answer_id = $_POST['answer_id'] ?? null;

$stmt = $pdo->prepare("
    INSERT INTO user_answers (user_id, quiz_id, question_id, answer_id, fill_blank_response)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->execute([$user_id, $quiz_id, $question_id, $answer_id, $fill_blank_response]);

$_SESSION['current_question_index'] = ($_SESSION['current_question_index'] ?? 0) + 1;
header("Location: quiz.php?quiz_id=" . $quiz_id);
exit;
