<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$content = trim($data["content"] ?? "");
$authorId = intval($data["authorId"] ?? 0);
$communityId = intval($data["communityId"] ?? 0);

if (!$content || !$authorId || !$communityId) {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields"]);
    exit();
}

$conn = new mysqli("localhost", "root", "", "cos20031_project_w11update");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed"]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO post (content, authorId, communityId, createdAt) VALUES ( ?, ?, ?, NOW())");
$stmt->bind_param("sii", $content, $authorId, $communityId);

if ($stmt->execute()) {
    echo json_encode(["message" => "Post created successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Failed to insert post"]);
}

$stmt->close();
$conn->close();
?>
