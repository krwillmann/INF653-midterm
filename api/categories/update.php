<?php
// Set Cross-Origin Resource Sharing (CORS) headers for wide accessibility and specify the content type as JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include the necessary files for database access and the category model
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Initialize a Database object and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize a Category object with the database connection
$category = new Category($db);

// Retrieve and decode the JSON body from the request
$data = json_decode(file_get_contents("php://input"));

// Validate that the necessary data (ID and category name) is provided
if (!empty($data->id) && !empty($data->category)) {
    // Assign the provided data to the Category object
    $category->id = $data->id;
    $category->category = $data->category;

    // Attempt to update the category in the database
    if ($category->update()) {
        // Prepare a response array with the updated category details
        $response = [
            "id" => $category->id,
            "category" => $category->category
        ];

        // Output the response in JSON format
        echo json_encode($response);
    } else {
        // Output a message if the category update failed
        echo json_encode(['message' => 'Category Not Updated']);
    }
} else {
    // Output a message if required parameters are missing
    echo json_encode(['message' => 'Missing Required Parameters']);
}
