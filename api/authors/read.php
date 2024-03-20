<?php
// Set CORS headers to allow all origins and specify the content type as JSON for API responses
//header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');

// Include the necessary configuration and model files
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Initialize the Database object and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize an Author object with the database connection
$author = new Author($db);

// Execute the read method to retrieve all authors
$result = $author->read();

// Count the number of rows returned
$num = $result->rowCount();

// Check if there are any authors in the database
if ($num > 0) {
    // Initialize an array to store authors' details
    $authors_arr = [];

    // Fetch each row and add it to the authors array
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row); // Converts column names into variables

        $author_item = [
            'id' => $id,
            'author' => $author
        ];

        // Add the author's details to the authors array
        array_push($authors_arr, $author_item);
    }

    // Encode the authors array as JSON and output it
    echo json_encode($authors_arr);
} else {
    // Output a message if no authors are found in the database
    echo json_encode(['message' => 'No Authors Found']);
}
