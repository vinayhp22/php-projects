# BlogSphere

**BlogSphere** is a feature-rich blog system built using PHP, HTML, CSS, JavaScript, and MySQL. It includes a rich text editor, SEO-friendly URLs and meta tags, social media sharing, a featured posts section, user roles (Admin, Editor, Subscriber), and image upload & gallery support—all wrapped in a responsive design using Bootstrap.

---

## Features

- **Rich Text Editor:** Integrated TinyMCE for creating and editing posts.
- **SEO-Friendly URLs & Meta Tags:** Clean URLs with custom meta tags for improved search engine visibility.
- **Social Media Sharing:** Built-in sharing buttons for Facebook and Twitter.
- **Featured Posts:** Highlight important posts on the homepage.
- **User Roles & Authentication:** Secure registration and login using password hashing. Manage users as Admin, Editor, or Subscriber.
- **Image Upload & Gallery:** Upload images for posts and display them in a responsive gallery.
- **Responsive Design:** Layout built with Bootstrap for mobile-first responsiveness.

---

## Directory Structure

```
BlogSphere/
├── admin/                      # Admin area for managing posts, users, etc.
│   ├── create_post.php         # Page to create a new post (with image upload)
│   ├── edit_post.php           # Page to edit an existing post
│   ├── manage_posts.php        # Manage posts page (list, edit, delete posts)
│   ├── login.php               # Admin login page
│   ├── logout.php              # Admin logout script
│   └── register.php            # User registration page
├── assets/                     # Frontend assets
│   ├── css/
│   │   └── style.css           # Custom CSS (includes sticky footer & custom styles)
│   ├── js/
│   │   └── script.js           # Custom JavaScript (e.g., password toggle)
│   └── images/                 # Static images (if any)
├── includes/                   # PHP includes and helper files
│   ├── config.php              # Database configuration and session start
│   ├── functions.php           # Helper functions (e.g., slug generation, routing)
│   ├── header.php              # Common header (meta tags, navigation, TinyMCE setup)
│   └── footer.php              # Common footer (social sharing buttons)
├── public/                     # Public-facing pages
│   ├── .htaccess               # URL rewriting rules for SEO-friendly URLs
│   ├── index.php               # Home page (displays featured and latest posts)
│   ├── post.php                # Single post view page (with related posts and images)
│   └── gallery.php             # Image gallery page
├── uploads/                    # Directory for user-uploaded images
└── README.md                   # This documentation file
```

---

## Setup Instructions

### 1. Environment Setup

- **Install XAMPP (or a similar LAMP/WAMP/MAMP stack):**
  - Download and install [XAMPP](https://www.apachefriends.org/index.html).
  - Once installed, place the `BlogSphere` folder inside the `htdocs` directory (for XAMPP, usually `C:\xampp\htdocs` on Windows).

- **Start Services:**
  - Launch the XAMPP Control Panel and start **Apache** and **MySQL**.

### 2. PHP & MySQL Configuration

- **PHP:**  
  Ensure you have PHP 7.4 or later installed (XAMPP comes bundled with a compatible version).

- **MySQL:**  
  - Access phpMyAdmin by navigating to [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/).
  - Create a new database named **blogsphere_db**.
  
- **Run the SQL Queries:**
  - Before testing or running the application, run the provided SQL queries (schema) that are included in the project (in a file or documentation) to create the necessary tables (e.g., `users`, `posts`, `images`, etc.).  
  - You can run these queries in phpMyAdmin’s SQL tab.

- **Update `includes/config.php`:**
  - Ensure the database credentials match your local configuration.  
  - For example, the default settings might be:
    ```php
    <?php
    $host   = 'localhost';
    $user   = 'root';
    $pass   = ''; // Default XAMPP password is empty
    $dbname = 'blogsphere_db';
    $port   = 3307; // Or update to 3306 if your MySQL runs on that port
    $conn = new mysqli($host, $user, $pass, $dbname, $port);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    session_start();
    ?>
    ```

### 3. Apache Setup

- **Enable mod_rewrite:**
  - Ensure that Apache’s mod_rewrite is enabled (this is usually enabled by default in XAMPP).
  - The provided `.htaccess` file in the **public** folder handles SEO-friendly URLs.

### 4. TinyMCE API Key

- **Get an API Key:**
  - Sign up at [Tiny Cloud](https://www.tiny.cloud/) for a free API key.
  - Replace the placeholder API key in `includes/header.php` with your actual key.

### 5. Testing Credentials

For testing purposes, use the following credentials:

- **As Admin:**  
  - Username: **root**  
  - Password: **root**

- **As Subscriber:**  
  - Username: **vinayhp**  
  - Password: **vinayhp**

You can register these accounts via the **admin/register.php** page or pre-populate your database using the SQL queries provided.

### 6. Running the Application

- **Access the Public Site:**  
  Open your browser and navigate to [http://localhost/BlogSphere/public/index.php](http://localhost/BlogSphere/public/index.php).

- **Access the Admin Panel:**  
  Log in using the admin credentials and manage posts, users, etc.

---

## Customization

- **Styling:**  
  Modify **assets/css/style.css** for custom styles.
- **JavaScript:**  
  Enhance functionality in **assets/js/script.js** (e.g., password view toggle).
- **Functionality:**  
  Extend or modify PHP files in **admin/** and **public/** as needed.

---

## Additional Information

- **Security:**  
  For production, ensure all user inputs are validated and sanitized. Use prepared statements to prevent SQL injection, implement CSRF protection, and secure your sessions.
- **Responsive Design:**  
  The project uses Bootstrap for a mobile-first, responsive layout.
- **Contribution:**  
  Feel free to fork this project, make improvements, and share your updates.

---

## License

This project is open-source. (Include your preferred license information here, e.g., MIT License.)

---

## Contact

For issues, feature requests, or contributions, please contact [vinay@skyllx.com || vinayhp.paramesh@gmail.com].

---

Happy coding and enjoy building with BlogSphere!

