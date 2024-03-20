<?php
// Set CORS headers to allow cross-origin requests
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database and model files
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate a Database object and establish a connection
$database = new Database();
$db = $database->connect();

// Create an Author object
$author = new Author($db);

// Retrieve the raw POSTed data
$data = json_decode(file_get_contents("php://input"));

// Validate the presence of the author's ID in the POSTed data
if (!empty($data->id)) {
    // Assign the ID to the author object
    $author->id = $data->id;

    // Attempt to delete the author using the provided ID
    if ($author->delete()) {
        // Respond with the ID of the deleted author on success
        echo json_encode(array('id' => $author->id));
    } else {
        // Respond with a failure message if the delete operation failed
        echo json_encode(array('message' => 'Author Not Deleted'));
    }
} else {
    // Respond with an error message if the ID is missing from the POSTed data
    echo json_encode(['message' => 'Missing Required Parameter: id']);
}