<?php
// Set CORS headers to allow all origins and specify content type as JSON
//header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');

// Include necessary files for database access and the author model
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Initialize a new Database object and establish a database connection
$database = new Database();
$db = $database->connect();

// Initialize an Author object with the database connection
$author = new Author($db);

// Retrieve the author's ID from the URL parameter 'id'
$author->id = isset($_GET['id']) ? $_GET['id'] : die('Missing author ID');

// Attempt to retrieve the author's details using the provided ID
$authorFound = $author->read_single();

if ($authorFound) {
    // If an author is found, prepare and output their details as JSON
    $author_arr = [
        'id' => (int) $author->id,
        'author' => $author->author
    ];
    echo json_encode($author_arr);
} else {
    // If no author is found with the provided ID, output an error message
    echo json_encode(['message' => 'Author ID Not Found']);
}
