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
// Get community info
$infoSql = "
  SELECT 
    community.name,
    community.description,
    community.coverPicUrl,
    community.createdAt,
    user.username AS createdBy
  FROM community
  JOIN user ON community.createdBy = user.id
  WHERE community.id = $communityId
";
$infoResult = $conn->query($infoSql);
$community = $infoResult->fetch_assoc();

// Get member count
$memberCountSql = "SELECT COUNT(*) AS memberCount FROM usercommunity WHERE communityId = $communityId";
$countResult = $conn->query($memberCountSql);
$community['memberCount'] = $countResult->fetch_assoc()['memberCount'] ?? 0;

// Get member list (with role and username)
$memberListSql = "
  SELECT u.id, u.username, uc.role
  FROM usercommunity uc
  JOIN user u ON uc.userId = u.id
  WHERE uc.communityId = $communityId
  ORDER BY uc.role = 'Admin' DESC, u.username
";

$memberListResult = $conn->query($memberListSql);
$members = [];

while ($row = $memberListResult->fetch_assoc()) {
  $members[] = $row;
}

$community['members'] = $members;

echo json_encode($community);
$conn->close();
?>