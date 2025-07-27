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
$userId = intval($data["userId"] ?? 0);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed"]);
    exit();
}

// Only allow deletion if user is the author
$sql = "DELETE FROM post WHERE id = ? AND authorId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $postId, $userId);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(["message" => "Post deleted"]);
} else {
    http_response_code(403);
    echo json_encode(["message" => "Not authorized or post not found"]);
}

$stmt->close();
$conn->close();
?>
