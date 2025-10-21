<?php 
session_start(); // FIX 1: Ensure session is started consistently at the top
require 'includes/db_config.php'; // Provides $pdo object

// NOTE: This page is assumed to be public or semi-public (viewable by all logged-in users). 

// Fetch all resources
// FIX 2: Use PDO for consistency and security
$query = "SELECT r.*, u.name AS uploader_name 
          FROM resources r 
          JOIN users u ON r.user_id = u.id 
          ORDER BY r.created_at DESC";

try {
    $stmt = $pdo->query($query);
    $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // FIX 3: Graceful error handling instead of die()
    $error_message = "Database query failed: " . $e->getMessage();
    $resources = [];
}

require 'includes/header.php';
?>

<div class="bg-white p-6 rounded shadow mt-6">
  <h2 class="text-2xl font-bold mb-4">Resource Library</h2>
  <p class="text-gray-600 mb-6">Access and share educational documents, notes, and study materials.</p>

  <?php if (isset($error_message)): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 border border-red-200">
        <p class="font-semibold mb-1">Database Error</p>
        <p class='text-sm'><?= htmlspecialchars($error_message) ?></p>
    </div>
  <?php endif; ?>

  <?php if (count($resources) > 0): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <?php foreach ($resources as $res): ?>
              <div class="border p-4 rounded shadow">
                  <h3 class="font-semibold mb-2"><?php echo htmlspecialchars($res['title']); ?></h3>
                  <p class="text-gray-700 mb-1"><?php echo htmlspecialchars($res['description']); ?></p>
                  <p class="text-gray-500 text-sm mb-2">
                      Uploaded by: <?php echo htmlspecialchars($res['uploader_name']); ?>
                  </p>
                  <p class="text-gray-500 text-sm mb-2">
                      Category: <?php echo htmlspecialchars($res['category']); ?>
                  </p>

                  <a href="<?php echo htmlspecialchars($res['file_path']); ?>" 
                     target="_blank" 
                     class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                     Open Document
                  </a>
              </div>
          <?php endforeach; ?>
      </div>
  <?php else: ?>
      <p class="text-gray-500">No resources yet. Upload your first document below!</p>
  <?php endif; ?>
</div>

<div class="mt-6">
  <a href="resources.php" class="text-blue-600 underline">Upload Document</a>
</div>

<?php require 'includes/footer.php'; ?>
