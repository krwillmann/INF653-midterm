<?php 

class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    public $author_name;
    public $category_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetches all quotes from the database
    public function read() {
        $query = "SELECT 
                    a.author AS author_name, 
                    c.category AS category_name, 
                    q.id, q.quote, q.author_id, q.category_id
                  FROM {$this->table} q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN categories c ON q.category_id = c.id
                  ORDER BY q.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Fetches a single quote by ID
    public function read_single() {
        $query = "SELECT 
                    a.author as author_name, 
                    c.category as category_name, 
                    q.id, q.quote, q.author_id, q.category_id
                  FROM {$this->table} q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN categories c ON q.category_id = c.id
                  WHERE q.id = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->setProperties($row);
        } else {
            $this->resetProperties();
        }
    }

    // Inserts a new quote into the database
    public function create() {
        $query = 'INSERT INTO' . $this->table . '(quote, author_id, category_id) 
                  VALUES (:quote, :author_id, :category_id)';

        $stmt = $this->conn->prepare($query);
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Updates an existing quote in the database
    public function update() {
        if (!$this->quoteExists()) return 'no_quote_found';
        if (!$this->authorExists($this->author_id)) return 'author_id Not Found';
        if (!$this->categoryExists($this->category_id)) return 'category_id Not Found';

        $query =  'UPDATE' . $this->table . ' 
                  SET quote = :quote, author_id = :author_id, category_id = :category_id 
                  WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute() ? 'updated' : 'update_failed';
    }

    // Deletes a quote from the database
    public function delete() {
        if (!$this->quoteExists()) return false;

        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    private function sanitizeInput() {
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));
    }

    private function setProperties($row) {
        $this->quote = $row['quote'];
        $this->author_id = $row['author_id'];
        $this->category_id = $row['category_id'];
        $this->author_name = $row['author_name'];
        $this->category_name = $row['category_name'];
    }

    private function resetProperties() {
        $this->quote = null;
        $this->author_id = null;
        $this->category_id = null;
        $this->author_name = null;
        $this->category_name = null;
    }

    public function quoteExists() {
        $query = "SELECT id FROM {$this->table} WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function authorExists($authorId) {
        $query = 'SELECT COUNT(*) FROM authors WHERE id = :author_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author_id', $authorId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function categoryExists($categoryId) {
        $query = 'SELECT COUNT(*) FROM categories WHERE id = :category_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}

