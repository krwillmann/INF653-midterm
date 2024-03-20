<?php
// Set CORS headers for cross-origin requests and specify content type as JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
// Capture the HTTP method of the request
$method = $_SERVER['REQUEST_METHOD'];

// Handle preflight requests for CORS
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit(); // Stop script execution for OPTIONS request
}

// Include necessary files for database access and quote model
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Initialize a Database object and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize a Quote object with the database connection
$quote = new Quote($db);

// Route the request based on the HTTP method
switch ($method) {
    case 'GET':
        // Check if an ID parameter is provided for fetching a specific quote
        $quote_id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($quote_id !== null) {
            // If an ID is provided, include the script to read a single quote
            include_once 'read_single.php';
        } else {
            // If no ID is provided, include the script to read all quotes
            include_once 'read.php';
        }
        break;
    case 'POST':
        // Include the script to handle quote creation
        include_once 'create.php';
        break;
    case 'PUT':
        // Include the script to handle quote update
        include_once 'update.php';
        break;
    case 'DELETE':
        // Include the script to handle quote deletion
        include_once 'delete.php';
        break;
    default:
        // Respond with a 405 HTTP status code for unsupported methods
        http_response_code(405); // Method Not Allowed
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
