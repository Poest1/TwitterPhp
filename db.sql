-- Create the 'twitter' database
CREATE DATABASE twitter;

-- Switch to the 'twitter' database
USE twitter;

-- Create the 'USERS' table
CREATE TABLE USERS (
user_id INT PRIMARY KEY AUTO_INCREMENT,
username VARCHAR(50) NOT NULL,
email VARCHAR(100) NOT NULL,
password VARCHAR(100) NOT NULL
);

-- Create the 'TWEETS' table
CREATE TABLE TWEETS (
tweet_id INT PRIMARY KEY AUTO_INCREMENT,
user_id INT,
tweet_content VARCHAR(280) NOT NULL,
creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES USERS(user_id)
);

-- Create the 'FOLLOW' table
CREATE TABLE FOLLOW (
follower_id INT,
followed_id INT,
FOREIGN KEY (follower_id) REFERENCES USERS(user_id),
FOREIGN KEY (followed_id) REFERENCES USERS(user_id),
PRIMARY KEY (follower_id, followed_id)
);