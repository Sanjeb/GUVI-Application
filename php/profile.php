<?php
require "db.php";
require "redis.php";

$action = $_POST["action"];
$token = $_POST["token"];

// Check token in Redis
$user_id = $redis->get($token);

if (!$user_id) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid session"
    ]);
    exit;
}

// FETCH PROFILE
if ($action === "fetch") {

    $stmt = $conn->prepare("SELECT age, dob, contact FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);
}


// UPDATE PROFILE
if ($action === "update") {

    $age = $_POST["age"];
    $dob = $_POST["dob"];
    $contact = $_POST["contact"];

    $stmt = $conn->prepare("UPDATE users SET age = ?, dob = ?, contact = ? WHERE id = ?");
    $stmt->bind_param("issi", $age, $dob, $contact, $user_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Profile updated"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Update failed"
        ]);
    }
}

// LOGOUT
if ($action === "logout") {
    $redis->del($token);

    echo json_encode([
        "status" => "success",
        "message" => "Logged out"
    ]);
    exit;
}

?>

