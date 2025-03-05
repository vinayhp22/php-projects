<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../public/index.php');
    exit;
}
require_once '../config/db.php';

$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_text = $_POST['question_text'] ?? '';
    $question_type = $_POST['question_type'] ?? 'multiple_choice';

    // Insert question into DB
    $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, question_type) VALUES (?, ?, ?)");
    $stmt->execute([$quiz_id, $question_text, $question_type]);
    $question_id = $pdo->lastInsertId();

    // Insert answers based on question type
    if ($question_type === 'multiple_choice') {
        for ($i = 1; $i <= 4; $i++) {
            $answer_text = $_POST["answer_$i"] ?? '';
            $is_correct  = (isset($_POST["correct"]) && $_POST["correct"] == $i) ? 1 : 0;
            $stmt2 = $pdo->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
            $stmt2->execute([$question_id, $answer_text, $is_correct]);
        }
    } elseif ($question_type === 'true_false') {
        $correct_tf = $_POST['correct_tf'] ?? 'False';
        $stmt2 = $pdo->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
        $stmt2->execute([$question_id, 'True', ($correct_tf === 'True' ? 1 : 0)]);
        $stmt2->execute([$question_id, 'False', ($correct_tf === 'False' ? 1 : 0)]);
    }
    header("Location: add_question.php?quiz_id=$quiz_id");
    exit;
}

$pageTitle = "Add Question - Quizify Admin";
include '../public/header.php';
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h2>Add Question (Quiz ID: <?= htmlspecialchars($quiz_id) ?>)</h2>
  </div>
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Question Text</label>
        <textarea name="question_text" class="form-control" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Question Type</label>
        <select name="question_type" id="question_type_select" class="form-select">
          <option value="multiple_choice">Multiple Choice</option>
          <option value="true_false">True/False</option>
          <option value="fill_blank">Fill in the Blank</option>
        </select>
      </div>

      <!-- Multiple Choice Options -->
      <div id="multiple_choice_block">
        <h5>Multiple Choice Options</h5>
        <?php for ($i = 1; $i <= 4; $i++): ?>
        <div class="mb-3">
          <label class="form-label">Choice <?= $i ?></label>
          <input type="text" name="answer_<?= $i ?>" class="form-control">
          <div class="form-check mt-1">
            <input class="form-check-input" type="radio" name="correct" value="<?= $i ?>">
            <label class="form-check-label">Mark as correct</label>
          </div>
        </div>
        <?php endfor; ?>
      </div>

      <!-- True/False Options -->
      <div id="true_false_block" style="display: none;">
        <h5>True/False Options</h5>
        <div class="mb-3">
          <label class="form-label">Select the correct answer:</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="correct_tf" value="True">
            <label class="form-check-label">True</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="correct_tf" value="False">
            <label class="form-check-label">False</label>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Add Question</button>
    </form>
  </div>
</div>

<script>
  const questionTypeSelect = document.getElementById('question_type_select');
  const mcBlock = document.getElementById('multiple_choice_block');
  const tfBlock = document.getElementById('true_false_block');

  function toggleBlocks() {
    if(questionTypeSelect.value === 'multiple_choice') {
      mcBlock.style.display = 'block';
      tfBlock.style.display = 'none';
    } else if(questionTypeSelect.value === 'true_false') {
      mcBlock.style.display = 'none';
      tfBlock.style.display = 'block';
    } else {
      mcBlock.style.display = 'none';
      tfBlock.style.display = 'none';
    }
  }
  questionTypeSelect.addEventListener('change', toggleBlocks);
  toggleBlocks();
</script>

<?php include '../public/footer.php'; ?>
