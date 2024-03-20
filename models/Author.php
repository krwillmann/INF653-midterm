<?php
class Author {
    // Database connection and table name
    private $conn;
    private $table = 'authors';

    // Author properties
    public $id;
    public $author;

    // Constructor with database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all authors from the database
    public function read() {
        $query = 'SELECT id, author FROM ' . $this->table . ' ORDER BY author ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read a single author by ID
    public function read_single() {
        $query = 'SELECT id, author FROM ' . $this->table . ' WHERE id = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->author = $row['author'];
            return true;
        } else {
            return false;
        }
    }

    // Create a new author
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET author = :author';
        $stmt = $this->conn->prepare($query);
        $this->author = htmlspecialchars(strip_tags($this->author));
        $stmt->bindParam(':author', $this->author);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Update an existing author
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Delete an author by ID
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
