<?php
// Set Cross-Origin Resource Sharing (CORS) headers for wide accessibility and specify the content type as JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
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

// Validate that all required parameters are provided
if (!empty($data->id) && !empty($data->quote) && isset($data->author_id) && isset($data->category_id)) {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
    exit;
}

// Get ID and other fields of Quote to update
$quote->id = $data->id;
$quote->quote = $data->quote;
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;

// Check if quote id exists
if (!$quote->quoteExists()) {
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
    exit;
}

// Check if author_id exists
if (!$quote->authorExists($quote->author_id)) {
    echo json_encode(
        array('message' => 'author_id Not Found')
    );
    exit;
}

// Check if category_id exists
if (!$quote->categoryExists($quote->category_id)) {
    echo json_encode(
        array('message' => 'category_id Not Found')
    );
    exit;
}

// update Quote
if ($quote->update()) {
    echo json_encode(
        array('id' => $quote->id, 
        'quote' => $quote->quote,
        'author_id' => $quote->author_id,
        'category_id' => $quote->category_id)
    );
} else {
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}

