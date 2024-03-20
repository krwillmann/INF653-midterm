<?php
// Enable CORS, set content type to JSON, and specify allowed methods and headers for creating an author
//header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');
//header('Access-Control-Allow-Methods: POST');
//header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database and model files
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Initialize a database connection
$database = new Database();
$db = $database->connect();

// Create an instance of the Author class
$author = new Author($db);

// Retrieve and decode the JSON body from the request
$data = json_decode(file_get_contents("php://input"));

// Validate the input data for the 'author' field
if (!empty($data->author)) {
    // Set the author property to the value provided
    $author->author = $data->author;

    // Attempt to create a new author in the database
    $new_author_id = $author->create();

    if ($new_author_id) {
        // If author creation is successful, return the new author's details
        $author_data = [
            'id' => $new_author_id,
            'author' => $data->author
        ];

        echo json_encode($author_data);
    } else {
        // If author creation fails, return an error message
        echo json_encode(['message' => 'Author Not Created']);
    }
} else {
    // If required parameters are missing, return an error message
    echo json_encode(['message' => 'Missing Required Parameters']);
}