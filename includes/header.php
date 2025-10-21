<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CampusLink | Connect. Grow. Succeed.</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-blue-800 text-white p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="text-3xl">🎓</div>
                <h1 class="text-2xl font-bold">CampusLink</h1>
            </div>
            
            <div class="flex items-center gap-6">
                <a href="index.php" class="px-3 py-2 hover:underline transition-all">Home</a>
                <a href="resources.php" class="px-3 py-2 hover:underline transition-all">Resources</a>
                <a href="livejobs.php" class="px-3 py-2 hover:underline transition-all">Live Jobs</a>
                
                <?php if (!isset($_SESSION['user'])): ?>
                    <a href="login.php" class="bg-white text-blue-800 px-4 py-2 rounded-md font-semibold hover:bg-blue-100 transition-all">Login</a>
                    <a href="register.php" class="bg-blue-600 px-4 py-2 rounded-md font-semibold hover:bg-blue-700 transition-all">Sign Up</a>
                <?php else: ?>
                    <a href="dashboard.php" class="px-3 py-2 hover:underline transition-all">Dashboard</a>
                    <a href="profile.php" class="px-3 py-2 hover:underline transition-all">Profile</a>
                    
                    <!-- Gamification Display -->
                    <div class="flex items-center gap-3">
                        <div id="points-display" class="points-display">
                            <span style="font-size: 1.5rem;">⭐</span> 0
                        </div>
                        <div id="level-display" class="level-badge">
                            Level 1
                        </div>
                    </div>
                    
                    <a href="logout.php" class="bg-red-500 px-4 py-2 rounded-md font-semibold hover:bg-red-600 transition-all">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="p-6">