<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("DELETE FROM user_answers WHERE quiz_id = ? AND user_id = ?");
$stmt->execute([$quiz_id, $user_id]);

unset($_SESSION['quiz_questions']);
unset($_SESSION['current_question_index']);

header("Location: quiz.php?quiz_id=" . $quiz_id);
exit;
