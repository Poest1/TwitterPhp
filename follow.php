<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $follower_id = $_SESSION['user_id'];
    $followed_id = $_POST['followed_id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "twitter";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user is already following the selected user
    $sql_check_follow = "SELECT * FROM FOLLOW WHERE follower_id = '$follower_id' AND followed_id = '$followed_id'";
    $result_check_follow = $conn->query($sql_check_follow);

    if ($result_check_follow->num_rows == 0) {
        // Insert new follow relationship into the database
        $sql_insert_follow = "INSERT INTO FOLLOW (follower_id, followed_id) VALUES ('$follower_id', '$followed_id')";

        if ($conn->query($sql_insert_follow) === TRUE) {
            header("Location: homepage.php");
            exit();
        } else {
            echo "Error: " . $sql_insert_follow . "<br>" . $conn->error;
        }
    } else {
        // User is already following the selected user
        echo "You are already following this user.";
    }

    $conn->close();
}
