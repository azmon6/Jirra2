<?php
include_once "helperfunctions.php";
if (!isset($_COOKIE["user"])) {
    redirect("login.php");
}
?>

<head>
    <title>Jirra2</title>
    <link rel="stylesheet" href="main.css">
</head> 

<body>
    <div>
        <?php
        include_once "navbar.php";
        echo createHeader();
        ?>
    </div>
    <div >
        <div class = "mainContainer" id="projectsDash">
            <h1>Your projects:</h1>
            <?php
                include_once "db_connection.php";
                $conn = OpenCon();
                $sql = "SELECT UserID from user WHERE Username = \"".$_COOKIE["user"]."\"";
                $result = mysqli_query($conn, $sql);
                $userID = 0;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $userID = $row["UserID"];
                    }
                }
                $sql2 = "SELECT proekt.ProjectID,proekt.ProjectName,proekt.Description,proekt.DateCreated,proekt.DateStarted,proekt.DateEnded,proekt.Status
                FROM `user` as potrebitel
                JOIN usertoproject as vruzka ON potrebitel.UserID = vruzka.UserID
                JOIN project as proekt ON proekt.ProjectID = vruzka.ProjectID
                WHERE potrebitel.UserID = ".$userID;
                $result = mysqli_query($conn, $sql2);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class = \"projectPanel\">
                                <div class =\"titleheader\">
                                    <a href=\"projectdashboard.php?id=".$row["ProjectID"]."\">".$row["ProjectName"]. "</a>
                                </div>
                                <div class = \"description\">
                                    Description:<p>".htmlspecialchars($row["Description"], ENT_QUOTES)."</p>
                                </div>
                                <div class = \"projectDate\">
                                    Date Created:".$row["DateCreated"].
                                "</div>
                                <div class = \"projectStatus\">
                                    Project Status: ".getStatusFromInt($row["Status"]).
                                "</div>
                                <a class=\"button\" href=\"editproject.php?id=".$row["ProjectID"]."\">Edit</a>
                                <a class=\"button\" href=\"projectdashboard.php?id=".$row["ProjectID"]."\">Open Project</a>
                            </div>
                        ";
                    }
                } else {
                    echo "<div>No projects for you.</div>";
                }
            ?>
            <div>
            <a class="button" href="editproject.php">Create Project</a>
            </div>
        </div>
    </div>
    <div class="footing">

    </div>
</body>