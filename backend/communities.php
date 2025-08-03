<?php
require_once '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed"]);
    exit();
}

$sort = $_GET['sort'] ?? 'created_desc'; // default
$orderClause = "";

switch ($sort) {
    case 'created_asc':
        $orderClause = "ORDER BY createdAt ASC";
        break;
    case 'created_desc':
        $orderClause = "ORDER BY createdAt DESC";
        break;
    case 'alpha_asc':
        $orderClause = "ORDER BY name ASC";
        break;
    case 'alpha_desc':
        $orderClause = "ORDER BY name DESC";
        break;
    case 'comments_desc':
        $orderClause = "ORDER BY totalComments DESC";
        break;
    case 'comments_asc':
        $orderClause = "ORDER BY totalComments ASC";
        break;
}


$sql = "
SELECT 
  c.*, 
  COALESCE(SUM(pc.commentCount), 0) AS totalComments
FROM community c
LEFT JOIN (
  SELECT post.communityId, COUNT(comment.id) AS commentCount
  FROM post
  LEFT JOIN comment ON comment.postId = post.id
  GROUP BY post.communityId
) pc ON c.id = pc.communityId
GROUP BY c.id
$orderClause
";

$result = $conn->query($sql);

$communities = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $communities[] = $row;
    }
    echo json_encode($communities);
} else {
    echo json_encode([]);
}

$conn->close();
?>