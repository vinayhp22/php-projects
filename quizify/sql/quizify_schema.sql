CREATE DATABASE IF NOT EXISTS quizify;
USE quizify;

/* USERS */
CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  role ENUM('admin','user') NOT NULL DEFAULT 'user'
);

/* QUIZZES */
CREATE TABLE IF NOT EXISTS quizzes (
  quiz_id INT AUTO_INCREMENT PRIMARY KEY,
  created_by INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_quizzes_users
      FOREIGN KEY (created_by) REFERENCES users(user_id)
      ON DELETE CASCADE
);


/* QUESTIONS */
CREATE TABLE IF NOT EXISTS questions (
  question_id INT AUTO_INCREMENT PRIMARY KEY,
  quiz_id INT NOT NULL,
  question_text TEXT NOT NULL,
  question_type ENUM('multiple_choice','true_false','fill_blank') NOT NULL DEFAULT 'multiple_choice',
  CONSTRAINT fk_quiz
    FOREIGN KEY (quiz_id) REFERENCES quizzes(quiz_id)
    ON DELETE CASCADE
);

/* ANSWERS */
CREATE TABLE IF NOT EXISTS answers (
  answer_id INT AUTO_INCREMENT PRIMARY KEY,
  question_id INT NOT NULL,
  answer_text TEXT NOT NULL,
  is_correct TINYINT(1) DEFAULT 0,
  CONSTRAINT fk_question
    FOREIGN KEY (question_id) REFERENCES questions(question_id)
    ON DELETE CASCADE
);

/* USER_ANSWERS (tracks each user’s chosen answer) */
CREATE TABLE IF NOT EXISTS user_answers (
  user_answer_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  quiz_id INT NOT NULL,
  question_id INT NOT NULL,
  answer_id INT,
  fill_blank_response TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_ua_user
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE,
  CONSTRAINT fk_ua_quiz
    FOREIGN KEY (quiz_id) REFERENCES quizzes(quiz_id)
    ON DELETE CASCADE,
  CONSTRAINT fk_ua_question
    FOREIGN KEY (question_id) REFERENCES questions(question_id)
    ON DELETE CASCADE
);

/* SAMPLE INSERTS FOR TESTING (Optional) */
INSERT INTO users (username, password_hash, email, role)
VALUES 
('admin', MD5('admin123'), 'admin@example.com', 'admin'),
('testuser', MD5('user123'), 'user@example.com', 'user');
