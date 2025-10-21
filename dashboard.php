<?php
session_start(); // FIX 1: CRITICAL - Start the session
require 'includes/db_config.php'; // Provides $pdo

// FIX 2: Use consistent session key 'user_id' for authentication check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];

// FIX 3: Fetch full user data from DB using the user_id
$stmt = $pdo->prepare('SELECT id, name, email, course FROM users WHERE id = ?');
$stmt->execute([$uid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Defensive check: if user_id is in session but not in DB, destroy session and redirect
    session_destroy();
    header('Location: login.php?error=user_not_found');
    exit;
}

require 'includes/header.php';
?>

<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold text-blue-700 mb-2">
            Welcome!, <?= htmlspecialchars($user['name']); ?> 🎓
        </h2>
        <p class="text-gray-600">Course: <?= htmlspecialchars($user['course']); ?></p>
        <p class="text-gray-600 mb-4">Email: <?= htmlspecialchars($user['email']); ?></p>

        <a href="profile.php" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Edit Profile
        </a>
        <a href="logout.php" class="px-4 py-2 border rounded ml-2 hover:bg-gray-100">
            Logout
        </a>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mt-8">
        <!-- Campus Updates -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg mb-3 text-blue-600">🎓 Campus Updates</h3>
            <ul class="list-disc ml-5 text-gray-700">
                <li>Tech Career Fair - Oct 18th, 2025</li>
                <li>New mentorship program for developers</li>
                <li>Student leadership elections coming soon</li>
            </ul>
        </div>

        <!-- Internship Section -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="font-semibold text-lg mb-3 text-blue-600">💼 Internships for You</h3>
            <ul class="list-disc ml-5 text-gray-700">
                <li>Junior Web Developer at Safaricom</li>
                <li>UI/UX Intern at TwigaTech</li>
                <li>Software Assistant - Equity Bank</li>
            </ul>
            <div class="mt-4">
                <!-- <a href="search.php" class="text-blue-600 hover:underline">View more opportunities →</a> -->
                <a href="livejobs.php" class="text-blue-600 hover:underline">View more opportunities →</a>
            </div>
        </div>
    </div>

    <!-- Student Feed -->
    <div class="bg-white p-6 rounded shadow mt-8">
        <h3 class="font-semibold text-lg mb-4 text-blue-600">🗣 Student Community Feed</h3>

        <?php
       
        $stmt = $pdo->query("SELECT title, content, created_at FROM posts ORDER BY created_at DESC");
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($posts):
            foreach ($posts as $post): ?>
                <div class="border-b border-gray-200 mb-4 pb-3">
                    <p class="font-semibold"><?= htmlspecialchars($post['title']); ?></p>
                    <p class="text-gray-700 text-sm"><?= htmlspecialchars($post['content']); ?></p>
                    <p class="text-gray-500 text-xs mt-1">Posted on <?= htmlspecialchars($post['created_at']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500 italic">No posts yet. Be the first to share something!</p>
               <a href="posts.php" class="px-4 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors">
        <i class="fas fa-plus mr-1"></i> Create Post
    </a>
        <?php endif; ?>
    </div>
</div>


<!-- Networking Testimonials Carousel (HTML/JS remains the same) -->
<div class="bg-gray-100 py-10 mt-10">
  <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">Networking & Career Stories</h2>

  <div class="relative max-w-6xl mx-auto overflow-hidden">
    <div id="carousel" class="flex transition-transform duration-700 ease-in-out">

      <!-- Slide 1 -->
      <div class="min-w-full flex-shrink-0 p-6">
        <div class="bg-white rounded-2xl shadow-lg p-8">
          <p class="text-gray-700 italic mb-4">
            “Joining student networking events opened doors I never imagined — from mentorship to my first internship!”
          </p>
          <div class="flex items-center">
            <img src="https://randomuser.me/api/portraits/women/12.jpg" class="w-12 h-12 rounded-full mr-4 border-2 border-blue-500">
            <div>
              <p class="font-semibold text-gray-800">Angela W.</p>
              <p class="text-sm text-gray-500">Student Leader, Nairobi</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="min-w-full flex-shrink-0 p-6">
        <div class="bg-white rounded-2xl shadow-lg p-8">
          <p class="text-gray-700 italic mb-4">
            “After I revamped my LinkedIn using these tips, I started getting messages from recruiters weekly. It really works!”
          </p>
          <div class="flex items-center">
            <img src="https://randomuser.me/api/portraits/women/12.jpg" alt="Author" class="w-12 h-12 rounded-full mr-4 border-2 border-blue-500">
            <div>
              <p class="font-semibold text-gray-800">Brian K.</p>
              <p class="text-sm text-gray-500">Tech Graduate, Mombasa</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="min-w-full flex-shrink-0 p-6">
        <div class="bg-white rounded-2xl shadow-lg p-8">
          <p class="text-gray-700 italic mb-4">
            “I attended a local networking event and met my future co-founder there — it changed everything for me.”
          </p>
          <div class="flex items-center">
            <img src="https://randomuser.me/api/portraits/women/12.jpg" alt="Author" class="w-12 h-12 rounded-full mr-4 border-2 border-blue-500">
            <div>
              <p class="font-semibold text-gray-800">Lilian M.</p>
              <p class="text-sm text-gray-500">Entrepreneur, Kisumu</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 4 -->
      <div class="min-w-full flex-shrink-0 p-6">
        <div class="bg-white rounded-2xl shadow-lg p-8">
          <p class="text-gray-700 italic mb-4">
            “Networking online helped me find mentors from South Africa and Ghana — real people who guided my career path.”
          </p>
          <div class="flex items-center">
            <img src="https://randomuser.me/api/portraits/women/12.jpg" alt="Author" class="w-12 h-12 rounded-full mr-4 border-2 border-blue-500">
            <div>
              <p class="font-semibold text-gray-800">David O.</p>
              <p class="text-sm text-gray-500">Software Developer, Nairobi</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Carousel Controls -->
    <div class="flex justify-center mt-6">
      <button id="prev" class="px-3 py-1 mx-2 bg-blue-600 text-white rounded-full hover:bg-blue-700">&lt;</button>
      <button id="next" class="px-3 py-1 mx-2 bg-blue-600 text-white rounded-full hover:bg-blue-700">&gt;</button>
    </div>
  </div>
</div>

<script>
  const carousel = document.getElementById('carousel');
  const slides = carousel.children.length;
  let index = 0;

  document.getElementById('next').addEventListener('click', () => {
    index = (index + 1) % slides;
    carousel.style.transform = `translateX(-${index * 100}%)`;
  });

  document.getElementById('prev').addEventListener('click', () => {
    index = (index - 1 + slides) % slides;
    carousel.style.transform = `translateX(-${index * 100}%)`;
  });

  // Auto-scroll
  setInterval(() => {
    index = (index + 1) % slides;
    carousel.style.transform = `translateX(-${index * 100}%)`;
  }, 8000);
</script>


<?php require 'includes/footer.php'; ?>
