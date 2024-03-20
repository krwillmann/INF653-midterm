<?php
class Category {
    // Database connection and table name
    private $conn;
    private $table = 'categories';

    // Category properties
    public $id;
    public $category;

    // Constructor with database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all categories from the database
    public function read() {
        $query = 'SELECT id, category FROM ' . $this->table . ' ORDER BY category ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read a single category by ID
    public function read_single() {
        $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->category = $row['category'];
            return true;
        } else {
            return false;
        }
    }

    // Create a new category
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category)';
        $stmt = $this->conn->prepare($query);
        $this->category = htmlspecialchars(strip_tags($this->category));
        $stmt->bindParam(':category', $this->category);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Update an existing category
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Delete a category by ID
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
}
