<?php
class Auth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Register a new user (default role = user).
     * If $isAdmin is true, require the correct OTP.
     */
    public function register($name, $email, $password, $otp = null) {
        // Check if email already exists
        $checkStmt = $this->pdo->prepare("SELECT user_id FROM users WHERE email = :email");
        $checkStmt->execute([':email' => $email]);
        if ($checkStmt->fetch()) {
            return ['success' => false, 'message' => 'Email already registered'];
        }

        $role = 'user';
        // If user is registering as an admin, verify OTP
        if (!is_null($otp)) {
            require_once __DIR__ . '/../config/config.php';
            if ($otp === ADMIN_OTP) {
                $role = 'admin';
            } else {
                return ['success' => false, 'message' => 'Invalid OTP for admin registration'];
            }
        }

        // Hash password (in real apps, use password_hash with robust hashing)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO users (name, email, password, role) 
            VALUES (:name, :email, :password, :role)
        ");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role
        ]);

        return ['success' => true, 'message' => 'Registration successful!'];
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'No user found with that email.'];
        }
        // Verify password
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Incorrect password.'];
        }

        // If success, return user data
        return [
            'success' => true,
            'user' => $user
        ];
    }
}
?>
