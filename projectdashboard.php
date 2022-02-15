<head>
    <title>Jirra2</title>
    <link rel="stylesheet" href="main.css">
</head>
<?php
include_once "db_connection.php";
include_once "helperfunctions.php";
$conn = OpenCon();
$id = 0;
$projectName = "";
$projectDesc = "";
$projectStatus = 0;
$projectDateCreated = "";
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}
$sql = "SELECT * FROM project WHERE ProjectID = $id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projectName = $row["ProjectName"];
        $projectDesc = $row["Description"];
        $projectDateCreated = $row["DateCreated"];
        $projectStatus = $row["Status"];
    }
} else {
    redirect("editproject.php");
}
CloseCon($conn);
?>
<body>
    <div>
        <?php
        include_once "navbar.php";
        echo createHeader();
        ?>
    </div>
    <div>
        <div class = "mainContainerAlign">
            <div class="projectPanel">
                <div class="titleheader">
                    <h2><?php echo $projectName;?></h2>
                </div>
                <div class="description">
                    Description:<p><?php echo htmlspecialchars($projectDesc, ENT_QUOTES) ?></p>
                </div>
                <div class="projectUsers">
                    Users in project:
                    <?php
                        $conn = OpenCon();
                        $tempstring = "";
                        $sql = "SELECT * FROM user
                                WHERE EXISTS (SELECT * FROM usertoproject WHERE user.UserID = usertoproject.UserID AND usertoproject.ProjectID = $id)";
                        $result = mysqli_query($conn, $sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $tempstring = $tempstring . $row["Username"].',';
                            }
                        }
                        $tempstring = substr_replace($tempstring ,"", -1);
                        echo $tempstring;
                    ?>
                </div>
                <div class="projectDate">
                    DateCreated: <?php echo $projectDateCreated ?>
                </div>
                <div class="projectStatus">
                    Status : <?php echo $projectStatus ?>
                </div>
                <div>
                    <a class="button" href="<?php echo "editproject.php?id=" .$id ; ?>">Edit Project</a>
                </div>
            </div>
            <div>
                <a class="button" href="<?php echo "editissue.php" ?>">Create Issue</a>
            </div>
            <div class="issueBubble">
                Open Issues
                <?php
                $conn = OpenCon();
                $sql = "SELECT * FROM issue
                        WHERE ProjectID = $id AND Status = 0";
                $result = mysqli_query($conn, $sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class= "projectIssue">
                                <a class="issueRedirectButton" href="issue.php?id='.$row["IssueID"].'">'.$row["Title"].'</a>
                                </div>';
                    }
                } 
                ?>
            </div>
            <div class="issueBubble">
                All Issues
                <?php
                $conn = OpenCon();
                $sql = "SELECT * FROM issue
                        WHERE ProjectID = $id";
                $result = mysqli_query($conn, $sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class= "projectIssue">
                                    <a class="issueRedirectButton" href="issue.php?id='.$row["IssueID"].'">'.$row["Title"].'</a>
                                </div>';
                    }
                } 
                ?>
            </div>
        </div>
    </div>
    <div>

    </div>
</body>