<?php
include_once "db_connection.php";
include_once "helperfunctions.php";
$conn = OpenCon();
$id = 0;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}
$issueID = 0;
if (isset($_GET['issue'])) {
    $issueID = intval($_GET['issue']);
}
if($issueID == 0 and $id == 0)
{
    redirect("dashboard.php");
}
$userID = GetUserIDFromUsername($conn,$_COOKIE["user"]);
$IssueName = $CommentUserName = $CommentText = $DateCreated = "";
$sql = "SELECT comment.CommentText, comment.DateCreated, prob.Title, potrebitel.Username, prob.IssueID
        FROM comment
        JOIN issue as prob on comment.IssueID = prob.IssueID
        JOIN user as potrebitel on potrebitel.UserID = prob.IssuerID
        WHERE CommentID = $id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $IssueName = $row["Title"];
        $CommentUserName = $row["Username"];
        $CommentText = $row["CommentText"];
        $DateCreated = $row["DateCreated"];
        $issueID = $row["IssueID"];
    }
}

if($id == 0)
{
    $sql = "SELECT *
        FROM issue
        WHERE IssueID = $issueID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $IssueName = $row["Title"];
        }
    }
}
CloseCon($conn);
?>
<div >
    <div class="editComment modal-content">
        <form id="commentform" method="post" action="<?php echo basename($_SERVER['REQUEST_URI']); ?>">
            <input hidden name="commentID" value="<?php echo $id?>">
            <input hidden name="issueID" value="<?php echo $issueID?>">
            <input hidden name="userID" value="<?php echo $userID?>">
            <div>
                <label>User:</label>
                <?php echo $CommentUserName ?>
            </div>
            <div>
                <label>DateCreated:</label>
                <?php echo $DateCreated ?>
            </div>
            <div>
                <label>Issue Name:</label>
                <?php echo $IssueName ?>
            </div>
            <div>
                <label>Text: </label>
                <textarea form="commentform" name="commentDesc" id="taid" cols="35" wrap="soft"><?php echo $CommentText ?></textarea>
            </div>
            <div>
                <input type="submit" name="submit" value="<?php if ($id == 0) {
                                                                echo "Create";
                                                            } else {
                                                                echo "Edit";
                                                            } ?>">
                <button onclick="removeComment()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php
if (isset($_POST['submit'])) {
    $conn = OpenCon();
    if ($_POST["commentID"] == 0) {
        $dateCreated = date("Y-m-d");
        $sql = 'INSERT INTO comment (CommentID,IssueID,CommentUserID,CommentText,DateCreated)
                VALUES (NULL,'.$_POST["issueID"].','.$_POST["userID"].',"'.$_POST["commentDesc"].'","'.$dateCreated.'")';
        mysqli_query($conn, $sql);
    } else {
        $sql = "UPDATE comment
                SET CommentText = \"".$_POST["commentDesc"]."\"
                WHERE CommentID = ".$_POST["commentID"]."";
        $conn->query($sql);
    }
    CloseCon($conn);
    redirect("dashboard.php");
}
?>