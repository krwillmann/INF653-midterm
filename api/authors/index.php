<?php
// Set CORS headers to allow all origins and set content type to JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
// Get the HTTP method used for the request
$method = $_SERVER['REQUEST_METHOD'];

// Handle preflight requests for CORS
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit(); // Stop the script after sending headers for OPTIONS request
}

// Include necessary files for database and model
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Initialize database connection
$database = new Database();
$db = $database->connect();

// Initialize an Author object
$author = new Author($db);

// Route the request based on the HTTP method
switch ($method) {
    case 'GET':
        // Determine if an ID parameter is provided
        $author_id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($author_id !== null) {
            // Handle request for a single author by ID
            include_once 'read_single.php';
        } else {
            // Handle request for all authors
            include_once 'read.php';
        }
        break;
    case 'POST':
        // Handle request to create a new author
        include_once 'create.php';
        break;
    case 'PUT':
        // Handle request to update an existing author
        include_once 'update.php';
        break;
    case 'DELETE':
        // Handle request to delete an author
        include_once 'delete.php';
        break;
    default:
        // Respond with a 405 HTTP status code for unsupported methods
        http_response_code(405); // Method Not Allowed
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
