<?php
// admin_manage_users.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include 'includes/header.php';
include 'db.php';

// Fetch admins
$adminQuery = "SELECT id, username FROM admins";
$adminResult = $conn->query($adminQuery);

// Fetch voters
$voterQuery = "SELECT id, username, email FROM voters";
$voterResult = $conn->query($voterQuery);
?>
<main>
    <div class="vw-container">
        <h2 class="vw-heading">Manage Users</h2>
        
        <section class="vw-dashboard-section">
            <h3 class="vw-subheading">Admins</h3>
            <div class="vw-action-bar">
                <a class="vw-btn" href="admin_add_admin.php">Add Admin</a>
            </div>
            <div class="vw-table-container">
                <table class="vw-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($admin = $adminResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $admin['id']; ?></td>
                            <td><?php echo htmlspecialchars($admin['username']); ?></td>
                            <td>
                                <a class="vw-link" href="admin_edit_admin.php?id=<?php echo $admin['id']; ?>">Edit</a>
                                <a class="vw-link" href="admin_delete_admin.php?id=<?php echo $admin['id']; ?>" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
        
        <section class="vw-dashboard-section">
            <h3 class="vw-subheading">Voters</h3>
            <div class="vw-action-bar">
                <a class="vw-btn" href="admin_add_voter.php">Add Voter</a>
            </div>
            <div class="vw-table-container">
                <table class="vw-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($voter = $voterResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $voter['id']; ?></td>
                            <td><?php echo htmlspecialchars($voter['username']); ?></td>
                            <td><?php echo htmlspecialchars($voter['email']); ?></td>
                            <td>
                                <a class="vw-link" href="admin_edit_voter.php?id=<?php echo $voter['id']; ?>">Edit</a>
                                <a class="vw-link" href="admin_delete_voter.php?id=<?php echo $voter['id']; ?>" onclick="return confirm('Are you sure you want to delete this voter?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
