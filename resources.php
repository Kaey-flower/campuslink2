<?php
session_start(); // FIX 1: Ensure session is started consistently at the top
require 'includes/db_config.php'; // Provides $pdo object

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$title = $description = $category = '';
$errors = [];

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($description) || empty($category)) {
        $errors[] = "All fields are required.";
    }

    if (!empty($_FILES["file"]["name"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        // FIX 2: Ensure the uploads directory exists relative to the script
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }

        $file_name = basename($_FILES["file"]["name"]);
        $file_tmp = $_FILES["file"]["tmp_name"];
        $file_size = $_FILES["file"]["size"];
        $file_type = pathinfo($file_name, PATHINFO_EXTENSION);
        // FIX 3: Use a unique and secure filename
        $unique_filename = uniqid('res_') . '.' . strtolower($file_type);
        $target_file = $upload_dir . $unique_filename;

        if (empty($errors)) {
            if (move_uploaded_file($file_tmp, $target_file)) {
                // FIX 4: Use PDO prepared statements
                $stmt = $pdo->prepare("INSERT INTO resources (user_id, title, description, file_path, category, file_size, file_type, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
                
                if ($stmt->execute([$user_id, $title, $description, $target_file, $category, $file_size, $file_type])) {
                    header("Location: uploads.php?success=1");
                    exit();
                } else {
                    $errors[] = "Database insertion failed.";
                    // Clean up the uploaded file if DB insertion fails
                    @unlink($target_file);
                }
            } else {
                $errors[] = "File upload failed. Check file size and server permissions.";
            }
        }
    } elseif (!empty($_FILES["file"]["name"]) && $_FILES["file"]["error"] !== UPLOAD_ERR_NO_FILE) {
         $errors[] = 'File upload failed with error code: ' . $_FILES["file"]["error"];
    } else {
        $errors[] = 'Please select a file to upload.';
    }
}

require 'includes/header.php';
?>

<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow mt-6">
  <h2 class="text-2xl font-bold mb-4">Upload Document</h2>

  <?php if (!empty($errors)): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 border border-red-200">
        <p class="font-semibold mb-1">Upload Failed</p>
        <?php foreach ($errors as $err) echo "<p class='text-sm'>- " . htmlspecialchars($err) . "</p>"; ?>
    </div>
  <?php endif; ?>

  <form action="resources.php" method="POST" enctype="multipart/form-data" class="space-y-4">
      <div>
          <label class="block font-semibold mb-1">Title</label>
          <input type="text" name="title" required class="w-full border px-3 py-2 rounded" value="<?= htmlspecialchars($title) ?>">
      </div>

      <div>
          <label class="block font-semibold mb-1">Description</label>
          <textarea name="description" required class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($description) ?></textarea>
      </div>

      <div>
          <label class="block font-semibold mb-1">Category</label>
          <select name="category" required class="w-full border px-3 py-2 rounded">
              <option value="">Select Category</option>
              <option value="Notes" <?= $category == 'Notes' ? 'selected' : '' ?>>Notes</option>
              <option value="Assignments" <?= $category == 'Assignments' ? 'selected' : '' ?>>Assignments</option>
              <option value="Projects" <?= $category == 'Projects' ? 'selected' : '' ?>>Projects</option>
              <option value="Past Papers" <?= $category == 'Past Papers' ? 'selected' : '' ?>>Past Papers</option>
          </select>
      </div>

      <div>
          <label class="block font-semibold mb-1">Select File</label>
          <input type="file" name="file" required class="w-full border px-3 py-2 rounded">
          <p class="text-xs text-gray-500 mt-1">Max 2MB recommended. Common file types accepted.</p>
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Upload
      </button>
  </form>
</div>

<?php require 'includes/footer.php'; ?>

