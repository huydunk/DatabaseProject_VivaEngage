<?php
require_once '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


// Handle CORS for browser fetch
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

// Preflight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Read JSON input
$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

// Safety check
if (!is_array($data) || !isset($data["username"])) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid input", "debug" => $rawInput]);
    exit();
}

$username = strtolower(trim($data["username"]));
$password = $data["password"] ?? "";

// Connect to MySQL

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed"]);
    exit();
}

// Query user by username (no password check yet)
$username_escaped = $conn->real_escape_string($username);
$sql = "SELECT * FROM user WHERE LOWER(username) = '$username_escaped'";
$result = $conn->query($sql);

// Response
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
      "message" => "Login successful",
      "userId" => $user["id"],
      "username" => $user["username"]
    ]);
} else {
    http_response_code(401);
    echo json_encode(["message" => "Invalid credentials"]);
}

$conn->close();
?>
