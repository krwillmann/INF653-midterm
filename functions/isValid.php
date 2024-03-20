<?php
// Include the necessary configuration and model files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';
include_once '../../models/Category.php'; 
include_once '../../models/Author.php';

/**
 * Checks if a record exists for a given ID in the specified model.
 *
 * @param mixed $id The ID to check for existence.
 * @param object $model An instance of a model that has a read_single method.
 * @return bool True if the record exists, false otherwise.
 */
function isValid($id, $model) 
{
    // Assign the ID to the model's ID property
    $model->id = $id;
    
    // Attempt to find the record using the model's read_single method
    $result = $model->read_single();
    
    // Assume read_single returns null if no record is found, and return a boolean accordingly
    return $result !== null;
}
