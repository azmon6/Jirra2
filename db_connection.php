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
   
function TestCon($conn)
{
    if (isset($_POST["submit"]))
    {
 
        $sql = "INSERT INTO test (q,w,e,r) VALUES (1,2,3,4)";
        mysqli_query($conn, $sql);
 
        echo "<p>Invoice has been added.</p>";
    }
}

function GetData($conn)
{
    $sql = "SELECT q,w,e,r FROM test";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()) {
        echo "<div>id: " . $row["q"]. " - Name: " . $row["w"]. " " . $row["e"]. "</div><br>";
      }
}

?>