<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$userId = intval($_GET['userId'] ?? 0);
require_once '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


$result = $conn->query("SELECT id, content, createdAt FROM comment WHERE authorId = $userId ORDER BY createdAt DESC");

$comments = [];
while ($row = $result->fetch_assoc()) {
  $comments[] = $row;
}

echo json_encode($comments);
$conn->close();
?>
