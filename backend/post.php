<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["message" => "Database connection failed"]);
  exit();
}

$communityId = intval($_GET['communityId'] ?? 0);

$postSql = "
  SELECT 
    post.id,
    post.content,
    post.createdAt,
    user.username,
    post.authorId
  FROM post
  JOIN user ON post.authorId = user.id
  WHERE post.communityId = $communityId
  ORDER BY post.createdAt DESC
";

$postResult = $conn->query($postSql);
$posts = [];

while ($post = $postResult->fetch_assoc()) {
  $postId = $post['id'];

  // Fetch comments
  $commentSql = "
      SELECT 
        comment.id,
        comment.authorId, 
        comment.content, 
        comment.createdAt, 
        user.username AS author,
        comment.updatedAt
      FROM comment
      JOIN user ON comment.authorId = user.id
      WHERE comment.postId = $postId
      ORDER BY comment.createdAt ASC
    ";

  $commentResult = $conn->query($commentSql);
  $comments = [];

  if ($commentResult) {
    while ($comment = $commentResult->fetch_assoc()) {
      $comments[] = $comment;
    }
  }


  // Fetch reaction counts
  $reactionSql = "
      SELECT reaction, COUNT(*) AS count
      FROM reaction
      WHERE postId = $postId
      GROUP BY reaction
    ";

  $reactionResult = $conn->query($reactionSql);
  $reactions = [];


  if ($reactionResult) {
    while ($reaction = $reactionResult->fetch_assoc()) {
      $reactions[$reaction['reaction']] = (int) $reaction['count'];
    }
  }

  $post['comments'] = $comments;
  $post['reactions'] = $reactions;

  $posts[] = $post;
}

echo json_encode($posts);
$conn->close();
?>