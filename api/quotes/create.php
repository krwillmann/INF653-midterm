<?php
// Set CORS headers for wide accessibility and specify the content type as JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include necessary configuration and model files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Initialize a Database object and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize a Quote object with the database connection
$quote = new Quote($db);

// Decode the JSON body from the request
$data = json_decode(file_get_contents("php://input"));

// Validate the necessary data is provided: quote, author_id, and category_id
if (!empty($data->quote) && isset($data->author_id) && isset($data->category_id)) {
    // Verify the existence of the author and category by their IDs
    $authorExists = $quote->authorExists($data->author_id);
    $categoryExists = $quote->categoryExists($data->category_id);

    // Respond and stop the script if the author or category does not exist
    if (!$authorExists) {
        echo json_encode(array('message' => 'author_id Not Found'));
        return;
    }
    if (!$categoryExists) {
        echo json_encode(array('message' => 'category_id Not Found'));
        return;
    }

    // Assign the provided data to the Quote object
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Attempt to create the quote
    if ($quote->create()) {
        // Prepare and output the newly created quote details
        $newQuoteData = [
            'id' => $db->lastInsertId(),
            'quote' => $data->quote,
            'author_id' => $data->author_id,
            'category_id' => $data->category_id
        ];
        echo json_encode($newQuoteData);
    } else {
        // Respond with an error message if the quote creation failed
        echo json_encode(['message' => 'Quote Not Created']);
    }
} else {
    // Respond with an error message if required parameters are missing
    echo json_encode(['message' => 'Missing Required Parameters']);
}
