<?php
// Set Cross-Origin Resource Sharing (CORS) headers for wide accessibility and specify the content type as JSON
//header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');
//header('Access-Control-Allow-Methods: DELETE');
//header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include the necessary configuration and model files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Initialize a Database object and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize a Quote object with the database connection
$quote = new Quote($db);

// Retrieve and decode the JSON body from the request to get the quote ID
$data = json_decode(file_get_contents("php://input"));

// Validate that an ID has been provided in the request body
if (!empty($data->id)) {
    // Assign the provided ID to the Quote object
    $quote->id = $data->id;

    // Verify the existence of the quote before attempting deletion
    if ($quote->quoteExists()) {
        // Attempt to delete the quote
        if ($quote->delete()) {
            // Output the ID of the deleted quote
            echo json_encode(['id' => $quote->id]);
        } else {
            // Respond with a message if the quote deletion failed
            echo json_encode(['message' => 'Quote Not Deleted']);
        }
    } else {
        // Respond with a message if no quote with the provided ID exists
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    // Respond with a message if the ID is missing from the request body
    echo json_encode(['message' => 'Missing ID in Request Body']);
}
