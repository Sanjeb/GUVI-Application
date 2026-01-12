<?php
require "db.php";
require "redis.php";

$email = $_POST["email"];
$password = $_POST["password"];

$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user["password"])) {

    // Generate token
    $token = bin2hex(random_bytes(16));

    // Store in Redis: token -> user_id
    $redis->setex($token, 3600, $user["id"]); // expires in 1 hour

    echo json_encode([
        "status" => "success",
        "token" => $token
    ]);

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid credentials"
    ]);
}
?>
