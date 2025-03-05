// assets/js/timer.js

let timeLeft = 15;
let timerDisplay = document.getElementById('timer');
let answerForm = document.getElementById('answerForm');

let countdown = setInterval(function() {
  timerDisplay.textContent = 'Time Remaining: ' + timeLeft + 's';
  timeLeft--;
  if (timeLeft < 0) {
    clearInterval(countdown);
    answerForm.submit();
  }
}, 1000);
