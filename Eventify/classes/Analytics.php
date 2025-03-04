<?php
class Analytics {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Example: get number of attendees vs. capacity for each event
    public function getEventAttendanceData() {
        $stmt = $this->pdo->query("
            SELECT 
                e.event_id,
                e.title,
                e.max_attendees,
                COUNT(a.attendee_id) as total_registered,
                SUM(a.is_waitlisted) as total_waitlisted
            FROM events e
            LEFT JOIN attendees a ON e.event_id = a.event_id
            GROUP BY e.event_id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Example: compute an approximate attendance rate
    public function getAttendanceRate($event_id) {
        // total registered vs. capacity
        $stmt = $this->pdo->prepare("
            SELECT 
                e.max_attendees,
                COUNT(a.attendee_id) as total_registered
            FROM events e
            LEFT JOIN attendees a ON e.event_id = a.event_id
            WHERE e.event_id = :event_id
        ");
        $stmt->execute([':event_id' => $event_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row || $row['max_attendees'] == 0) return 0;

        $rate = ($row['total_registered'] / $row['max_attendees']) * 100;
        return number_format($rate, 2);
    }
}
?>
