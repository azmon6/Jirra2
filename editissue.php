<head>
    <title>Jirra2</title>
    <link rel="stylesheet" href="main.css">
</head>

<?php
include_once "db_connection.php";
include_once "helperfunctions.php";
$conn = OpenCon();
$id = 0;
$projectName = $issueName = $issueDesc = $DateCreated = $issuerName = "";
$issueStatus = 0;
$issuerID = 0;
$projectID = 0;
$hoursToSolve = 0;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
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
        $issuerName = $row["Username"];
        $issuerID = $row["IssuerID"];
        $projectID = $row["ProjectID"];
        $hoursToSolve = $row["HoursToSolve"];
        $issueStatus = $row["Status"];
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
    <div>
        <form id="issueform" method="post" action="<?php echo basename($_SERVER['REQUEST_URI']); ?>">
            <input hidden type="number" name="issueID" value="<?php echo $id ?>">
            <input hidden type="number" name="projectID" value="<?php echo $projectID ?>">
            <input hidden type="number" name="issuerID" value="<?php echo $issuerID ?>">
            <input hidden type="number" name="tempStatus" value="<?php echo $issueStatus ?>">
            <label>Project Name</label>
            <select name="projectName" value="<?php echo $projectName; ?>">
                <?php
                    $conn = OpenCon();
                    $issuerID = GetUserIDFromUsername($conn,$_COOKIE["user"]);
                    $sql = 'SELECT proekt.ProjectName
                            FROM usertoproject
                            JOIN project as proekt on usertoproject.ProjectID = proekt.ProjectID
                            WHERE usertoproject.UserID = '.$issuerID;
                    $result=mysqli_query($conn, $sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row["ProjectName"].'">'.$row["ProjectName"].'</option>';
                        }
                    }
                ?>
            </select>

            <label>Issue Name</label>
            <input type="text" name="issueName" value="<?php echo $issueName; ?>">

            <p><?php echo $issuerName ?: "None yet"; ?></p>

            <label>Description </label>
            <textarea form="issueform" name="issueDesc" id="taid" cols="35" wrap="soft"><?php echo $issueDesc ?></textarea>

            <label>Status </label>
            <select name="issueStatus" id="issueStatus">
                <option value="0,Open">Open</option>
                <option value="1,InProgress">In Progress</option>
                <option value="2,Done">Done</option>
                <option value="3,Rejected">Rejected</option>
                <option value="4,Reopen">Reopen</option>
                <option value="5,Closed">Closed</option>
                <option value="6,InReview">In Review</option>
            </select>

            <label><?php echo $DateCreated; ?></label>
            <label>Hours to Solve</label>
            <input type="number" name="hoursToSolve" value="<?php echo $hoursToSolve?>">
            <input type="submit" name="submit" value="<?php if ($id == 0) {
                                                            echo "Create";
                                                        } else {
                                                            echo "Edit";
                                                        } ?>">
        </form>
    </div>
    <div>

    </div>
</body>

<?php
if (isset($_POST['submit'])) {
    $conn = OpenCon();
    if ($_POST["issueID"] == 0) {
        $dateCreated = date("Y-m-d");
        $issueStatus = explode(",", $_POST["issueStatus"],2);
        $issueStatus = $issueStatus[0];
        $issueID = $_POST["issueID"];
        $projectID = GetProjecIDFromName($conn,$_POST["projectName"]);
        $issuerID = GetUserIDFromUsername($conn,$_COOKIE["user"]);
        $issueName = $_POST["issueName"];
        $issueDesc = $_POST["issueDesc"];
        $hoursToSolve = $_POST["hoursToSolve"];
        $sql = 'INSERT INTO issue (IssueID,ProjectID,IssuerID,Description,Title,Status,DateCreated,HoursToSolve) 
            VALUES (NULL,"'.$projectID.'","'.$issuerID.'","'.$issueDesc.'","'.$issueName.'","'.$issueStatus.'","'.$dateCreated.'","'.$hoursToSolve.'")';
        mysqli_query($conn, $sql);
    } else {
        $prevStatus = $_POST["tempStatus"];
        $issueStatus = explode(",", $_POST["issueStatus"],2);
        $issueStatus = $issueStatus[0];
        $issueID = $_POST["issueID"];
        $issueName = $_POST["issueName"];
        $issueDesc = $_POST["issueDesc"];
        $hoursToSolve = $_POST["hoursToSolve"];
        $sql = "UPDATE issue
            SET Status = \"$issueStatus\", Description= \"$issueDesc\", Title = \"$issueName\", HoursToSolve = \"$hoursToSolve\"
            WHERE IssueID = $issueID ";
        $conn->query($sql);
        if($prevStatus != $issueStatus)
        {
            $sql2 = 'INSERT INTO issuehistory (HistoryID,IssueID,UserID,FromStatus,ToStatus,DateMade)
                     VALUES (NULL,'.$issueID.','.$issuerID.','.$prevStatus.','.$issueStatus.',NULL)';
            mysqli_query($conn, $sql2);
        }
        redirect("issue.php?id=".$issueID);
    }
    CloseCon($conn);
    redirect("dashboard.php");
}
?>