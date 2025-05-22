CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255)
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    event_date DATE,
    category VARCHAR(20) DEFAULT 'default',
    description TEXT,
    status ENUM('pending', 'done') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    task_date DATE,
    status ENUM('pending', 'done') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

