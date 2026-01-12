<?php
header("Content-Type: application/json");

// UPC API URL
$barcode = isset($_GET['barcode']) ? $_GET['barcode'] : '';
if (!$barcode) {
    echo json_encode(["status" => "error", "message" => "Barcode missing"]);
    exit;
}

// External UPC API endpoint
$apiUrl = "https://world.openfoodfacts.org/api/v0/product/$barcode.json";


// Fetch API data
if (!(file_get_contents($apiUrl))) {
    echo json_encode(["status" => "error", "message" => "UPC API unavailable"]);
    exit;
}
$response = file_get_contents($apiUrl);

$data = json_decode($response, true);

// If no product found
if (!isset($data['product'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Product not found",
        "name" => "Unknown Product",
        "barcode" => "${barcode}",
        "quantity" => "Unknown Quantity",
        "material" => "Unknown Material"
    ]);
    exit;
}

$product = $data['product'];


$result = [];
$result['barcode'] = $barcode;
$result['name'] = $product['product_name'] ?? "Unknown Product";
$result['brand'] = $product['brands'] ?? "Unknown Brand";
$result['category'] = $product['categories_tags'][0] ?? "unknown";

// Extract quantity from different possible keys
if (!empty($product['quantity'])) {
    $result['quantity'] = $product['quantity'];  // e.g., "500 ml"
} elseif (!empty($product['sizes'][0]['quantity'])) {
    $result['quantity'] = $product['sizes'][0]['quantity'];
} elseif (preg_match('/\d+\s?(ml|l|g|kg)/i', $result['name'], $matches)) {
    $result['quantity'] = $matches[0]; // Try to extract from name
} else {
    $result['quantity'] = "Unknown";
}

// ----------------------
// CONVERT QUANTITY & ADD quantitySum
// ----------------------
$result['quantitySum'] = 0; // Default value

if (preg_match('/(\d+(?:\.\d+)?)\s*ml/i', $result['quantity'], $matches)) {
    // Convert ml → liters
    $ml = floatval($matches[1]);
    $liters = $ml / 1000;
    $result['quantity'] = $liters . " L";
    $result['quantitySum'] = $liters;
} elseif (preg_match('/(\d+(?:\.\d+)?)\s*l/i', $result['quantity'], $matches)) {
    // Already liters
    $liters = floatval($matches[1]);
    $result['quantity'] = $liters . " L";
    $result['quantitySum'] = $liters;
} elseif (preg_match('/(\d+(?:\.\d+)?)\s*kg/i', $result['quantity'], $matches)) {
    // 1 kg = 1 liter (approx. for water-like density)
    $kg = floatval($matches[1]);
    $result['quantity'] = $kg . " kg";
    $result['quantitySum'] = $kg;
} elseif (preg_match('/(\d+(?:\.\d+)?)\s*g/i', $result['quantity'], $matches)) {
    // Convert grams to liters (assuming water-based, 1L = 1000g)
    $grams = floatval($matches[1]);
    $liters = $grams / 1000;
    $result['quantity'] = $grams . " g";
    $result['quantitySum'] = $liters;
}

// ----------------------
// MATERIAL DETECTION
// ----------------------
$keywords = strtolower($result['name'] . " " . implode(" ", $product['categories_tags'] ?? []));

// Detect plastic
if (strpos($keywords, 'plastic') !== false || strpos($keywords, 'pet') !== false || strpos($keywords, 'polyethylene') !== false) {
    $result['material'] = "plastic";
}
// Detect aluminum cans
elseif (strpos($keywords, 'can') !== false || strpos($keywords, 'aluminum') !== false) {
    $result['material'] = "can";
}
// Detect glass bottles
elseif (strpos($keywords, 'glass') !== false || strpos($keywords, 'jar') !== false) {
    $result['material'] = "glass";
} else {
    $result['material'] = "plastic";
}

echo json_encode([
    "status" => "success",
    "data" => $result
], JSON_PRETTY_PRINT);
?>
