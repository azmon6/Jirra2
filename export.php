<head>
    <title>Jirra2</title>
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <div>
        <?php
        include "navbar.php";
        echo createHeader();
        ?>
    </div>
    <div>
        <?php
        include_once "db_connection.php";
        $conn = OpenCon();
        $sql = "SELECT UserID from user WHERE Username = \"" . $_COOKIE["user"] . "\"";
        $result = mysqli_query($conn, $sql);
        $userID = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userID = $row["UserID"];
            }
        }
        $sql2 = "SELECT proekt.ProjectID,proekt.ProjectName
                FROM `user` as potrebitel
                JOIN usertoproject as vruzka ON potrebitel.UserID = vruzka.UserID
                JOIN project as proekt ON proekt.ProjectID = vruzka.ProjectID
                WHERE potrebitel.UserID = " . $userID;
        $result = mysqli_query($conn, $sql2);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div><a href="mindmap.php?id='.$row["ProjectID"].'"> '.$row["ProjectName"].' </a></div>';
            }
        }
        ?>
    </div>
    <div>

    </div>
</body>