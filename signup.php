<head>
    <title>Jirra2</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="login.css">
</head>

<?php
include_once "helperfunctions.php";
include_once "db_connection.php";
$usernameErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["username"])) {
        $usernameErr = "Enter Username";
    }

    if (empty($_POST["password"]) or empty($_POST["passwordConfirm"])) {
        $passwordErr = "Enter Password";
    }

    if ($usernameErr == "") {
        $conn = OpenCon();
        $sql = 'SELECT * FROM user WHERE Username = "' . $_POST["username"].'"';
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
            $usernameErr = "Username already exists.";
        }
        CloseCon($conn);
    }
}
?>

<body>
    <div>

    </div>
    <div class="loginPanel">
        <form method="post">
            <label>Username:</label>
            <input type="text" name="username" placeholder="Username" />
            <label>Password:</label>
            <input type="password" name="password" placeholder="Password" />
            <label>Confirm Password: </label>
            <input type="password" name="passwordConfirm" placeholder="Confrim Password" />
            <input type="submit" name="submit" value="Submit">
        </form>
        <a href="login.php">Login</a>
        <?php
        if ($usernameErr != "") {
            echo '<p class="error">*' . $usernameErr . '</p>';
        }
        ?>
    </div>
    <div>

    </div>
</body>

<?php
if (isset($_POST['submit'])) {
    if ($usernameErr == "" and $passwordErr == "") {
        $pass = hash("md5", $_POST["password"]);
        $conn = OpenCon();
        $sql = "INSERT INTO user (UserID, Username, Password) Values (NULL, \"" . $_POST["username"] . "\",\"" . $pass . "\")";
        $conn->query($sql);
        CloseCon($conn);
        $cookie_name = "user";
        setcookie($cookie_name, $_POST["username"], time() + 86400, "/");
        redirect("dashboard.php");
    }
}
?>