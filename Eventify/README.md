# Eventify

**Eventify** is a simple PHP/MySQL-based **Event Management System**. It supports:

- Role-based authentication (Admin & User)  
- Event creation, editing, and deletion (limited to the Admin who created the event)  
- Attendee registration with waitlist management (if the event is full)  
- Event tags (including the ability to create new tags on the fly)  
- Event categories  
- Event analytics (attendance rate, capacity vs. registered)  
- “My Registrations” page for users to view their enrolled events  
- A polished Bootstrap-based user interface  

## Features

1. **Admin & User** Roles  
   - Admins can create and manage events they own, including capacity and waitlist.  
   - Only the admin who originally created the event can edit it or see its waitlist.  
   - Users can browse events and register for them (with automatic waitlisting if full).

2. **Tag Management**  
   - Events can have multiple tags.  
   - Admin can create a new tag either when creating or editing an event.

3. **Analytics**  
   - Admin can view overall attendance statistics, including total registered vs. waitlisted, and capacity usage.

4. **UI & UX**  
   - Uses [Bootstrap 5](https://getbootstrap.com/) for a clean, modern interface.  
   - Fixed navbar at the top, fixed footer at the bottom, and a responsive layout.

---

## Getting Started

### 1. Clone or Download the Repository

```bash
git clone https://github.com/YOUR_USERNAME/Eventify.git
cd Eventify
```

*(Or, if you prefer, download the ZIP and extract it.)*

### 2. Database Setup

1. Create a database (e.g. `eventify_db`) in MySQL.  
2. Import the **SQL export** file located at `Eventify/sql-export.sql`. This will create all necessary tables:
   ```sql
   -- For example, in a MySQL client:
   CREATE DATABASE eventify_db;
   USE eventify_db;
   SOURCE path/to/Eventify/sql-export.sql;
   ```

3. Adjust **`config/config.php`** to match your local or server credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'eventify_db');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('ADMIN_OTP', '112233'); // default OTP for admin registration
   ```

### 3. Configure Your Server

- Place the project in your web server root directory (e.g. `htdocs` in XAMPP or `www/html` in LAMP).  
- Make sure `public/` is accessible at a URL like `http://localhost/Eventify/public/`.  
- If using XAMPP, the path might be: `C:\xampp\htdocs\Eventify`.  
- Ensure **PHP PDO** extension is installed and enabled for MySQL (`pdo_mysql`).

### 4. Running the Application

- Go to `http://localhost/Eventify/public/` in your browser.  
- You should see the “All Events” page (or be redirected to the login if not logged in).

### 5. Testing Credentials

- **For admin** testing, you can register yourself as an admin by using the default OTP `112233` during registration. 
- Or, use the pre-created admin account:
  - **Email**: `admin@skyllx.com`
  - **Password**: `Admin`
- Or, use the pre-created user account:
  - **Email**: `user@skyllx.com`
  - **Password**: `user`
- For a **regular user**, just register without checking the “Register as Admin” box.

---

## Project Structure

A simplified folder structure:

```
Eventify/
├── config/
│   └── config.php
├── database/
│   └── db_connect.php
├── auth/
│   ├── login.php
│   └── register.php
├── includes/
│   ├── header.php
│   └── footer.php
├── classes/
│   ├── Auth.php
│   ├── Event.php
│   ├── Category.php
│   ├── Tag.php
│   └── Analytics.php
├── pages/
│   ├── event_list.php
│   ├── event_details.php
│   ├── event_create.php
│   ├── event_edit.php
│   ├── event_register.php
│   ├── waitlist.php
│   ├── my_registrations.php
│   └── analytics.php
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   └── styles.css
│   │   └── js/
│   │       └── scripts.js
│   ├── index.php
│   └── .htaccess
├── sql-export.sql
└── README.md
```

---

## Usage Overview

1. **Register** as a normal user or admin (using OTP `112233`).  
2. **Login** and explore.  
   - Admin can create a new event (Fill the form in “Create Event”).  
   - Other users can see events and click **“Register”**.  
3. If an event is full, the user is **waitlisted** automatically. Admin can view the **waitlist** link (if they own that event).  
4. Admin can view **Analytics** to see capacity usage, waitlist counts, etc.  
5. Users can see **My Registrations** to check events they’ve signed up for.

---

## Contributing

1. Fork the repo  
2. Create a feature branch: `git checkout -b feature/my-new-feature`  
3. Commit changes: `git commit -m "Add some feature"`  
4. Push to the branch: `git push origin feature/my-new-feature`  
5. Create a new pull request in GitHub

---

## License

This project is distributed under the MIT License. See `LICENSE` for details.

---

**Thank you** for using **Eventify**! If you have any questions or suggestions, feel free to open an issue on GitHub or contact the maintainers. Enjoy managing your events easily with **Eventify**!

For any questions or suggestions, please contact [vinay@skyllx.com](mailto:vinay@skyllx.com) || [vinayhp.paramesh@gmail.com](mailto:vinayhp.paramesh@gmail.com).