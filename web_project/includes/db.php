<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "discover_saudi";

$conn = new mysqli($host, $user, $pass, $dbname);


if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
?>