# ContactFlow

ContactFlow is a PHP-based contact form solution with advanced features including file attachment support, an administrative dashboard to view and manage submissions, CSV export, and user authentication (login and registration).

## Features

- **Contact Form:**  
  A user-friendly contact form that allows visitors to send inquiries and attach files.
  
- **File Attachment Support:**  
  Supports uploading files with validations on allowed file types.
  
- **reCAPTCHA Integration:**  
  Integrates reCAPTCHA v2 to prevent spam submissions.
  
- **Dashboard:**  
  A secure dashboard for administrators to view, manage, and export submissions.
  
- **CSV Export:**  
  Ability to export all submissions to CSV format for easy reporting and analysis.
  
- **User Authentication:**  
  Includes registration and login functionality to secure the dashboard.  
  **Test Admin Credentials:**  
  - **Username:** `admin`  
  - **Password:** `admin123`

## Prerequisites

- **PHP:** Version 7.x or higher
- **MySQL:** For database storage
- **Web Server:** Apache or Nginx
- **Composer:** For dependency management

## Installation

1. **Clone the Repository:**

   ```bash
   git clone https://your-repo-url.git
   cd contactflow
   ```

2. **Install Dependencies:**

   Run Composer to install PHP dependencies:
   ```bash
   composer install
   ```

3. **Database Setup:**

   Create a new MySQL database (e.g., `contactflow`) and run the following SQL to create the necessary tables:

   ```sql
   CREATE TABLE submissions (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       email VARCHAR(255) NOT NULL,
       subject VARCHAR(255) NOT NULL,
       message TEXT NOT NULL,
       attachment VARCHAR(255) DEFAULT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(255) NOT NULL UNIQUE,
       password VARCHAR(255) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

4. **Configure Database Connection:**

   Update the `db.php` file with your MySQL database credentials.

5. **Configure reCAPTCHA:**

   - Replace the placeholder **Site Key** in `contact_form.php` with your actual reCAPTCHA v2 site key.
   - Replace the **Secret Key** in `process_contact.php` with your reCAPTCHA v2 secret key.

6. **Header & Footer:**

   All pages include a common `header.php` and `footer.php` file to ensure a consistent look and feel.

## Project Structure

```
/contactflow/
├── db.php                  # Database connection
├── header.php              # Common header for all pages
├── footer.php              # Common footer for all pages
├── index.php               # Landing page
├── contact_form.php        # Public contact form page
├── process_contact.php     # Form processing and submission handling
├── dashboard.php           # Admin dashboard for submissions
├── login.php               # Login page for authentication
├── register.php            # Registration page for new users
├── logout.php              # Logout functionality (destroys session)
├── export.php              # CSV export functionality for submissions
├── vendor/                 # Composer-managed dependencies
└── README.md               # Project documentation (this file)
```

## Usage

1. **Access the Contact Form:**  
   Visit `contact_form.php` to submit a message along with any file attachments.

2. **Administration:**  
   - Log in at `login.php` (use the test credentials: `admin` / `admin123` or register a new user).
   - Once logged in, access `dashboard.php` to view all submissions.
   - Use `export.php` from the dashboard to download submission data as a CSV file.

3. **Logout:**  
   Click the Logout link (found in the navigation bar) to end your session.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Notes

- Ensure proper permissions for the `uploads/` directory so that file attachments can be stored.
- For production, further secure your application by adding CSRF protection, input sanitization, and improved error handling.

Happy Coding!

---

Feel free to modify this README file to match any additional details or customizations specific to your project.