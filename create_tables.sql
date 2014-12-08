CREATE TABLE users (
    user_uid VARCHAR(255) PRIMARY KEY,
    user_email TEXT
);

CREATE TABLE waiting (
    waiting_id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255),
    other_email VARCHAR(255),
    consent BOOLEAN,
    rejected BOOLEAN
);

CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    user_uid VARCHAR(255),
    event_name TEXT,
    starttime TIMESTAMP,
    endtime TIMESTAMP,
    FOREIGN KEY (user_uid) REFERENCES users(user_uid)
);
