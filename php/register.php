<?php
require "db.php";

$name = $_POST["name"];
$email = $_POST["email"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $password);

try {
    $stmt->execute();
    echo "Registered successfully";
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        echo "Email already registered";
    } else {
        echo "Registration failed";
    }
}
?>
