<?php
include 'db_connection.php';
$conn = OpenCon();
GetData($conn);
CloseCon($conn);
