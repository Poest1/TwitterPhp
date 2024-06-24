<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "twitter";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM USERS WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        header("Location: homepage.php");
        exit();
    } else {
        $error_message = "Invalid username or password";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>

    <?php if (isset($error_message)) {
        echo "<p>" . $error_message . "</p>";
    } ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>

    <br>

    <a href="register.php">Register</a>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <a href="create_tables.php">Go to Create Tables Page(FIRST)</a>
</body>

</html>