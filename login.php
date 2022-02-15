<?php
include_once "helperfunctions.php";
delCookieValue("user");
setcookie("user", "", time() - 3600);
?>

<head>
        <title>Jirra2</title>
        <link rel="stylesheet" href="main.css">
        <link rel="stylesheet" href="login.css">
</head>

<?php
include_once "db_connection.php";
$usernameErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $noerror = true;
    if (empty($_POST["username"])) {
        $usernameErr = "Enter Username";
        $noerror = false;
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Enter Password";
        $noerror = false;
    }

    if($noerror)
    {
        $conn = OpenCon();
        $sql = 'SELECT * FROM user WHERE Username = "'.$_POST["username"].'" AND Password = "'.hash("md5",$_POST["password"]).'" ';
        $result = mysqli_query($conn,$sql);
        if ($result->num_rows == 0) {
            $usernameErr = "Invalid username or password";
        }
        CloseCon($conn);
    }

}
?>

<body>
        <div>

        </div>
        <div class="loginPanel">
                <div class = "login-header">
                    Log In
                </div>
                <form class="login-containter" method="post">
                        <label>Username:</label>
                        <input type="text" name="username" placeholder="Username" />
                        <label>Password:</label>
                        <input type="password" name="password" placeholder="Password" />
                        <input type="submit" name="submit" value="Submit">
                </form>
                <a href="signup.php">Register</a>
                <?php
                    if($usernameErr != "")
                    {
                        echo '<p class="error">*'.$usernameErr.'</p>';
                    }
                    if($passwordErr != "")
                    {
                        echo '<p class="error">*'.$passwordErr.'</p>';
                    }
                ?>
        </div>
        <div>

        </div>
</body>

<?php

if (isset($_POST['submit'])) {
    if ($usernameErr == "" and $passwordErr == "") {
        $cookie_name = "user";
        setcookie($cookie_name, $_POST["username"], time() + 86400, "/");
        redirect("dashboard.php");
    }
}
?>