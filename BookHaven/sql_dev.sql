create database bookhaven;

use bookhaven;

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(100),
    publication_year INT,
    stock INT DEFAULT 0,
    barcode VARCHAR(100)
);

CREATE TABLE borrowings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT,
    borrower VARCHAR(255),
    borrow_date DATE,
    return_date DATE DEFAULT NULL,
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- Insert sample data into books table
INSERT INTO books (title, author, genre, publication_year, stock, barcode) VALUES
('To Kill a Mockingbird', 'Harper Lee', 'Fiction', 1960, 5, '9780061120084'),
('1984', 'George Orwell', 'Dystopian', 1949, 8, '9780451524935'),
('The Great Gatsby', 'F. Scott Fitzgerald', 'Fiction', 1925, 4, '9780743273565'),
('Moby Dick', 'Herman Melville', 'Adventure', 1851, 2, '9781503280786'),
('Pride and Prejudice', 'Jane Austen', 'Romance', 1813, 7, '9781503290563'),
('The Catcher in the Rye', 'J.D. Salinger', 'Fiction', 1951, 6, '9780316769174'),
('The Hobbit', 'J.R.R. Tolkien', 'Fantasy', 1937, 10, '9780547928227'),
('Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 'Fantasy', 1997, 12, '9780590353427'),
('The Da Vinci Code', 'Dan Brown', 'Mystery', 2003, 9, '9780307474278'),
('The Alchemist', 'Paulo Coelho', 'Adventure', 1988, 3, '9780061122415'),
('Brave New World', 'Aldous Huxley', 'Science Fiction', 1932, 4, '9780060850524');

INSERT INTO books (title, author, genre, publication_year, stock, barcode) VALUES
('Midnight''s Children', 'Salman Rushdie', 'Historical Fiction', 1981, 5, '9780812976530'),
('The God of Small Things', 'Arundhati Roy', 'Fiction', 1997, 7, '9780812979650'),
('A Suitable Boy', 'Vikram Seth', 'Historical Fiction', 1993, 4, '9780143038412'),
('The White Tiger', 'Aravind Adiga', 'Fiction', 2008, 6, '9780099519741'),
('Train to Pakistan', 'Khushwant Singh', 'Historical Fiction', 1956, 3, '9780143038029');


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);
