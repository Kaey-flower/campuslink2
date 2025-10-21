<?php
session_start(); 
require 'includes/db_config.php'; 


$q = isset($_GET['q']) ? trim($_GET['q']) : '';


function escape_like_wildcards($str) {
  
    return str_replace(['%', '_', '\\'], ['\\%', '\\_', '\\\\'], $str);
}

// Prepare the LIKE pattern.
$safe_q = escape_like_wildcards($q);
$like_pattern = "%" . $safe_q . "%";

require 'includes/header.php';
?>

<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
<h2 class="text-2xl font-bold mb-4 text-blue-600">Search Results for: "<?php echo htmlspecialchars($q); ?>"</h2>

<?php if ($q): ?>

<h4 class="mt-6 font-bold text-xl border-b pb-2">📚 Resources</h4>
<?php
$rows = [];
try {
    $stmt_r = $pdo->prepare('SELECT r.id, r.title, r.description, r.file_path, u.name FROM resources r JOIN users u ON r.user_id = u.id WHERE r.title LIKE ? OR r.description LIKE ? LIMIT 20');
    $stmt_r->execute([$like_pattern, $like_pattern]);
    $rows = $stmt_r->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<p class="text-red-600 font-bold">Error searching resources: ' . htmlspecialchars($e->getMessage()) . '</p>';
}

if ($rows): ?>
<?php foreach ($rows as $r): ?>
<div class="border-b py-3">
    <a href="/resources.php?id=<?php echo htmlspecialchars($r['id']); ?>" class="font-semibold text-blue-600 hover:underline">
        <?php echo htmlspecialchars($r['title']); ?>
    </a>
    <div class="text-sm text-gray-600">by <?php echo htmlspecialchars($r['name']); ?></div>
    <div class="text-xs text-gray-500 mt-1">

        [<a href="<?php echo htmlspecialchars($r['file_path']); ?>" target="_blank" class="text-purple-600 hover:text-purple-700">Download File</a>]
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<p class="text-gray-600 italic mt-2">No resources found matching your search.</p>
<?php endif; ?>




<?php else: ?>
<p class="text-gray-600 mt-2">Please enter a search query.</p>
<?php endif; ?>
</div>
<?php require 'includes/footer.php'; ?>