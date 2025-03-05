# Quizify - Online Quiz System

Quizify is an online quiz system built with PHP, MySQL, and Bootstrap. It features a user-friendly interface with timed questions, randomized order, detailed quiz analytics, and a leaderboard. The system supports multiple quiz types including multiple-choice, true/false, and fill-in-the-blank questions.

## Features

- **User Registration & Login:** Users can register, log in, and take quizzes.
- **Admin Dashboard:** Admins can create quizzes, add/edit questions, import questions from Excel, and view detailed analytics.
- **Quiz Functionality:** Each quiz has a per-question timer, random question order, and comprehensive result views.
- **Leaderboard:** View top-scoring users.
- **Responsive Design:** Built using Bootstrap for a modern, mobile-friendly UI.
- **Excel Import:** Admins can bulk import questions using an Excel template.

## Project Structure

```
quizify/
├── admin/                   # Admin panel files (dashboard, add_quiz, add_question, analytics, etc.)
├── public/                  # Public pages (index.php, login.php, register.php, quiz.php, result.php, view_answers.php, retake_test.php)
├── assets/
│   ├── css/
│   │   └── style.css        # Custom CSS (including sticky footer styles)
│   └── js/                  # JavaScript files (e.g., timer.js)
├── config/
│   └── db.php               # Database configuration
├── sql/
│   └── quizify_schema.sql   # SQL schema to create database and sample data
└── README.md                # This file
```

## How to Run the Project

### Prerequisites

- **PHP 8.1+** (Project dependencies require PHP version >= 8.1.0)
- **MySQL/MariaDB**
- **Web Server:** Apache (e.g., via XAMPP) or any server that supports PHP
- **Composer:** For managing PHP dependencies (e.g., [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet))

### Installation Steps

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/yourusername/quizify.git
   cd quizify
   ```

2. **Set Up the Database:**
   - Create a new database (e.g., named `quizify`).
   - Import the SQL schema:
     ```bash
     mysql -u your_username -p quizify < sql/quizify_schema.sql
     ```

3. **Configure Database Connection:**
   - Open `config/db.php` and update the host, database name, username, and password as needed.

4. **Install Composer Dependencies:**
   ```bash
   composer install
   ```

5. **Run the Project:**
   - If using XAMPP, place the project in the `htdocs` folder.
   - Access the project at: [http://localhost/quizify/public/index.php](http://localhost/quizify/public/index.php)

## Testing Credentials

Use the following test accounts (as provided in the sample SQL):

### Admin Account
- **Username:** admin
- **Password:** admin123

### Regular User Account
- **Username:** testuser
- **Password:** user123

## Importing Questions from Excel

Admins can import questions using an Excel file with the following expected columns (Row 1 as headers):

| Column | Field Name         | Description                                               |
| ------ | ------------------ | --------------------------------------------------------- |
| A      | question_text      | The text of the question                                  |
| B      | question_type      | Type of question: `multiple_choice`, `true_false`, or `fill_blank` |
| C      | answers            | For multiple-choice/true-false: semicolon-separated options; use `(N/A)` for fill_blank |
| D      | correct_answer     | The correct answer (or reference answer for fill_blank)   |

A sample template (`sample_questions_format.xlsx`) is provided in the project for reference.

## Running a Quiz

- **Homepage:** Users see a list of available quizzes.
- **Quiz Flow:** Once logged in, users can start a quiz, which loads randomized questions with a timer for each.
- **Results:** After completing a quiz, users can view detailed results and compare their answers with the correct answers.
- **Retake Option:** Users have the option to retake a quiz, which resets their previous responses.

## Admin Features

- **Create Quiz:** Admins can create new quizzes.
- **Add/Edit Questions:** Admins can add questions (including options for different types) and edit existing ones.
- **Import Questions:** Bulk import questions using an Excel file.
- **Analytics:** View detailed analytics for each quiz (number of attempts, correct answers, etc.).

## License

This project is licensed under the MIT License.

## Acknowledgments

- [Bootstrap 5](https://getbootstrap.com/) for the frontend framework.
- [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) for Excel file processing.
- Inspiration from modern online quiz platforms.

For any questions or suggestions, please contact [vinay@skyllx.com](mailto:vinay@skyllx.com) || [vinayhp.paramesh@gmail.com](mailto:vinayhp.paramesh@gmail.com).