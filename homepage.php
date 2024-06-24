<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "twitter";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the searched username
    $search_user = $_POST["search_user"];

    header("Location: homepage.php?search=$search_user");
    exit();
}

$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$sql_tweets = "SELECT TWEETS.tweet_content, TWEETS.creation_date, USERS.username, USERS.user_id
               FROM TWEETS
               INNER JOIN FOLLOW ON TWEETS.user_id = FOLLOW.followed_id
               INNER JOIN USERS ON TWEETS.user_id = USERS.user_id
               WHERE FOLLOW.follower_id = '$user_id'
               ORDER BY TWEETS.creation_date DESC";
$result_tweets = $conn->query($sql_tweets);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Homepage</title>
</head>

<body>
    <h2>Welcome to the Twitter, <?php echo $_SESSION['username']; ?>!</h2>

    <h3>Search for a user to follow:</h3>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="search_user" placeholder="Enter username">
        <input type="submit" value="Search">
    </form>

    <?php
    if (!empty($search_query)) {
        $sql_search = "SELECT * FROM USERS WHERE username = '$search_query'";
        $result_search = $conn->query($sql_search);

        if ($result_search->num_rows > 0) {
            $row_search = $result_search->fetch_assoc();
            $searched_user_id = $row_search['user_id'];

            $sql_check_follow = "SELECT * FROM FOLLOW WHERE follower_id = '$user_id' AND followed_id = '$searched_user_id'";
            $result_check_follow = $conn->query($sql_check_follow);

            if ($result_check_follow->num_rows == 0) {

                echo "<p>User found: " . $row_search['username'] . "</p>";
                echo "<form method='post' action='follow.php'>";
                echo "<input type='hidden' name='followed_id' value='" . $row_search['user_id'] . "'>";
                echo "<input type='submit' value='Follow'>";
                echo "</form>";
            } else {
                echo "<p>You are already following this user.</p>";
            }
        } else {
            echo "<p>User not found.</p>";
        }
    }
    ?>

    <h3>Tweets from followed users:</h3>
    <?php
    if ($result_tweets->num_rows > 0) {
        while ($row = $result_tweets->fetch_assoc()) {
            $tweet_content = $row['tweet_content'];
            $creation_date = $row['creation_date'];
            $username = $row['username'];
            echo "<p><strong>$username:</strong> $tweet_content <em>($creation_date)</em></p>";
        }
    } else {
        echo "<p>No tweets to display.</p>";
    }
    ?>

    <br>

    <a href="profile.php">View Profile</a> | <a href="logout.php">Logout</a>
</body>

</html>