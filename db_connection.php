<?php
function OpenCon()
 {
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "pesho123";
 $db = "practice";
 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
 
 return $conn;
 }
 
function CloseCon($conn)
 {
 $conn -> close();
 }


 function GetUserIDFromUsername($conn, $name)
 {
  $sql2 = "SELECT UserID from user WHERE Username = \"".$name."\"";
  $result = mysqli_query($conn, $sql2);
  $userID = 0;
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $userID = $row["UserID"];
          return $userID;
      }
  } else {
      echo "Problem retrieving user from username";
  }
  return 0;
 }

 function GetProjecIDFromName($conn, $name)
 {
  $sql2 = "SELECT ProjectID from project WHERE ProjectName = \"".$name."\"";
  $result = mysqli_query($conn, $sql2) or die("could not find Username");
  $userID = 0;
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $userID = $row["ProjectID"];
          return $userID;
      }
  } else {
      echo "Problem retrieving projectID from projectname";
  }
  return 0;
 }
