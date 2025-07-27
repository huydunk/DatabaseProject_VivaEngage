<?php
require_once '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$postId = intval($data["postId"] ?? 0);
$authorId = intval($data["authorId"] ?? 0);
$content = trim($data["content"] ?? "");

if (!$postId || !$authorId || !$content) {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields"]);
    exit();
}


if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "DB connection failed"]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO comment (postId, authorId, content, createdAt, updatedAt) VALUES (?, ?, ?, NOW(), NOW())");
$stmt->bind_param("iis", $postId, $authorId, $content);

if ($stmt->execute()) {
    echo json_encode(["message" => "Comment added"]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Failed to add comment"]);
}

$stmt->close();
$conn->close();
?>
