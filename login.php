<?php
session_start(); // CRITICAL FIX: Start the session
require 'includes/db_config.php'; // Assuming this provides a $pdo (PDO object)

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // FIX: Use PDO for consistency and security (assuming db_config provides $pdo)
        // FIX: Select the hashed password and the user's ID
        $stmt = $pdo->prepare("SELECT id, name, email, course, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // FIX: Use password_verify() for secure password checking
            // NOTE: The original code was using plain text passwords. This fix assumes
            // the passwords in the database are now stored using password_hash().
            // If they are still plain text, this will fail until they are updated.
            if (password_verify($password, $user['password'])) {
                
                // FIX: Use consistent session key 'user_id' as expected by protected pages
                $_SESSION['user_id'] = $user['id']; 
                
                // Optional: Store other user details under a different key if needed, 
                // but 'user_id' is the minimal requirement for authentication check.
                // The original code used 'user', which is being removed for consistency.
                
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Incorrect email or password. Try again.";
            }
        } else {
            $error = "Incorrect email or password. Try again.";
        }
    }
}
?>

<?php require 'includes/header.php'; ?>

<div class="max-w-md mx-auto bg-white p-6 rounded shadow mt-10">
    <h2 class="text-2xl font-bold mb-4 text-center">Welcome Back</h2>

    <?php if (!empty($error)): ?>
        <p class="text-red-500 text-center mb-3"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Login</button>

        <p class="text-center mt-4 text-sm">
            Don’t have an account?
            <a href="register.php" class="text-blue-600 hover:underline">Register here</a>
        </p>
    </form>
</div>

<?php require 'includes/footer.php'; ?>
