<?php
// Set Cross-Origin Resource Sharing (CORS) headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database and category model files
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Initialize and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize a Category object
$category = new Category($db);

// Retrieve and decode the JSON body from the request
$data = json_decode(file_get_contents("php://input"));

// Check if the category data is provided
if (!empty($data->category)) {
    // Assign the category name from the decoded data
    $category->category = $data->category;

    // Attempt to create a new category in the database
    $new_category_id = $category->create();

    if ($new_category_id) {
        // If the category was successfully created, prepare and output its details
        $category_data = [
            'id' => $new_category_id,
            'category' => $data->category
        ];

        echo json_encode($category_data);
    } else {
        // Output a message if the category creation failed
        echo json_encode(['message' => 'Category Not Created']);
    }
} else {
    // Output a message if required data is missing
    echo json_encode(['message' => 'Missing Required Parameters']);
}
