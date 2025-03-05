<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php");
    exit;
}
require_once '../config/db.php';

$question_id = $_GET['question_id'] ?? null;
$quiz_id = $_GET['quiz_id'] ?? null;
if (!$question_id || !$quiz_id) {
    header("Location: index.php");
    exit;
}

// Fetch question details
$stmtQ = $pdo->prepare("SELECT * FROM questions WHERE question_id = ?");
$stmtQ->execute([$question_id]);
$question = $stmtQ->fetch(PDO::FETCH_ASSOC);
if (!$question) {
    die("Question not found.");
}

// Fetch associated answers if needed
$answers = [];
if ($question['question_type'] !== 'fill_blank') {
    $stmtA = $pdo->prepare("SELECT * FROM answers WHERE question_id = ?");
    $stmtA->execute([$question_id]);
    $answers = $stmtA->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newQuestionText = $_POST['question_text'] ?? '';
    $newType = $_POST['question_type'] ?? 'multiple_choice';
    $stmtUpdateQ = $pdo->prepare("UPDATE questions SET question_text = ?, question_type = ? WHERE question_id = ?");
    $stmtUpdateQ->execute([$newQuestionText, $newType, $question_id]);

    if ($newType === 'multiple_choice') {
        for ($i = 0; $i < 4; $i++) {
            $ansText = $_POST["answer_{$i}_text"] ?? '';
            $ansId = $_POST["answer_{$i}_id"] ?? '';
            $isCorr = (isset($_POST["correct"]) && $_POST["correct"] == $ansId) ? 1 : 0;
            $stmtUpdateA = $pdo->prepare("UPDATE answers SET answer_text = ?, is_correct = ? WHERE answer_id = ?");
            $stmtUpdateA->execute([$ansText, $isCorr, $ansId]);
        }
    } elseif ($newType === 'true_false') {
        for ($i = 0; $i < 2; $i++) {
            $ansText = $_POST["tf_{$i}_text"] ?? '';
            $ansId = $_POST["tf_{$i}_id"] ?? '';
            $correctValue = $_POST['correct_tf'] ?? '';
            $isCorr = (strcasecmp(trim($ansText), trim($correctValue)) === 0) ? 1 : 0;
            $stmtUpdateA = $pdo->prepare("UPDATE answers SET answer_text = ?, is_correct = ? WHERE answer_id = ?");
            $stmtUpdateA->execute([$ansText, $isCorr, $ansId]);
        }
    }
    header("Location: view_questions.php?quiz_id=$quiz_id");
    exit;
}

$pageTitle = "Edit Question - Quizify Admin";
include '../public/header.php';
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h2>Edit Question #<?= htmlspecialchars($question['question_id']) ?></h2>
  </div>
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Question Text</label>
        <textarea name="question_text" class="form-control" required><?= htmlspecialchars($question['question_text']) ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Question Type</label>
        <select name="question_type" id="question_type_select" class="form-select">
          <option value="multiple_choice" <?= $question['question_type'] === 'multiple_choice' ? 'selected' : '' ?>>Multiple Choice</option>
          <option value="true_false" <?= $question['question_type'] === 'true_false' ? 'selected' : '' ?>>True/False</option>
          <option value="fill_blank" <?= $question['question_type'] === 'fill_blank' ? 'selected' : '' ?>>Fill in the Blank</option>
        </select>
      </div>

      <!-- Multiple Choice Block -->
      <div id="multiple_choice_block" style="display: none;">
        <h5>Edit Multiple Choice Answers</h5>
        <?php if($question['question_type'] === 'multiple_choice'): ?>
          <?php foreach($answers as $index => $ans): ?>
            <div class="mb-3">
              <label class="form-label">Choice <?= $index + 1 ?></label>
              <input type="hidden" name="answer_<?= $index ?>_id" value="<?= $ans['answer_id'] ?>">
              <input type="text" name="answer_<?= $index ?>_text" class="form-control" value="<?= htmlspecialchars($ans['answer_text']) ?>">
              <div class="form-check mt-1">
                <input type="radio" name="correct" value="<?= $ans['answer_id'] ?>" class="form-check-input" <?= $ans['is_correct'] ? 'checked' : '' ?>>
                <label class="form-check-label">Mark as correct</label>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No multiple-choice answers available for this question type.</p>
        <?php endif; ?>
      </div>

      <!-- True/False Block -->
      <div id="true_false_block" style="display: none;">
        <h5>Edit True/False Answers</h5>
        <?php if($question['question_type'] === 'true_false'): ?>
          <?php foreach($answers as $index => $ans): ?>
            <div class="mb-3">
              <input type="hidden" name="tf_<?= $index ?>_id" value="<?= $ans['answer_id'] ?>">
              <label class="form-label">Answer Text</label>
              <input type="text" name="tf_<?= $index ?>_text" class="form-control" value="<?= htmlspecialchars($ans['answer_text']) ?>">
            </div>
          <?php endforeach; ?>
          <div class="mb-3">
            <label class="form-label">Correct Answer:</label>
            <div class="form-check">
              <input type="radio" name="correct_tf" value="True" class="form-check-input" <?= (strcasecmp($answers[0]['answer_text'], 'True') === 0 && $answers[0]['is_correct']) || (strcasecmp($answers[1]['answer_text'], 'True') === 0 && $answers[1]['is_correct']) ? 'checked' : '' ?>>
              <label class="form-check-label">True</label>
            </div>
            <div class="form-check">
              <input type="radio" name="correct_tf" value="False" class="form-check-input" <?= (strcasecmp($answers[0]['answer_text'], 'False') === 0 && $answers[0]['is_correct']) || (strcasecmp($answers[1]['answer_text'], 'False') === 0 && $answers[1]['is_correct']) ? 'checked' : '' ?>>
              <label class="form-check-label">False</label>
            </div>
          </div>
        <?php else: ?>
          <p>No True/False answers available for this question type.</p>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn btn-primary w-100">Save Changes</button>
      <a href="view_questions.php?quiz_id=<?= $quiz_id ?>" class="btn btn-secondary w-100 mt-2">Cancel</a>
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
