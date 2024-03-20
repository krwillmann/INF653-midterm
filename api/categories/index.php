<?php
// Set Cross-Origin Resource Sharing (CORS) headers for wide accessibility
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
// Determine the HTTP method of the request
$method = $_SERVER['REQUEST_METHOD'];

// Handle preflight requests for CORS
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit(); // Stop further execution for OPTIONS request
}

// Include necessary files for database access and the category model
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Initialize the Database object and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize a Category object with the database connection
$category = new Category($db);

// Route the request based on the HTTP method
switch ($method) {
    case 'GET':
        // Determine if an ID parameter is provided in the request
        $category_id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($category_id !== null) {
            // Handle request for a single category by including the read_single script
            include_once 'read_single.php';
        } else {
            // Handle request for all categories by including the read script
            include_once 'read.php';
        }
        break;
    case 'POST':
        // Handle create category request by including the create script
        include_once 'create.php';
        break;
    case 'PUT':
        // Handle update category request by including the update script
        include_once 'update.php';
        break;
    case 'DELETE':
        // Handle delete category request by including the delete script
        include_once 'delete.php';
        break;
    default:
        // Respond with a 405 HTTP status code for unsupported methods
        http_response_code(405); // Method Not Allowed
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
