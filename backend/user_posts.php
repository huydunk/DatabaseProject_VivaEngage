<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$userId = intval($_GET['userId'] ?? 0);


$result = $conn->query("SELECT id, content, createdAt FROM post WHERE authorId = $userId ORDER BY createdAt DESC");

$posts = [];
while ($row = $result->fetch_assoc()) {
  $posts[] = $row;
}

echo json_encode($posts);
$conn->close();
?>
