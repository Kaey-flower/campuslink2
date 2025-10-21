// CampusLink Enhanced JavaScript

// Gamification System
class GamificationSystem {
  constructor() {
    this.points = parseInt(localStorage.getItem('campuslink_points')) || 0;
    this.level = parseInt(localStorage.getItem('campuslink_level')) || 1;
    this.achievements = JSON.parse(localStorage.getItem('campuslink_achievements')) || [];
    this.init();
  }

  init() {
    this.updateDisplay();
    this.checkAchievements();
  }

  addPoints(amount, reason) {
    this.points += amount;
    localStorage.setItem('campuslink_points', this.points);
    this.checkLevelUp();
    this.updateDisplay();
    this.showToast(`+${amount} points! ${reason}`, 'success');
  }

  checkLevelUp() {
    const newLevel = Math.floor(this.points / 100) + 1;
    if (newLevel > this.level) {
      this.level = newLevel;
      localStorage.setItem('campuslink_level', this.level);
      this.showToast(`🎉 Level Up! You're now Level ${this.level}!`, 'success');
      this.unlockAchievement('level_' + this.level);
    }
  }

  unlockAchievement(achievementId) {
    if (!this.achievements.includes(achievementId)) {
      this.achievements.push(achievementId);
      localStorage.setItem('campuslink_achievements', JSON.stringify(this.achievements));
      this.showAchievementModal(achievementId);
    }
  }

  showAchievementModal(achievementId) {
    const achievements = {
      'first_login': { title: 'Welcome Aboard!', icon: '👋', description: 'Logged in for the first time' },
      'job_explorer': { title: 'Job Explorer', icon: '🔍', description: 'Viewed 10 job listings' },
      'networking_pro': { title: 'Networking Pro', icon: '🤝', description: 'Connected with 5 students' },
      'level_5': { title: 'Rising Star', icon: '⭐', description: 'Reached Level 5' },
      'level_10': { title: 'Campus Legend', icon: '🏆', description: 'Reached Level 10' },
      'profile_complete': { title: 'Profile Master', icon: '✅', description: 'Completed your profile' }
    };

    const achievement = achievements[achievementId] || { title: 'Achievement', icon: '🎖️', description: 'Unlocked!' };
    
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.display = 'block';
    modal.innerHTML = `
      <div class="modal-content text-center">
        <span class="close">&times;</span>
        <div style="font-size: 4rem; margin: 1rem 0;">${achievement.icon}</div>
        <h2 class="text-2xl font-bold text-blue-700 mb-2">Achievement Unlocked!</h2>
        <h3 class="text-xl font-semibold mb-2">${achievement.title}</h3>
        <p class="text-gray-600">${achievement.description}</p>
      </div>
    `;
    document.body.appendChild(modal);

    modal.querySelector('.close').onclick = () => {
      modal.remove();
    };

    setTimeout(() => {
      modal.remove();
    }, 5000);
  }

  updateDisplay() {
    const pointsDisplay = document.getElementById('points-display');
    const levelDisplay = document.getElementById('level-display');
    
    if (pointsDisplay) {
      pointsDisplay.innerHTML = `<span style="font-size: 1.5rem;">⭐</span> ${this.points}`;
    }
    
    if (levelDisplay) {
      levelDisplay.innerHTML = `Level ${this.level}`;
    }
  }

  checkAchievements() {
    // Check for various achievements based on user actions
    const loginCount = parseInt(localStorage.getItem('login_count')) || 0;
    if (loginCount === 1) {
      this.unlockAchievement('first_login');
    }
  }

  showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
      <div style="font-size: 1.5rem;">${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</div>
      <div>${message}</div>
    `;
    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.animation = 'fadeOut 0.3s ease';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }
}

// Initialize gamification system
let gamification;
if (typeof window !== 'undefined') {
  gamification = new GamificationSystem();
}

// Quote functionality
document.addEventListener("DOMContentLoaded", function () {
  const box = document.getElementById("quote-box");
  const btn = document.getElementById("new-quote");
  
  if (box && btn) {
    const quotes = [
      { text: "The future belongs to those who believe in the beauty of their dreams.", author: "Eleanor Roosevelt" },
      { text: "Success is not final, failure is not fatal: it is the courage to continue that counts.", author: "Winston Churchill" },
      { text: "Education is the most powerful weapon which you can use to change the world.", author: "Nelson Mandela" },
      { text: "The only way to do great work is to love what you do.", author: "Steve Jobs" },
      { text: "Believe you can and you're halfway there.", author: "Theodore Roosevelt" },
      { text: "Don't watch the clock; do what it does. Keep going.", author: "Sam Levenson" },
      { text: "The expert in anything was once a beginner.", author: "Helen Hayes" },
      { text: "Your limitation—it's only your imagination.", author: "Unknown" },
      { text: "Great things never come from comfort zones.", author: "Unknown" },
      { text: "Dream it. Wish it. Do it.", author: "Unknown" }
    ];

    function loadQuote() {
      const randomQuote = quotes[Math.floor(Math.random() * quotes.length)];
      box.innerHTML = `"${randomQuote.text}"<div class="mt-2 text-sm text-gray-500">— ${randomQuote.author}</div>`;
      box.classList.add('fade-in');
      
      if (gamification) {
        gamification.addPoints(5, 'Read a motivational quote');
      }
    }
    
    btn.addEventListener("click", loadQuote);
    loadQuote();
  }

  // Add fade-in animation to all cards
  const cards = document.querySelectorAll('.bg-white');
  cards.forEach((card, index) => {
    setTimeout(() => {
      card.classList.add('fade-in');
    }, index * 100);
  });

  // Job card interactions
  const jobCards = document.querySelectorAll('.job-card');
  jobCards.forEach(card => {
    card.addEventListener('click', function() {
      if (gamification) {
        gamification.addPoints(10, 'Viewed a job listing');
      }
    });
  });

  // Track login
  const loginCount = parseInt(localStorage.getItem('login_count')) || 0;
  localStorage.setItem('login_count', loginCount + 1);

  // Carousel fix for dashboard
  const carousel = document.getElementById('carousel');
  if (carousel) {
    const slides = carousel.children.length;
    let index = 0;

    const nextBtn = document.getElementById('next');
    const prevBtn = document.getElementById('prev');

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        index = (index + 1) % slides;
        carousel.style.transform = `translateX(-${index * 100}%)`;
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        index = (index - 1 + slides) % slides;
        carousel.style.transform = `translateX(-${index * 100}%)`;
      });
    }

    // Auto-scroll
    setInterval(() => {
      index = (index + 1) % slides;
      carousel.style.transform = `translateX(-${index * 100}%)`;
    }, 8000);
  }

  // Search functionality enhancement
  const searchForm = document.querySelector('form[action="search.php"]');
  if (searchForm) {
    searchForm.addEventListener('submit', function() {
      if (gamification) {
        gamification.addPoints(5, 'Performed a search');
      }
    });
  }

  // Add smooth scroll to top button
  const scrollToTopBtn = document.createElement('button');
  scrollToTopBtn.innerHTML = '↑';
  scrollToTopBtn.className = 'fixed bottom-8 right-8 bg-blue-600 text-white w-12 h-12 rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 opacity-0 pointer-events-none';
  scrollToTopBtn.style.zIndex = '9999';
  document.body.appendChild(scrollToTopBtn);

  window.addEventListener('scroll', () => {
    if (window.pageYOffset > 300) {
      scrollToTopBtn.style.opacity = '1';
      scrollToTopBtn.style.pointerEvents = 'auto';
    } else {
      scrollToTopBtn.style.opacity = '0';
      scrollToTopBtn.style.pointerEvents = 'none';
    }
  });

  scrollToTopBtn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  // Loading animation for job listings
  const jobListings = document.querySelectorAll('.job-card, .bg-white');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('fade-in');
      }
    });
  }, { threshold: 0.1 });

  jobListings.forEach(listing => {
    observer.observe(listing);
  });

  // Profile completion tracker
  const profileFields = document.querySelectorAll('input[type="text"], input[type="email"], textarea');
  if (profileFields.length > 0) {
    let filledFields = 0;
    profileFields.forEach(field => {
      if (field.value.trim() !== '') filledFields++;
    });
    
    const completionPercentage = Math.round((filledFields / profileFields.length) * 100);
    if (completionPercentage === 100 && gamification) {
      gamification.unlockAchievement('profile_complete');
    }
  }
});

// Add CSS for fadeOut animation
const style = document.createElement('style');
style.textContent = `
  @keyframes fadeOut {
    from {
      opacity: 1;
      transform: translateX(0);
    }
    to {
      opacity: 0;
      transform: translateX(100%);
    }
  }
`;
document.head.appendChild(style);

