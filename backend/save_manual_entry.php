<?php
include '../config/conn.php'; // adjust path to your DB connection

$response = ["success" => false, "message" => ""];
if (!isset($_SESSION['User'])) {
    $response["message"] = "User not logged in.";
    echo json_encode($response);
    exit;
}
$user_id = $_SESSION["User"];
$sql = "SELECT * FROM users WHERE email = '$user_id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$user_id = $row['user_id'];

// Required fields
if (empty($_POST['bottleName']) || empty($_POST['bottleQuantity']) || empty($_POST['bottleBarcode'])) {
    $response["message"] = "All fields are required.";
    echo json_encode($response);
    exit;
}

$bottleName = mysqli_real_escape_string($conn, $_POST['bottleName']);
$bottleQuantity = mysqli_real_escape_string($conn, $_POST['bottleQuantity']);
$bottleBarcode = mysqli_real_escape_string($conn, $_POST['bottleBarcode']);

// Handle image
if (!isset($_FILES['bottleImage'])) {
    $response["message"] = "Bottle image is required.";
    echo json_encode($response);
    exit;
}

$image = $_FILES['bottleImage'];
if ($image['error'] !== UPLOAD_ERR_OK) {
    $response["message"] = "Upload failed.";
    echo json_encode($response);
    exit;
}

if ($image['size'] > 4 * 1024 * 1024) {
    $response["message"] = "Image must be less than 4MB.";
    echo json_encode($response);
    exit;
}

// Create upload folder if not exists
$uploadDir = __DIR__ . "./uploads/";

// Generate unique filename
$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
$filename = uniqid("bottle_", true) . "." . strtolower($ext);
$targetFile = $uploadDir . $filename;

if (move_uploaded_file($image['tmp_name'], $targetFile)) {
    $dbPath = "uploads/" . $filename;

    $sql = "INSERT INTO scans (bottle_name, quantity, bottle_code, image_path, scanned_at , status , points_earned, user_id) 
            VALUES ('$bottleName', '$bottleQuantity', '$bottleBarcode', '$dbPath', NOW() , 'Pending' , 0 , '$user_id')";

    if (mysqli_query($conn, $sql)) {
        $response["success"] = true;
        $response["message"] = "Saved successfully.";
    } else {
        $response["message"] = "DB Error: " . mysqli_error($conn);
    }
} else {
    $response["message"] = "Could not save image.";
}

echo json_encode($response);
