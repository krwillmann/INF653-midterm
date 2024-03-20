<?php
// Set Cross-Origin Resource Sharing (CORS) headers for wide accessibility
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include the necessary configuration and model files
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Initialize the Database object and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize a Category object with the database connection
$category = new Category($db);

// Retrieve the category ID from the URL parameter 'id'
$category->id = isset($_GET['id']) ? $_GET['id'] : die('Category ID not provided');

// Attempt to retrieve the category's details using the provided ID
$categoryFound = $category->read_single();

if ($categoryFound) {
    // If a category is found, prepare and output its details as JSON
    $category_arr = [
        'id' => (int) $category->id,
        'category' => $category->category
    ];
    echo json_encode($category_arr);
} else {
    // Output a message if no category is found with the provided ID
    echo json_encode(['message' => 'Category ID Not Found']);
}
