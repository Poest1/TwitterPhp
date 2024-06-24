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

$sql_user = "SELECT username FROM USERS WHERE user_id = '$user_id'";
$result_user = $conn->query($sql_user);
$row_user = $result_user->fetch_assoc();
$username = $row_user['username'];

$sql_follow = "SELECT COUNT(DISTINCT follower_id) AS num_followers,
                    COUNT(DISTINCT followed_id) AS num_following
               FROM FOLLOW
               WHERE follower_id = '$user_id'";
$result_follow = $conn->query($sql_follow);
$row_follow = $result_follow->fetch_assoc();
$num_followers = $row_follow['num_followers'];
$num_following = $row_follow['num_following'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tweet_content = $_POST["tweet_content"];

    $sql_insert_tweet = "INSERT INTO TWEETS (user_id, tweet_content) VALUES ('$user_id', '$tweet_content')";
    $conn->query($sql_insert_tweet);
}

$sql_tweets = "SELECT tweet_content FROM TWEETS WHERE user_id = '$user_id' ORDER BY creation_date DESC";
$result_tweets = $conn->query($sql_tweets);

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Profile</title>
</head>

<body>
    <h2>Welcome, <?php echo $username; ?>!</h2>
    <p>Number of followers: <?php echo $num_followers; ?></p>
    <p>Number of following: <?php echo $num_following; ?></p>

    <h3>Your Tweets:</h3>
    <?php
    if ($result_tweets->num_rows > 0) {
        while ($row_tweet = $result_tweets->fetch_assoc()) {
            $tweet_content = $row_tweet['tweet_content'];
            echo "<p>$tweet_content</p>";
        }
    } else {
        echo "<p>No tweets to display.</p>";
    }
    ?>

    <h3>Post a New Tweet:</h3>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <textarea name="tweet_content" rows="4" cols="50" required></textarea><br><br>
        <input type="submit" value="Post Tweet">
    </form>

    <br>

    <a href="homepage.php">Back to Homepage</a> | <a href="logout.php">Logout</a>
</body>

</html>