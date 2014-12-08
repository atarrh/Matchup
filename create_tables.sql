CREATE TABLE users (
    user_uid VARCHAR(255) PRIMARY KEY,
    user_email TEXT
);

CREATE TABLE waiting (
    waiting_id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255),
    other_email VARCHAR(255),
    request_date VARCHAR(255),
    request_length VARCHAR(255),
    accepted BOOLEAN,
    rejected BOOLEAN
);

CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255),
    event_name TEXT,
    starttime TIMESTAMP,
    endtime TIMESTAMP
);
