<?php require 'includes/header.php'; ?>

<!-- Hero Section -->
<div class="max-w-6xl mx-auto mt-10 text-center">
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-12 rounded-2xl shadow-2xl mb-10">
        <h1 class="text-5xl font-bold mb-4 animate-fade-in">Welcome to CampusLink 🎓</h1>
        <p class="text-2xl mb-8">Connect. Grow. Succeed.</p>
        <p class="text-lg mb-8 max-w-3xl mx-auto">Your one-stop platform for internships, networking, and career growth. Join thousands of students building their future today!</p>
        
        <?php if (!isset($_SESSION['user'])): ?>
            <div class="flex gap-4 justify-center">
                <a href="register.php" class="px-8 py-4 bg-white text-blue-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                    Get Started Free
                </a>
                <a href="login.php" class="px-8 py-4 border-2 border-white rounded-lg font-bold text-lg hover:bg-white hover:text-blue-600 transition-all transform hover:scale-105">
                    Login
                </a>
            </div>
        <?php else: ?>
            <a href="dashboard.php" class="inline-block px-8 py-4 bg-white text-blue-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                Go to Dashboard →
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Features Section -->
<div class="max-w-6xl mx-auto mb-10">
    <h2 class="text-3xl font-bold text-center text-white mb-8">Why Choose CampusLink?</h2>
    
    <div class="grid md:grid-cols-3 gap-8 mb-10">
        <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105">
            <div class="text-6xl mb-4 text-center">💼</div>
            <h3 class="text-xl font-bold text-center mb-3">Live Job Listings</h3>
            <p class="text-gray-600 text-center">Access real-time internship and graduate opportunities from top companies across Kenya.</p>
        </div>
        
        <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105">
            <div class="text-6xl mb-4 text-center">🤝</div>
            <h3 class="text-xl font-bold text-center mb-3">Student Networking</h3>
            <p class="text-gray-600 text-center">Connect with peers, mentors, and industry professionals to expand your network.</p>
        </div>
        
        <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105">
            <div class="text-6xl mb-4 text-center">🏆</div>
            <h3 class="text-xl font-bold text-center mb-3">Gamified Learning</h3>
            <p class="text-gray-600 text-center">Earn points, unlock achievements, and level up as you engage with the platform.</p>
        </div>
    </div>
</div>

<!-- Interactive Sections -->
<div class="max-w-4xl mx-auto grid gap-6">
    <!-- Quote of the Day -->
    <div class="bg-white p-8 rounded-xl shadow-lg">
        <h3 class="text-2xl font-bold mb-4 text-blue-700 flex items-center gap-2">
            <span>💡</span> Quote of the Day
        </h3>
        <div id="quote-box" class="italic text-gray-700 text-lg mb-4 min-h-[100px] flex items-center justify-center">
            Loading...
        </div>
        <div class="text-center">
            <button id="new-quote" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold">
                Get New Quote
            </button>
        </div>
    </div>

    <!-- Quick Search -->
    <div class="bg-white p-8 rounded-xl shadow-lg">
        <h3 class="text-2xl font-bold mb-4 text-blue-700 flex items-center gap-2">
            <span>🔍</span> Quick Search
        </h3>
        <form action="search.php" method="GET" class="flex gap-3">
            <input type="text" name="q" placeholder="Search resources" 
                   class="flex-1 border-2 border-gray-300 p-3 rounded-lg focus:outline-none focus:border-blue-500 transition-all" 
                   required />
            <button class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold">
                Search
            </button>
        </form>
    </div>

    <!-- Statistics -->
    <div class="bg-white p-8 rounded-xl shadow-lg">
        <h3 class="text-2xl font-bold mb-6 text-blue-700 text-center">Platform Statistics</h3>
        <div class="grid grid-cols-3 gap-6 text-center">
            <div>
                <div class="text-4xl font-bold text-blue-600 mb-2">500+</div>
                <div class="text-gray-600">Active Students</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-purple-600 mb-2">150+</div>
                <div class="text-gray-600">Job Listings</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-green-600 mb-2">95%</div>
                <div class="text-gray-600">Success Rate</div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <?php if (!isset($_SESSION['user'])): ?>
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white p-8 rounded-xl shadow-lg text-center">
        <h3 class="text-2xl font-bold mb-4">Ready to Start Your Journey?</h3>
        <p class="mb-6 text-lg">Join CampusLink today and unlock endless opportunities for your career!</p>
        <a href="register.php" class="inline-block px-8 py-4 bg-white text-blue-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
            Create Free Account
        </a>
    </div>
    <?php endif; ?>
</div>

<style>
@keyframes animate-fade-in {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: animate-fade-in 1s ease-out;
}
</style>

<?php require 'includes/footer.php'; ?>

