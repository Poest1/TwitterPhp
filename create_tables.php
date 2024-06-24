<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "twitter";

// Create a connection to the MySQL server
$conn = new mysqli($servername, $username, $password);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the 'twitter' database
$sql = "CREATE DATABASE  twitter";
if ($conn->query($sql) === TRUE) {
    echo "Database 'twitter' created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Switch to the 'twitter' database
$conn->select_db($dbname);

// Execute the SQL code to create tables
$sql = "
CREATE TABLE  USERS (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL
);

CREATE TABLE  TWEETS (
  tweet_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  tweet_content VARCHAR(280) NOT NULL,
  creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES USERS(user_id)
);

CREATE TABLE  FOLLOW (
  follower_id INT,
  followed_id INT,
  FOREIGN KEY (follower_id) REFERENCES USERS(user_id),
  FOREIGN KEY (followed_id) REFERENCES USERS(user_id),
  PRIMARY KEY (follower_id, followed_id)
);
";

if ($conn->multi_query($sql) === TRUE) {
    echo "Tables created successfully<br>";
} else {
    echo "Error creating tables: " . $conn->error . "<br>";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Tables</title>
</head>

<body>
    <br>
    <a href="login.php">Go back to Login</a>
</body>

</html>