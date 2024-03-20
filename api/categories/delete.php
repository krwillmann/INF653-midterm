<?php
// Set Cross-Origin Resource Sharing (CORS) headers for wide accessibility
//header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');
//header('Access-Control-Allow-Methods: DELETE');
//header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include the necessary files for database access and the category model
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Initialize the Database object and establish a connection
$database = new Database();
$db = $database->connect();

// Initialize a Category object
$category = new Category($db);

// Decode the JSON body from the request to get the category ID
$data = json_decode(file_get_contents("php://input"));

// Validate that an ID has been provided
if (!empty($data->id)) {
    $category->id = $data->id; // Assign the category ID for deletion

    // Attempt to delete the category
    if ($category->delete()) {
        // Output the ID of the deleted category
        echo json_encode(['id' => $category->id]);
    } else {
        // Output a message if deletion was unsuccessful
        echo json_encode(['message' => 'Category Not Deleted']);
    }
} else {
    // Output a message if the category ID was missing from the request
    echo json_encode(['message' => 'Missing Required Parameter: id']);
    die();
}
