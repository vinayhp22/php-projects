# LinkLynx - Advanced PHP URL Shortener

LinkLynx is an advanced URL shortening application built with PHP, MySQL, HTML, CSS, and Bootstrap. It offers features such as:

- **Custom Short URL Creation:** Create a unique alias or use an auto-generated code.
- **Click Analytics:** Track link visits with detailed analytics.
- **Link Expiration Date:** Set an expiration date for shortened URLs.
- **Password-Protected URLs:** Secure your links with a password.
- **Bulk URL Shortening:** Shorten multiple URLs at once.
- **QR Code Generation & Download:** Generate and download QR codes for your shortened links.
- **User Authentication:** Registration, login, and link management.

## Project Structure

```
linklynx/
├── config/
│   └── config.php           // Database configuration
├── css/
│   └── style.css            // Custom CSS and styling
├── includes/
│   ├── db.php               // Database connection using PDO
│   ├── functions.php        // Utility functions (short code generation, QR generation, etc.)
│   ├── auth.php             // Authentication helper functions
│   ├── header.php           // Shared header for all pages
│   └── footer.php           // Shared footer for all pages
├── public/
│   ├── index.php            // Home & URL shortening form
│   ├── shorten.php          // Processes single URL shortening
│   ├── redirect.php         // Redirects short URL to the original URL
│   ├── analytics.php        // Displays click analytics
│   ├── bulk_shorten.php     // Bulk URL shortening form and results
│   ├── register.php         // User registration page
│   ├── login.php            // User login page
│   ├── logout.php           // Logs out the user
│   ├── manage.php           // Manage, edit, and delete links (requires login)
│   ├── edit.php             // Edit a specific link (requires login and ownership)
│   ├── delete.php           // Delete a link (requires login and ownership)
│   └── download_qr.php      // Download the QR Code image (requires login and ownership)
├── vendor/
│   └── phpqrcode/           // PHP QR Code library (download and extract here)
│       ├── qrlib.php        // QR Code generation functions
│       └── qrcodes/         // Folder for generated QR Code images (ensure writable)
├── .htaccess                // Apache rewrite rules for clean URLs (optional)
└── schema.sql               // SQL script to create required database tables
```

## Setup Instructions

1. **Clone the Repository:**

   Clone or download the project files into your web server's root folder (for example, `C:\xampp\htdocs\linklynx` for XAMPP users).

2. **Database Setup:**

   - Create a MySQL database named `linklynx`.
   - Import the provided **schema.sql** file to create the necessary tables:
     ```sql
     CREATE DATABASE linklynx;
     USE linklynx;

     CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         username VARCHAR(50) NOT NULL UNIQUE,
         email VARCHAR(100) NOT NULL UNIQUE,
         password VARCHAR(255) NOT NULL,
         created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
     );

     CREATE TABLE urls (
         id INT AUTO_INCREMENT PRIMARY KEY,
         original_url TEXT NOT NULL,
         short_code VARCHAR(100) NOT NULL UNIQUE,
         expiration_date DATETIME NULL,
         password VARCHAR(255) NULL,
         user_id INT DEFAULT NULL,
         created_at DATETIME NOT NULL,
         click_count INT DEFAULT 0,
         FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
     );

     CREATE TABLE clicks (
         id INT AUTO_INCREMENT PRIMARY KEY,
         url_id INT NOT NULL,
         click_time DATETIME NOT NULL,
         ip_address VARCHAR(45) NOT NULL,
         FOREIGN KEY (url_id) REFERENCES urls(id) ON DELETE CASCADE
     );
     ```

3. **Configure Database Connection:**

   Update your **config/config.php** file with your MySQL credentials:
   ```php
   <?php
   // config/config.php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'linklynx');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ?>
   ```

4. **Download PHP QR Code Library:**

   - Download the [PHP QR Code library](http://phpqrcode.sourceforge.net/).
   - Extract it and place its files (including `qrlib.php`) into the `vendor/phpqrcode/` folder.
   - Ensure the `vendor/phpqrcode/qrcodes/` directory exists and is writable.

5. **Running the Application:**

   - If using XAMPP, start Apache and MySQL from the XAMPP Control Panel.
   - **Access the application at:**  
     `http://localhost/linklynx/public/index.php`  
     **Or, using your server's IP address:**  
     `http://<IP Address>/linklynx/public/index.php`  
     *(Replace `<IP Address>` with your actual server IP address.)*

6. **Getting the Server IP Address:**

   On Windows, open Command Prompt and run:
   ```bash
   ipconfig
   ```
   Look for the IPv4 address under your active network adapter. Use this IP address in the URL above (e.g., `http://192.168.1.100/linklynx/public/index.php`).

## Test Credentials

For testing purposes, you can use the following user accounts:

- **Email:** user@skyllx.com  
  **Password:** user123

- **Email:** user1@skyllx.com  
  **Password:** user123

## Additional Notes

- **Shared Layout:**  
  All pages include a shared header and footer for a consistent look and professional feel.
  
- **Responsive Design:**  
  The application uses Bootstrap to ensure that the pages are responsive and attractive on all devices.

- **Error Handling & Security:**  
  Basic error handling is implemented on all pages. For production, consider adding further security measures like input sanitization and HTTPS.

## License

This project is provided as-is without warranty. Feel free to customize and improve it as needed.

---

Happy coding!
```