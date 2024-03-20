<?php
// Set Cross-Origin Resource Sharing (CORS) headers for wide accessibility and specify the content type as JSON
//header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');

// Include necessary configuration and model files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Initialize a Database object and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize a Quote object with the database connection
$quote = new Quote($db);

// Retrieve the quote ID from the URL parameter 'id'
$quote->id = isset($_GET['id']) ? $_GET['id'] : die("Quote ID not provided.");

// Attempt to retrieve the details of the quote using the provided ID
$quote->read_single();

// Check if the quote details were successfully retrieved
if (!empty($quote->quote)) {
    // Prepare the quote details as an array
    $quote_arr = [
        'id' => (int)$quote->id,
        'quote' => $quote->quote,
        'author' => $quote->author_name,
        'category' => $quote->category_name
    ];

    // Output the quote details as JSON
    echo json_encode($quote_arr);
} else {
    // Respond with a message if no quote was found with the provided ID
    echo json_encode(['message' => 'No Quotes Found']);
}
