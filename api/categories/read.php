<?php
// Set Cross-Origin Resource Sharing (CORS) headers for wide accessibility and specify the content type as JSON
//header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');

// Include necessary configuration and model files for database access and category operations
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Initialize a Database object and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize a Category object with the database connection
$category = new Category($db);

// Execute the read method to retrieve all categories
$result = $category->read();

// Count the number of rows returned to determine if any categories are present
$num = $result->rowCount();

// Check if there are any categories found
if ($num > 0) {
    // Initialize an array to store category details
    $categories_arr = [];

    // Fetch each row and populate the category details array
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row); // Converts column names into variables

        $category_item = [
            'id' => $id,
            'category' => $category
        ];

        // Add the category's details to the array
        array_push($categories_arr, $category_item);
    }

    // Encode the categories array as JSON and output it
    echo json_encode($categories_arr);
} else {
    // Output a message if no categories are found in the database
    echo json_encode(['message' => 'No Categories Found']);
}
