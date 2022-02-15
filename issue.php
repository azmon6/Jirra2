<head>
    <title>Jirra2</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="issue.css">
    <link rel="stylesheet" href="comment.css">
    <script type="text/javascript" src="comment.js"></script>
</head>

<?php
include_once "db_connection.php";
include_once "helperfunctions.php";
$conn = OpenCon();
$id = 0;
$projectName = $issueName = $issueDesc = $DateCreated = $issuerName = "";
$issuerID = 0;
$projectID = 0;
$hoursToSolve = 0;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}
else
{
    redirect("dashboard.php");
}
$sql = "SELECT prob.IssueID, prob.ProjectID, prob.IssuerID, prob.Description, prob.Title, prob.Status, prob.DateCreated, prob.HoursToSolve, proekt.ProjectName, potrebitel.Username
        FROM issue as prob
        JOIN user as potrebitel on prob.IssuerID = potrebitel.UserID
        JOIN project as proekt on proekt.ProjectID = prob.ProjectID
        WHERE prob.IssueID = $id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projectName = $row["ProjectName"];
        $issueName = $row["Title"];
        $issueDesc = $row["Description"];
        $DateCreated = $row["DateCreated"];
        $issueName = $row["Username"];
        $issuerID = $row["IssuerID"];
        $projectID = $row["ProjectID"];
        $hoursToSolve = $row["HoursToSolve"];
    }
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
    <div class="mainContainerIssue">
        <div class="sidepanelIssues">
            <h3>
                Project: <?php echo $projectName;?>
            </h3>
            <h3>
                Issues:
            </h3>
            <ul class="issueList">
                <?php
                    $conn = OpenCon();
                    $sql = 'SELECT issue.IssueID, issue.Title
                            FROM issue
                            JOIN project as proekt on issue.ProjectID = proekt.ProjectID
                            WHERE proekt.ProjectID = '.$projectID.' ';
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<li> '.$row["Title"].' </li>';
                        }
                    }
                    CloseCon($conn);
                ?>
            </ul>
        </div>
        <div class="mainContainerAlign">
            <div>
                Title
            </div>
            <div>
                Project Name
            </div>
            <div>
                Description
            </div>
            <div>
                <a class="editButton button" href="editissue.php?id=<?php echo $id; ?>">Edit</a>
            </div>
            <div>
                Comments
                <div>
                    <?php
                        echo '<button id="myBtn" onclick="getComment('."0".','.$id.')">Click me </button>';
                        echo '<div id="commentContainer"></div>';
                        $conn = OpenCon();
                        $sql = "SELECT comment.CommentText , potrebitel.Username 
                                FROM comment
                                JOIN user as potrebitel on comment.CommentUserID = potrebitel.UserID
                                WHERE IssueID = $id";
                        $result = mysqli_query($conn, $sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class= "comment">
                                    '.$row["Username"].':'.$row["CommentText"].'
                                    </div>';
                            }
                        }  
                    ?>
                </div>
            </div>
            <div>
                History
                <div>
                    <?php
                        $conn = OpenCon();
                        $sql = "SELECT issuehistory.FromStatus,issuehistory.ToStatus, issuehistory.DateMade, potrebitel.Username 
                                FROM issuehistory
                                JOIN user as potrebitel on issuehistory.UserID = potrebitel.UserID
                                WHERE IssueID = $id
                                ORDER BY issuehistory.DateMade DESC";
                        $result = mysqli_query($conn, $sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $fromStatus = getStatusFromInt($row["FromStatus"]);
                                $ToStatus = getStatusFromInt($row["ToStatus"]);
                                echo '<div>
                                        '.$row["Username"].' : Changed from "'.$fromStatus.'" to "'.$ToStatus.'" on '.$row["DateMade"].' .
                                    </div>';
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div>

    </div>
</body>