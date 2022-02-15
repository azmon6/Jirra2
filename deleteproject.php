<?php

include_once "helperfunctions.php";
include_once "db_connection.php";

$id = 0;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}

$conn = OpenCon();

$sql = "DELETE FROM project WHERE ProjectID = $id";
mysqli_query($conn,$sql);

redirect("dashboard.php");
