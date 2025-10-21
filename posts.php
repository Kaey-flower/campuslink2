<?php
session_start(); // FIX 1: Start the session
require 'includes/db_config.php'; // Provides $pdo object

// FIX 2: Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$errors = [];
$success_message = '';
$title = '';
$content = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title)) {
        $errors[] = 'Post title is required.';
    }
    if (empty($content)) {
        $errors[] = 'Post content is required.';
    }

    if (empty($errors)) {
        // FIX 3: Use PDO for secure insertion
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content, created_at) VALUES (?, ?, ?, NOW())");
        
        if ($stmt->execute([$uid, $title, $content])) {
            $success_message = "Your post has been successfully published!";
            // Clear form data on success
            $title = '';
            $content = '';
        } else {
            $errors[] = "Failed to publish post. Please try again.";
        }
    }
}

require 'includes/header.php';
?>

<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow mt-6">
    <h2 class="text-2xl font-bold mb-4 text-blue-600">Create New Post</h2>

    <?php if (!empty($success_message)): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 border border-green-200">
            <p class="font-semibold mb-1"><i class="fas fa-check-circle mr-2"></i> Success!</p>
            <p class='text-sm'><?= htmlspecialchars($success_message) ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 border border-red-200">
            <p class="font-semibold mb-1"><i class="fas fa-exclamation-triangle mr-2"></i> Post Failed</p>
            <?php foreach ($errors as $err) echo "<p class='text-sm'>- " . htmlspecialchars($err) . "</p>"; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="posts.php" class="space-y-4">
        <div>
            <label for="title" class="block font-semibold mb-1">Post Title</label>
            <input type="text" id="title" name="title" required class="w-full border px-3 py-2 rounded" value="<?= htmlspecialchars($title) ?>">
        </div>

        <div>
            <label for="content" class="block font-semibold mb-1">Content</label>
            <textarea id="content" name="content" rows="8" required class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($content) ?></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Publish Post
        </button>
    </form>
    
    <div class="mt-6 pt-4 border-t border-gray-100">
        <a href="dashboard.php" class="text-blue-600 hover:underline">← Back to Dashboard Feed</a>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
