<?php
require 'includes/db_config.php'; // Ensures $pdo is defined
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$uid = $_SESSION['user_id'];

// Fetch current user data
$stmt = $pdo->prepare('SELECT id, name, email, institution, course, skills, photo FROM users WHERE id = ?');
$stmt->execute([$uid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // If the user_id exists in the session but not in the database, destroy the session and redirect
    session_destroy();
    header('Location: login.php?error=user_not_found');
    exit;
}

$errors = [];

// Handle form submission (Update logic remains the same)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $institution = trim($_POST['institution'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $skills = trim($_POST['skills'] ?? '');

    // Handle photo upload
    $photo = $user['photo']; // keep existing if no new upload

    if (!empty($_FILES['photo']['name'])) {
        $upload_dir = __DIR__ . '/uploads/';
        // FIX: Ensure the uploads directory exists
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }
        
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('pf_') . '.' . $ext;
        $target = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $photo = $filename;
            // OPTIONAL: Delete old photo file if it exists and is not the default
            if (!empty($user['photo']) && file_exists($upload_dir . $user['photo'])) {
                @unlink($upload_dir . $user['photo']);
            }
        } else {
            $errors[] = 'Failed to upload photo.';
        }
    }

    // Update only if no errors
    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE users SET name=?, institution=?, course=?, skills=?, photo=? WHERE id=?');
        $stmt->execute([$name, $institution, $course, $skills, $photo, $uid]);
        // Redirect to prevent form resubmission and display updated data
        header('Location: profile.php');
        exit;
    }
}

// Assuming 'includes/header.php' and 'includes/footer.php' handle common layout elements
// and that the main CampusLink color is a shade of blue (e.g., #3B82F6 - blue-600 in Tailwind)
$primary_color_class = 'bg-blue-600';
$primary_text_class = 'text-blue-600';
$primary_hover_class = 'hover:bg-blue-700';
$ring_focus_class = 'focus:ring-blue-500 focus:border-blue-500';

require 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['name'] ?? 'User'); ?>'s Profile - CampusLink</title>
    <!-- Tailwind CSS CDN (same as original) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CDN (same as original) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Custom scrollbar for a cleaner look */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1; /* gray-300 */
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background-color: #f1f5f9; /* gray-100 */
        }
    </style>
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal">

<div class="container mx-auto p-4 md:p-8">
    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-2xl overflow-hidden">
        
        <!-- Profile Header Section -->
        <div class="relative h-40 <?php echo $primary_color_class; ?> flex items-center justify-center">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <h1 class="relative text-3xl font-extrabold text-white z-10">User Profile Management</h1>
        </div>

        <div class="p-6 md:p-10 grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- Left Column: Profile Card (Display) -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-6 text-center sticky top-8">
                    
                    <!-- Profile Photo -->
                    <div class="w-40 h-40 mx-auto rounded-full overflow-hidden border-4 border-white shadow-xl -mt-20 bg-gray-200 flex items-center justify-center">
                        <?php if (!empty($user['photo'])): ?>
    
<img src="/campuslink/uploads/<?php echo htmlspecialchars($user['photo']); ?>"

