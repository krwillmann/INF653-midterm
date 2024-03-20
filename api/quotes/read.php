<?php
// Set Cross-Origin Resource Sharing (CORS) headers for wide accessibility and specify the content type as JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include necessary configuration and model files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';
include_once '../../models/Author.php';
include_once '../../models/Category.php';

// Initialize a Database object and connect to the database
$database = new Database();
$db = $database->connect();

// Initialize a Quote object with the database connection
$quote = new Quote($db);
$author = new Author($db); // Initialize Author object
$category = new Category($db);

$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// If author_id is provided, check for its existence
if ($author_id !== null) {
    $query = "SELECT COUNT(*) FROM authors WHERE id = :author_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':author_id', $author_id);
    $stmt->execute();
    $authorExists = $stmt->fetchColumn() > 0;

    if (!$authorExists) {
        echo json_encode(array('message' => 'author_id Not Found'));
        exit;
    }
}

// Check if category_id is provided and exists
if ($category_id !== null) {
    $query = "SELECT COUNT(*) FROM categories WHERE id = :category_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();
    $categoryExists = $stmt->fetchColumn() > 0;

    if (!$categoryExists) {
        echo json_encode(array('message' => 'category_id Not Found'));
        exit;
    }
}
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
            'id' => $row['id'],
            'quote' => $row['quote'],
            'author_id' => $row['author_id'],
            'category_id' => $row['category_id'] 
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