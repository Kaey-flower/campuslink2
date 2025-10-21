<?php
session_start(); // FIX 1: CRITICAL - Start the session
require 'includes/db_config.php'; // Provides $pdo

$error = '';
$name = $email = $course = ''; // Initialize variables for form persistence

// FIX 2: If user is already logged in, redirect them to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? ''; // Raw password for hashing
    $course = trim($_POST['course'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($course)) {
        $error = "All fields are required.";
    } else {
        // FIX 3: Use PDO to check if the email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "This email is already registered. Please log in instead.";
        } else {
            // FIX 4: Hash the password for secure storage
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user using PDO
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, course) VALUES (?, ?, ?, ?)");

            if ($stmt->execute([$name, $email, $hashed_password, $course])) {
                // Get the ID of the newly inserted user
                $user_id = $pdo->lastInsertId();

                // FIX 5: Standardize session key to 'user_id'
                $_SESSION['user_id'] = $user_id;
                
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<?php require 'includes/header.php'; ?>

<div class="max-w-md mx-auto bg-white p-6 rounded shadow mt-10">
    <h2 class="text-2xl font-bold mb-4 text-center">Create Account</h2>

    <?php if (!empty($error)): ?>
        <p class="text-red-500 text-center mb-3"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="block mb-1">Full Name</label>
            <input type="text" name="name" class="w-full border p-2 rounded" required value="<?= htmlspecialchars($name) ?>">
        </div>

        <div class="mb-3">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" required value="<?= htmlspecialchars($email) ?>">
        </div>

        <div class="mb-3">
            <label class="block mb-1">Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Course</label>
            <input type="text" name="course" class="w-full border p-2 rounded" required value="<?= htmlspecialchars($course) ?>">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Register</button>

        <p class="text-center mt-4 text-sm">
            Already have an account?
            <a href="login.php" class="text-blue-600 hover:underline">Login here</a>
        </p>
    </form>
</div>

<?php require 'includes/footer.php'; ?>

