<?php
class Tag {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllTags() {
        $stmt = $this->pdo->query("SELECT * FROM tags");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTag($name) {
        $stmt = $this->pdo->prepare("INSERT INTO tags (name) VALUES (:name)");
        $stmt->execute([':name' => $name]);
        return $this->pdo->lastInsertId();
    }
}
?>
