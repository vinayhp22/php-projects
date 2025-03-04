<?php
require_once '../database/db_connect.php';
require_once '../classes/Event.php';
require_once '../classes/Category.php';
require_once '../classes/Tag.php';
require_once '../classes/Analytics.php';

$eventObj = new Event($pdo);
$categoryObj = new Category($pdo);
$tagObj = new Tag($pdo);
$analyticsObj = new Analytics($pdo);

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

switch ($action) {
    case 'list':
        include '../pages/event_list.php';
        break;

    case 'details':
        include '../pages/event_details.php';
        break;

    case 'create':
        // Only admin can create events
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            die("Access denied. Admin only.");
        }
        include '../pages/event_create.php';
        break;

    case 'edit':
        session_start();
        // Check user is logged in and is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            die("Access denied. Admin only.");
        }
    
        if (!isset($_GET['event_id'])) {
            die("No event specified.");
        }
        
        // Fetch the event
        $event_id = $_GET['event_id'];
        $event = $eventObj->getEventById($event_id);
    
        // If the event doesn’t exist, or user_id doesn’t match the session user
        if (!$event || $event['user_id'] != $_SESSION['user_id']) {
            die("Access denied. You didn't create this event.");
        }
    
        // Now include the edit page logic
        include '../pages/event_edit.php';
        break;

    case 'delete':
        // Only admin can delete
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            die("Access denied. Admin only.");
        }
        if (isset($_GET['event_id'])) {
            $eventObj->deleteEvent($_GET['event_id']);
        }
        header("Location: index.php?action=list");
        exit;
        break;

    case 'register':
        session_start();
        if (!isset($_SESSION['user_id'])) {
            die("You must be logged in to register.");
        }
    
        // Make sure we have an event_id
        if (!isset($_GET['event_id'])) {
            die("No event specified.");
        }
        $event_id = $_GET['event_id'];
    
        // 1. Check if the user is already registered
        $checkStmt = $pdo->prepare("
            SELECT attendee_id 
            FROM attendees 
            WHERE event_id = :event_id AND user_id = :user_id
        ");
        $checkStmt->execute([
            ':event_id' => $event_id,
            ':user_id'  => $_SESSION['user_id']
        ]);
        $alreadyRegistered = $checkStmt->fetch();
    
        if ($alreadyRegistered) {
            // Already registered => redirect or show message
            echo "<p>You are already registered for this event.</p>";
            echo "<p><a href='index.php?action=details&event_id={$event_id}'>Back to Event</a></p>";
            exit;
        }
    
        // 2. Fetch event info to check capacity
        $eventStmt = $pdo->prepare("SELECT max_attendees FROM events WHERE event_id = :event_id");
        $eventStmt->execute([':event_id' => $event_id]);
        $event = $eventStmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$event) {
            die("Event not found.");
        }
    
        $capacity = $event['max_attendees'];
    
        // 3. Count how many are *not waitlisted* for this event
        $countStmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM attendees
            WHERE event_id = :event_id
                AND is_waitlisted = 0
        ");
        $countStmt->execute([':event_id' => $event_id]);
        $currentCount = $countStmt->fetchColumn();
    
        // 4. Decide if the user is waitlisted
        $isWaitlisted = ($currentCount >= $capacity) ? 1 : 0;
    
        // 5. Insert the new record using the user’s info from session
        $insertStmt = $pdo->prepare("
            INSERT INTO attendees (event_id, user_id, attendee_name, attendee_email, is_waitlisted)
            VALUES (:event_id, :user_id, :attendee_name, :attendee_email, :is_waitlisted)
        ");
        $insertStmt->execute([
            ':event_id'       => $event_id,
            ':user_id'        => $_SESSION['user_id'],
            ':attendee_name'  => $_SESSION['user_name'],  // from session
            ':attendee_email' => $_SESSION['user_email'], // from session
            ':is_waitlisted'  => $isWaitlisted
        ]);
    
        // 6. Show success or waitlist message
        if ($isWaitlisted) {
            echo "<div class='alert alert-warning'>
                    Registration successful, but you are waitlisted due to full capacity.
                    </div>";
        } else {
            echo "<div class='alert alert-success'>
                    Registration successful! We look forward to your attendance.
                    </div>";
        }
    
        echo "<p><a href='index.php?action=details&event_id={$event_id}'>Back to Event</a></p>";
        exit;
        break;

    case 'waitlist':
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            die("Access denied. Admin only.");
        }
    
        if (!isset($_GET['event_id'])) {
            die("No event specified.");
        }
    
        $event_id = $_GET['event_id'];
        $event = $eventObj->getEventById($event_id);
    
        // Check the event's user_id
        if (!$event || $event['user_id'] != $_SESSION['user_id']) {
            die("Access denied. You didn't create this event.");
        }
    
        // If passes, show the waitlist page
        include '../pages/waitlist.php';
        break;

    case 'analytics':
        // Only admin can see analytics
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            die("Access denied. Admin only.");
        }
        include '../pages/analytics.php';
        break;

    case 'my_registrations':
            session_start();
            if (!isset($_SESSION['user_id'])) {
                die("You must be logged in to view your registrations.");
            }
            include '../pages/my_registrations.php';
            break;        

    default:
        include '../pages/event_list.php';
        break;
}
