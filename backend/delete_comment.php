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

$commentId = intval($data["commentId"] ?? 0);
$authorId = intval($data["authorId"] ?? 0);

if (!$commentId || !$authorId) {
    http_response_code(400);
    echo json_encode(["message" => "Missing fields"]);
    exit();
}


if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Database error"]);
    exit();
}

// Only delete if the user is the author
$stmt = $conn->prepare("DELETE FROM comment WHERE id = ? AND authorId = ?");
$stmt->bind_param("ii", $commentId, $authorId);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(["message" => "Comment deleted"]);
} else {
    http_response_code(403);
    echo json_encode(["message" => "Not authorized or comment not found"]);
}

$stmt->close();
$conn->close();
?>
