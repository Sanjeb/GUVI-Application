<?php
$conn = new mysqli("localhost", "root", "1234", "internship");

if ($conn->connect_error) {
    die("Database connection failed");
}
?>