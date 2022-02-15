<!DOCTYPE html>

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
        <form id="projectform" method="post" action="<?php echo basename($_SERVER['REQUEST_URI']); ?>">
            <input hidden type="number" name="projectID" value="<?php echo $id ?>">
            <label>Project Name:</label>
            <input type="text" name="projectName" placeholder="Project Name" value="<?php echo $projectName ?>">
            <label>Description </label>
            <textarea form="projectform" name="projectDesc" id="taid" cols="35" wrap="soft"><?php echo $projectDesc ?></textarea>
            <label>Status</label>
            <select name="projectStatus" id="cars">
                <option value="Upcoming">Upcoming</option>
                <option value="Pending">Pending</option>
                <option value="Overdue">Overdue</option>
                <option value="NotStarted">Not Started</option>
                <option value="Active">Active</option>
                <option value="Canceled">Canceled</option>
            </select>
            <div>
                Users:
                <select name="usersInProject[]" multiple>
                    <?php
                    $conn = OpenCon();
                    $sql = 'SELECT * from user';
                    $result = mysqli_query($conn, $sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value= "'.$row["Username"].'">
                                ' . $row["Username"] . '
                              </option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                Started/Ended
            </div>
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
    if ($_POST["projectID"] == 0) {
        $dateCreated = date("Y-m-d");
        $temp = getStatusFromString($_POST['projectStatus']);
        $projectName = $_POST["projectName"];
        $projectDesc = $_POST['projectDesc'];
        $sql = "INSERT INTO project (ProjectID,ProjectName,Description,DateCreated,DateStarted,DateEnded,Status) 
        VALUES (NULL,\"$projectName\",\"$projectDesc\",\"$dateCreated\",NULL,NULL,\"$temp\")";
        $conn->query($sql);
        $project_id = $conn->insert_id;
        $temp = $_COOKIE["user"];
        $userID = GetUserIDFromUsername($conn, $temp);
        $sql3 = "INSERT INTO usertoproject (ConnectionID,UserID, ProjectID) 
            VALUES (NULL,$userID,$project_id)";
        mysqli_query($conn, $sql3);
        if(isset($_POST["usersInProject"]))
        {
            foreach ($_POST['usersInProject'] as $subject)
            {
                echo $subject;
                $teammate = GetUserIDFromUsername($conn, $subject);
                $sql3 = "INSERT INTO usertoproject (ConnectionID,UserID, ProjectID) 
                         VALUES (NULL,$teammate,$project_id)";
                mysqli_query($conn, $sql3);
            }
        }
    } else {
        $projectID = $_POST["projectID"];
        $projectName = $_POST["projectName"];
        $projectDesc = $_POST['projectDesc'];
        $sql = "UPDATE project
        SET ProjectName = \"$projectName\", Description= \"$projectDesc\"
        WHERE ProjectID = $projectID ";
        $conn->query($sql);
    }
    redirect("dashboard.php");
    CloseCon($conn);
}
?>