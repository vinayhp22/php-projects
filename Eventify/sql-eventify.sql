CREATE DATABASE eventify_db;
USE eventify_db;

-- Table for user authentication
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user'
);

-- Table for event categories
CREATE TABLE categories (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

-- Table for tags (you could also store tags in one field in the events table, but this is more normalized)
CREATE TABLE tags (
  tag_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

-- Junction table for many-to-many relationship between events and tags
CREATE TABLE event_tags (
  event_id INT NOT NULL,
  tag_id INT NOT NULL,
  PRIMARY KEY (event_id, tag_id)
);

-- Table for events
CREATE TABLE events (
  event_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  category_id INT NOT NULL,
  user_id INT NOT NULL,
  event_date DATETIME NOT NULL,
  location VARCHAR(255),
  max_attendees INT NOT NULL DEFAULT 50,
  FOREIGN KEY (category_id) REFERENCES categories(category_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Table for attendees
CREATE TABLE attendees (
  attendee_id INT AUTO_INCREMENT PRIMARY KEY,
  event_id INT NOT NULL,
  user_id INT NOT NULL,
  attendee_name VARCHAR(255) NOT NULL,
  attendee_email VARCHAR(255) NOT NULL,
  registered_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_waitlisted TINYINT NOT NULL DEFAULT 0,
  FOREIGN KEY (event_id) REFERENCES events(event_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Insert sample categories
INSERT INTO categories (name)
VALUES 
    ('Conference'), 
    ('Workshop'), 
    ('Meetup'),
    ('Webinar');

-- Insert sample tags
INSERT INTO tags (name)
VALUES 
    ('PHP'),
    ('JavaScript'),
    ('Design'),
    ('Security'),
    ('AI');

-- Insert sample events
INSERT INTO events (title, description, category_id, user_id, event_date, location, max_attendees)
VALUES
    (
        'PHP Conference 2023',
        'A conference about all things PHP.',
        1, -- Conference
        1,
        '2023-07-10 09:00:00',
        'New York City',
        200
    ),
    (
        'JavaScript Workshop',
        'A hands-on workshop focusing on modern JavaScript.',
        2, -- Workshop
        1,
        '2023-08-15 10:00:00',
        'San Francisco',
        100
    ),
    (
        'Design Meetup',
        'A meetup for UI/UX enthusiasts.',
        3, -- Meetup
        1,
        '2023-09-01 18:00:00',
        'Los Angeles',
        50
    ),
    (
        'AI Webinar',
        'Exploring the latest in AI technology.',
        4, -- Webinar
        1,
        '2023-10-05 11:00:00',
        'Online',
        500
    );

-- Insert sample event-tag relationships 
-- (Many-to-many via the event_tags junction table)
INSERT INTO event_tags (event_id, tag_id)
VALUES
    (1, 1), -- PHP Conference 2023 -> PHP
    (1, 5), -- PHP Conference 2023 -> AI (maybe it's also covering AI topics)
    (2, 2), -- JavaScript Workshop -> JavaScript
    (2, 4), -- JavaScript Workshop -> Security (for example, secure coding in JS)
    (3, 3), -- Design Meetup -> Design
    (4, 5); -- AI Webinar -> AI

-- Insert sample attendees
INSERT INTO attendees (event_id, attendee_name, attendee_email, is_waitlisted)
VALUES
    (1, 'Alice Smith', 'alice@example.com', 0),
    (1, 'Bob Jones', 'bob@example.com', 0),
    (2, 'Charlie Brown', 'charlie@example.com', 0),
    (2, 'Diana Prince', 'diana@example.com', 1), -- Suppose Diana is waitlisted
    (3, 'Eve Johnson', 'eve@example.com', 0),
    (3, 'Frank Wilson', 'frank@example.com', 0),
    (4, 'Grace White', 'grace@example.com', 0),
    (4, 'Heidi Black', 'heidi@example.com', 1); -- Suppose Heidi is waitlisted
