# BookHaven - Bookstore Inventory System

BookHaven is a PHP-based bookstore inventory system designed to manage a bookstore's inventory and streamline operations. It includes robust features such as inventory management, advanced search, secure user authentication with role-based access, barcode scanning integration, and detailed reporting.

## Features

- **Inventory Management:**  
  - Add, edit, and delete books (admin only)
  - Maintain book details including title, author, genre, publication year, stock, and barcode

- **Book Borrowing:**  
  - Users can borrow available books
  - Admins can borrow books on behalf of existing users (dropdown list of registered users). If no users exist, a prompt is shown to register users first

- **Receive Returned Books:**  
  - Admins can mark borrowed books as received, updating the borrowing record and incrementing the stock

- **User Management:**  
  - **Registration:** Both users and admins can register. For admin registration, an OTP (default: `112233`) is required  
  - **Login/Logout:** Secure authentication with password hashing using PHP's `password_hash()` and `password_verify()`  
  - **Profile Editing:** Both admins and users can update their profile information  
  - **Manual User Addition:** Admins can manually add users through a dedicated page

- **Advanced Search:**  
  - Search books by title, author, genre, or publication year

- **Reporting:**  
  - Generate reports for low-stock books and borrowing history (last 30 days)

- **Barcode Scanning:**  
  - Integration with QuaggaJS allows barcode scanning for quick book entry

- **Responsive and Professional Design:**  
  - Modern layout using Bootstrap and custom CSS  
  - A sticky footer that remains at the bottom of the page

## Installation

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/yourusername/BookHaven.git
   ```

2. **Navigate to the Project Directory:**

   ```bash
   cd BookHaven
   ```

3. **Set Up the Environment:**

   - Ensure you have a local development environment installed (e.g., XAMPP, WAMP, or MAMP)
   - Verify that PHP and MySQL are properly configured on your system

4. **Database Setup:**

   - Create a new MySQL database named `bookhaven` (or update the database name in `config/config.php`)
   - **Important:** Before running the application, import the provided SQL query file (e.g., `bookhaven.sql`) located in the project. This file contains all the necessary table structures and sample data.

   ```sql
   -- Example:
   CREATE DATABASE bookhaven;
   USE bookhaven;
   -- Then run the SQL queries provided in the SQL file.
   ```

## Running the Application

1. **Start Your Local Server:**

   - Launch your local server (e.g., XAMPP/WAMP/MAMP) and ensure both Apache and MySQL are running.

2. **Place the Project in Your Document Root:**

   - Copy the project folder to your web server's document root (e.g., `C:\xampp\htdocs\BookHaven`).

3. **Access the Application:**

   Open your web browser and navigate to:

   ```
   http://localhost/BookHaven/public/index.php
   ```

4. **Testing Credentials:**

   For testing purposes, use the following credentials:

   - **Admin:**  
     - **Username:** `root`  
     - **Password:** `root`

   - **User:**  
     - **Username:** `vinayhp`  
     - **Password:** `vinayhp`

## Additional Notes

- **Security:**  
  - The application uses secure password hashing (`password_hash` and `password_verify`).  
  - For production, ensure to enable HTTPS and adjust PHP settings (e.g., secure session cookies, CSRF protection).

- **Barcode Scanning:**  
  - The barcode scanning feature uses QuaggaJS. Make sure the QuaggaJS library is available via CDN or included locally.

- **Customization:**  
  - Feel free to modify the code, styling, and features to match your specific requirements.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For any questions or suggestions, please contact [vinay@skyllx.com](mailto:vinay@skyllx.com) || [vinayhp.paramesh@gmail.com](mailto:vinayhp.paramesh@gmail.com).
