<?php
// Set CORS headers for cross-origin allowance and content type to JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include the necessary configuration and model files
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Initialize the Database object and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize an Author object with the database connection
$author = new Author($db);

// Retrieve and decode the JSON body from the PUT request
$data = json_decode(file_get_contents("php://input"));

// Validate that necessary data is provided (ID and author name)
if (!empty($data->id) && !empty($data->author)) {
    // Assign the provided ID and author name to the Author object
    $author->id = $data->id;
    $author->author = $data->author;

    // Attempt to update the author in the database
    if ($author->update()) {
        // Prepare a response array with the updated author details
        $response = [
            "id" => $author->id,
            "author" => $author->author
        ];

        // Output the response in JSON format
        echo json_encode($response);
    } else {
        // Respond with a message if the update operation fails
        echo json_encode(['message' => 'Author Not Updated']);
    }
} else {
    // Respond with a message if required parameters are missing
    echo json_encode(['message' => 'Missing Required Parameters']);
}
