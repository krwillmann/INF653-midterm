<?php
// Set Cross-Origin Resource Sharing (CORS) headers for wide accessibility and specify the content type as JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include necessary configuration and model files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Initialize a Database object and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize a Quote object with the database connection
$quote = new Quote($db);

// Execute the read method to retrieve all quotes
$result = $quote->read();

// Count the number of rows returned to determine if any quotes are present
$num = $result->rowCount();

// Check if there are any quotes found
if ($num > 0) {
    // Initialize an array to store quotes' details
    $quotes_arr = [];

    // Fetch each row and populate the quotes details array
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row); // Converts column names into variables

        $quote_item = [
            'id' => $id,
            'quote' => $quote,
            'author' => $author,
            'category' => $category 
        ];

        // Add the quote's details to the array
        $quotes_arr[] = $quote_item;
    }

    // Encode the quotes array as JSON and output it
    echo json_encode($quotes_arr);
} else {
    // Output a message if no quotes are found in the database
    echo json_encode(['message' => 'No Quotes Found']);
}
