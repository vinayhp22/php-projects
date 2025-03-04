<?php
class Event {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createEvent($title, $description, $category_id, $event_date, $location, $max_attendees, $tags = []) {
        $stmt = $this->pdo->prepare("
            INSERT INTO events (title, description, category_id, event_date, location, max_attendees)
            VALUES (:title, :description, :category_id, :event_date, :location, :max_attendees)
        ");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category_id' => $category_id,
            ':event_date' => $event_date,
            ':location' => $location,
            ':max_attendees' => $max_attendees
        ]);
        $event_id = $this->pdo->lastInsertId();

        // Insert tags
        foreach($tags as $tag_id) {
            $tagStmt = $this->pdo->prepare("
                INSERT INTO event_tags (event_id, tag_id) VALUES (:event_id, :tag_id)
            ");
            $tagStmt->execute([':event_id' => $event_id, ':tag_id' => $tag_id]);
        }

        return $event_id;
    }

    public function updateEvent($event_id, $title, $description, $category_id, $event_date, $location, $max_attendees, $tags = []) {
        $stmt = $this->pdo->prepare("
            UPDATE events 
            SET title = :title, description = :description, category_id = :category_id,
                event_date = :event_date, location = :location, max_attendees = :max_attendees
            WHERE event_id = :event_id
        ");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category_id' => $category_id,
            ':event_date' => $event_date,
            ':location' => $location,
            ':max_attendees' => $max_attendees,
            ':event_id' => $event_id
        ]);

        // Update tags
        $this->pdo->prepare("DELETE FROM event_tags WHERE event_id = :event_id")
                  ->execute([':event_id' => $event_id]);
        foreach($tags as $tag_id) {
            $tagStmt = $this->pdo->prepare("
                INSERT INTO event_tags (event_id, tag_id) VALUES (:event_id, :tag_id)
            ");
            $tagStmt->execute([':event_id' => $event_id, ':tag_id' => $tag_id]);
        }
        return true;
    }

    public function deleteEvent($event_id) {
        // Remove associated tags
        $this->pdo->prepare("DELETE FROM event_tags WHERE event_id = :event_id")
                  ->execute([':event_id' => $event_id]);
        // Remove associated attendees
        $this->pdo->prepare("DELETE FROM attendees WHERE event_id = :event_id")
                  ->execute([':event_id' => $event_id]);
        // Remove event
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE event_id = :event_id");
        return $stmt->execute([':event_id' => $event_id]);
    }

    public function getEventById($event_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE event_id = :event_id");
        $stmt->execute([':event_id' => $event_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEvents() {
        $stmt = $this->pdo->query("
            SELECT e.*, c.name as category_name
            FROM events e
            JOIN categories c ON e.category_id = c.category_id
            ORDER BY e.event_date ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventTags($event_id) {
        $stmt = $this->pdo->prepare("
            SELECT t.* 
            FROM tags t
            JOIN event_tags et ON t.tag_id = et.tag_id
            WHERE et.event_id = :event_id
        ");
        $stmt->execute([':event_id' => $event_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
