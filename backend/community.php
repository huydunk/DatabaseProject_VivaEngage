<?php
require_once '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$communityId = intval($_GET['communityId'] ?? 0);


if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "DB error"]);
    exit();
}

// Join user table to get creator's username
$sql = "
  SELECT 
    community.name,
    community.description,
    community.createdAt,
    user.name AS createdByName
  FROM community
  JOIN user ON community.createdBy = user.id
  WHERE community.id = $communityId
";

$result = $conn->query($sql);
$community = $result ? $result->fetch_assoc() : null;

echo json_encode($community);
$conn->close();
?>
