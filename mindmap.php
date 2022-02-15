<?php

include_once "db_connection.php";
include_once "helperfunctions.php";

function GenerateMM($projectID)
{
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=savethis.mm");

    $conn = OpenCon();
    print "<map version=\"1.0.1\">\n";
    GenerateProject($projectID,$conn);
    print "</map>";
    CloseCon($conn);
}

function GenerateProject($projectID, $conn)
{
    $sql = "SELECT *
            From project
            WHERE ProjectID = $projectID";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        while ($row = $result->fetch_assoc()) {
            print "<node TEXT=\"".$row["ProjectName"]."\"> \n";
            GenerateIssues($projectID,$conn);
            GenerateUsers($projectID,$conn);
            print "</node>\n";
        }
    }
    else
    {
        echo "Problem with getting Project.";
    }
}

function GenerateIssues($projectID, $conn)
{
    $sql = "SELECT issue.Title, issue.IssueID
            From issue
            JOIN project as proekt on issue.ProjectID = proekt.ProjectID
            WHERE proekt.ProjectID = $projectID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            print "<node POSITION=\"right\" TEXT=\"".$row["Title"]."\"> \n";
            GenerateComments($row["IssueID"],$conn);
            GenerateHistory($row["IssueID"],$conn);
            print "</node>\n";
        }
    }
}

function GenerateComments($issueID, $conn)
{
    $sql = "SELECT comment.CommentText
            From comment
            JOIN issue as problem on comment.IssueID = problem.IssueID
            WHERE problem.IssueID = $issueID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        print "<node TEXT=\"Comments\"> \n";
        while ($row = $result->fetch_assoc()) {
            print "<node TEXT=\"".$row["CommentText"]."\"> \n";
            
            print "</node>\n";
        }
        print "</node>\n";
    }
}

function GenerateHistory($issueID, $conn)
{
    $sql = "SELECT issuehistory.FromStatus,issuehistory.ToStatus, issuehistory.DateMade, potrebitel.Username 
                                FROM issuehistory
                                JOIN user as potrebitel on issuehistory.UserID = potrebitel.UserID
                                WHERE IssueID = $issueID
                                ORDER BY issuehistory.DateMade DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        print "<node TEXT=\"History\"> \n";
        while ($row = $result->fetch_assoc()) {
            $tempstring = $row["Username"].' : Changed from '.$row["FromStatus"].' to '.$row["ToStatus"].' on '.$row["DateMade"];
            print "<node TEXT=\"".$tempstring."\"> \n";
            
            print "</node>\n";
        }
        print "</node>\n";
    }
}

function GenerateUsers($projectID, $conn)
{
    $sql = "SELECT user.Username
            From user
            JOIN usertoproject as utp on user.UserID = utp.UserID
            JOIN project as proekt on utp.ProjectID = proekt.ProjectID
            WHERE proekt.ProjectID = $projectID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            print "<node POSITION=\"left\" TEXT=\"".$row["Username"]."\"> \n";
            print "</node>\n";
        }
    }
    else
    {
        echo "Problem with getting Users.";
    }
}
GenerateMM($_GET['id']);
