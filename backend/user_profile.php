<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$userId = intval($_GET['userId'] ?? 0);
require_once '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["message" => "DB connection failed"]);
  exit();
}

// Get basic user info
$userSql = "SELECT id, username, email, name, roleId, departmentId, profilePicUrl, bio, location, joinDate, status, isVerified, lastLogin, deactivated FROM user WHERE id = $userId";
$userResult = $conn->query($userSql);

if (!$userResult || $userResult->num_rows === 0) {
  http_response_code(404);
  echo json_encode(["message" => "User not found"]);
  exit();
}

$userData = $userResult->fetch_assoc();

// Get user posts
$postSql = "SELECT id, content, createdAt FROM post WHERE authorId = $userId ORDER BY createdAt DESC";
$postResult = $conn->query($postSql);
$posts = [];

if ($postResult) {
  while ($row = $postResult->fetch_assoc()) {
    $posts[] = $row;
  }
}
$userData['posts'] = $posts;

// Get user comments
$commentSql = "SELECT id, content, createdAt FROM comment WHERE authorId = $userId ORDER BY createdAt DESC";
$commentResult = $conn->query($commentSql);
$comments = [];

if ($commentResult) {
  while ($row = $commentResult->fetch_assoc()) {
    $comments[] = $row;
  }
}
$userData['comments'] = $comments;

// Return combined data
echo json_encode($userData);
$conn->close();
?>
