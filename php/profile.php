<?php
require "db.php";
require "redis.php";
require "mongo.php";

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

    $query = new MongoDB\Driver\Query(["user_id" => (int)$user_id]);
    $cursor = $manager->executeQuery("$db.$collection", $query);

    $doc = current($cursor->toArray());

    echo json_encode([
        "status" => "success",
        "data" => [
            "age" => $doc->age ?? "",
            "dob" => $doc->dob ?? "",
            "contact" => $doc->contact ?? ""
        ]
    ]);
}


// UPDATE PROFILE
if ($action === "update") {

    $age = $_POST["age"];
    $dob = $_POST["dob"];
    $contact = $_POST["contact"];

    $bulk = new MongoDB\Driver\BulkWrite;

    $bulk->update(
        ["user_id" => (int)$user_id],
        ['$set' => [
            "user_id" => (int)$user_id,
            "age" => $age,
            "dob" => $dob,
            "contact" => $contact
        ]],
        ['upsert' => true]
    );

    $manager->executeBulkWrite("$db.$collection", $bulk);

    echo json_encode([
        "status" => "success",
        "message" => "Profile updated"
    ]);
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