<img src="/campuslink2/uploads/<?php echo htmlspecialchars($user['photo']); ?>"  class="h-full w-full object-cover"/>

                        <?php else: ?>
                            <i class="fas fa-user-circle fa-4x text-gray-500"></i>
                        <?php endif; ?>
                    </div>

                    <!-- Name and Email -->
                    <h2 class="mt-4 text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></h2>
                    <p class="text-sm text-gray-500 mb-6 flex items-center justify-center">
                        <i class="fas fa-envelope mr-2 text-sm <?php echo $primary_text_class; ?>"></i>
                        <?php echo htmlspecialchars($user['email'] ?? 'Email Not Available'); ?>
                    </p>

                    <!-- Institution and Course -->
                    <div class="space-y-3 text-left">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-university w-6 <?php echo $primary_text_class; ?>"></i>
                            <span class="ml-3 font-medium">Institution:</span>
                            <span class="ml-2 text-gray-600"><?php echo htmlspecialchars($user['institution'] ?? 'Not Set'); ?></span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-graduation-cap w-6 <?php echo $primary_text_class; ?>"></i>
                            <span class="ml-3 font-medium">Course/Major:</span>
                            <span class="ml-2 text-gray-600"><?php echo htmlspecialchars($user['course'] ?? 'Not Set'); ?></span>
                        </div>
                    </div>

                    <!-- Skills/Bio Section -->
                    <div class="mt-6 pt-4 border-t border-gray-100 text-left">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-lightbulb mr-2 <?php echo $primary_text_class; ?>"></i>
                            Skills & Interests
                        </h3>
                        <p class="text-sm text-gray-600 custom-scrollbar max-h-24 overflow-y-auto">
                            <?php 
                                $skills = htmlspecialchars($user['skills'] ?? 'No skills listed yet. Use the form to update your profile!');
                                // Simple tag-like display for skills if comma-separated
                                if (strpos($skills, ',') !== false) {
                                    $skill_tags = explode(',', $skills);
                                    echo '<div class="flex flex-wrap gap-2">';
                                    foreach ($skill_tags as $tag) {
                                        echo '<span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">' . trim($tag) . '</span>';
                                    }
                                    echo '</div>';
                                } else {
                                    echo $skills;
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Profile Update Form -->
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2 flex items-center">
                    <i class="fas fa-edit mr-3 <?php echo $primary_text_class; ?>"></i>
                    Update Your Information
                </h2>

                <?php if (!empty($errors)): ?>
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 border border-red-200">
                        <p class="font-semibold mb-1 flex items-center"><i class="fas fa-exclamation-triangle mr-2"></i> Update Failed</p>
                        <?php foreach ($errors as $err) echo "<p class='text-sm'>- $err</p>"; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">

                    <!-- Photo Upload Field -->
                    <div class="border border-gray-200 p-4 rounded-lg">
                        <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-camera mr-2 <?php echo $primary_text_class; ?>"></i>
                            Profile Photo
                        </label>
                        <input type="file" name="photo" id="photo" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100 cursor-pointer
                        "/>
                        <p class="mt-1 text-xs text-gray-500">Upload a new photo (Max 2MB, JPG/PNG recommended).</p>
                    </div>

                    <!-- Personal Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-user mr-2 text-xs <?php echo $primary_text_class; ?>"></i>
                                Full Name
                            </label>
                            <input id="name" name="name" type="text" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required
                                class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:outline-none <?php echo $ring_focus_class; ?>"
                                placeholder="e.g., Jane Doe" />
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-at mr-2 text-xs <?php echo $primary_text_class; ?>"></i>
                                Email Address (Read-only)
                            </label>
                            <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly
                                class="w-full border border-gray-200 bg-gray-100 p-3 rounded-lg shadow-inner cursor-not-allowed" />
                        </div>
                    </div>

                    <!-- Academic Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="institution" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-building mr-2 text-xs <?php echo $primary_text_class; ?>"></i>
                                Institution / University
                            </label>
                            <input id="institution" name="institution" type="text" value="<?php echo htmlspecialchars($user['institution'] ?? ''); ?>"
                                class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:outline-none <?php echo $ring_focus_class; ?>"
                                placeholder="e.g., University of CampusLink" />
                        </div>

                        <div>
                            <label for="course" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-book-open mr-2 text-xs <?php echo $primary_text_class; ?>"></i>
                                Course / Major
                            </label>
                            <input id="course" name="course" type="text" value="<?php echo htmlspecialchars($user['course'] ?? ''); ?>"
                                class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:outline-none <?php echo $ring_focus_class; ?>"
                                placeholder="e.g., Computer Science" />
                        </div>
                    </div>

                    <!-- Skills/Bio Textarea -->
                    <div>
                        <label for="skills" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-code mr-2 text-xs <?php echo $primary_text_class; ?>"></i>
                            Skills (Comma-separated)
                        </label>
                        <textarea id="skills" name="skills" rows="3"
                            class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:outline-none <?php echo $ring_focus_class; ?>"
                            placeholder="e.g., PHP, Tailwind CSS, Database Management, Project Leadership"><?php echo htmlspecialchars($user['skills'] ?? ''); ?></textarea>
                        <p class="mt-1 text-xs text-gray-500">List your key skills, separated by commas.</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="px-8 py-3 <?php echo $primary_color_class; ?> text-white font-semibold rounded-lg shadow-md transition duration-300 <?php echo $primary_hover_class; ?> focus:outline-none focus:ring-4 focus:ring-blue-300">
                            <i class="fas fa-cloud-upload-alt mr-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php require 'includes/footer.php'; ?>

