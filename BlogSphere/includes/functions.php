<?php
// Helper functions

// Generate a URL-friendly slug from a string
function generateSlug($string) {
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    return trim($slug, '-');
}

// (Optional) Enhanced routing logic based on a 'page' parameter
function routeRequest() {
    if (isset($_GET['page'])) {
        $page = basename($_GET['page']); // basic sanitization
        $file = "../public/{$page}.php";
        if (file_exists($file)) {
            include $file;
            return;
        }
    }
    include "../public/index.php";
}
?>
