# VoteWave - Online Voting System

## Overview

VoteWave is an online voting system built using PHP, HTML, CSS, JavaScript, and MySQL. It provides a complete solution for conducting polls online with secure administration and voter functionalities. The system supports real-time poll result visualization, exporting results in CSV and PDF formats, and multi-user management with separate roles for administrators and voters.

## Features

### Admin Features
- **Secure Admin Login:** Admins can securely log in to access the management dashboard.
- **Poll Management:** Create, edit, and delete polls with multiple options.
- **Real-time Results:** Monitor poll results in real time using Chart.js.
- **User Management:** Add, edit, and remove both admin and voter accounts.
- **Export Functionality:** Export poll results to CSV and PDF formats.

### Voter Features
- **Secure Voter Login:** Voters can log in to participate in polls.
- **Poll Participation:** View available polls and cast votes.
- **Real-time Results:** View live updates of poll results.
- **Responsive Design:** The interface is designed to work seamlessly on desktops, tablets, and mobile devices.

## Installation

### Requirements
- PHP 7.x or higher
- MySQL
- A web server (e.g., Apache)
- [Optional] Composer (if additional libraries are needed, though this project can run without it)

### Setup Steps

1. **Download the Project:**
   - Clone the repository or download the project ZIP file and extract it into your web server's document root (e.g., `htdocs` or `www`).

2. **Create the Database:**
   - Open your MySQL client (phpMyAdmin, MySQL Workbench, or command line).
   - Create a new database (for example, named `votewave`).

3. **Run SQL Query File:**
   - Before running the project, import the `schema.sql` file (located in the project root) into your newly created database. This file will create all necessary tables and sample data.
   - Example (command line):
     ```bash
     mysql -u your_username -p votewave < schema.sql
     ```

4. **Configure the Project:**
   - Open `config.php` and update the database connection details (host, user, password, database name).

5. **Run the Project:**
   - Start your web server and navigate to the project URL (e.g., [http://localhost/votewave](http://localhost/votewave)).

## Testing Credentials

For testing purposes, use the following credentials:

- **Admins:**
  - Username: `admin` &nbsp;&nbsp;&nbsp;&nbsp; Password: `admin123`
  - Username: `root` &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Password: `root`

- **Voters:**
  - Username: `vinayhp` &nbsp;&nbsp;&nbsp; Password: `vinayhp`

## Running the Project

1. Ensure your MySQL database is set up and the `schema.sql` file has been run.
2. Update the database credentials in `config.php`.
3. Place the project files on your web server.
4. Navigate to the project URL in your web browser.
5. Use the provided testing credentials to log in as an admin or a voter.
6. Explore the various features, such as creating polls, casting votes, and viewing real-time results.

## Contributing

Contributions are welcome! If you'd like to contribute to VoteWave, please fork the repository and submit pull requests with your improvements or bug fixes.

## License

This project is licensed under the MIT License.
