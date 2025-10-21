<?php
require 'includes/header.php';

// BrighterMonday internships page
$url = "https://www.brightermonday.co.ke/jobs/internship-graduate";
$cache_file = __DIR__ . "/cache/jobs_cache.html";
$cache_time = 600; // 10 minutes

// Create cache folder if missing
if (!file_exists(__DIR__ . "/cache")) {
    mkdir(__DIR__ . "/cache");
}

// If we have a fresh cache, use it; else fetch new
if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_time)) {
    $html = file_get_contents($cache_file);
} else {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch);
    curl_close($ch);
    if ($html) {
        file_put_contents($cache_file, $html);
    }
}

$jobs = [];

if ($html) {
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    // Try to find job listings using various selectors
    $articles = $xpath->query("//article");
    
    if ($articles->length == 0) {
        // Fallback: try different selectors
        $articles = $xpath->query("//*[contains(@class, 'job-item')]");
    }

    foreach ($articles as $article) {
        // find the <a> inside
        $aTag = $xpath->query(".//a", $article)->item(0);
        $titleTag = $xpath->query(".//h3 | .//h2", $article)->item(0);
        $companyTag = $xpath->query(".//p", $article)->item(0);

        if ($aTag && $titleTag) {
            $link = $aTag->getAttribute("href");
            if (strpos($link, "http") !== 0) {
                $link = "https://www.brightermonday.co.ke" . $link;
            }

            $title = trim($titleTag->textContent);
            $company = $companyTag ? trim($companyTag->textContent) : "Company Not Listed";
            
            // Extract location if available
            $locationTag = $xpath->query(".//span[contains(@class, 'location')] | .//div[contains(@class, 'location')]", $article)->item(0);
            $location = $locationTag ? trim($locationTag->textContent) : "Kenya";

            $jobs[] = [
                "title" => $title,
                "company" => $company,
                "location" => $location,
                "link" => $link,
                "posted" => "Recently"
            ];
        }

        if (count($jobs) >= 12) break; // limit to 12 jobs
    }
}

// If scraping fails, use fallback static jobs
if (empty($jobs)) {
    $jobs = [
        [
            "title" => "Digital Marketing Intern",
            "company" => "Tech Startup Kenya",
            "location" => "Nairobi",
            "link" => "https://www.brightermonday.co.ke/jobs/internship-graduate",
            "posted" => "2 days ago"
        ],
        [
            "title" => "Software Developer Intern",
            "company" => "Innovation Hub",
            "location" => "Nairobi",
            "link" => "https://www.brightermonday.co.ke/jobs/internship-graduate",
            "posted" => "1 week ago"
        ],
        [
            "title" => "Business Analyst Intern",
            "company" => "Financial Services Ltd",
            "location" => "Mombasa",
            "link" => "https://www.brightermonday.co.ke/jobs/internship-graduate",
            "posted" => "3 days ago"
        ],
        [
            "title" => "UI/UX Designer Intern",
            "company" => "Creative Agency",
            "location" => "Nairobi",
            "link" => "https://www.brightermonday.co.ke/jobs/internship-graduate",
            "posted" => "5 days ago"
        ],
        [
            "title" => "Data Analyst Intern",
            "company" => "Analytics Firm",
            "location" => "Nairobi",
            "link" => "https://www.brightermonday.co.ke/jobs/internship-graduate",
            "posted" => "1 week ago"
        ],
        [
            "title" => "Human Resources Intern",
            "company" => "Corporate Solutions",
            "location" => "Mombasa",
            "link" => "https://www.brightermonday.co.ke/jobs/internship-graduate",
            "posted" => "4 days ago"
        ]
    ];
}

// Array of professional images to cycle through
$jobImages = [

    "assets/images/job1.jpg",
    "assets/images/job2.jpg",
    "assets/images/job4.jpg",
    "assets/images/job5.jpg",
    "assets/images/job6.jpg",
    "assets/images/job3.png",

];
?>



<style>
/* Professional styling for job listings */
.job-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 4rem 0;
    margin-bottom: 3rem;
}

.filter-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    padding: 2rem;
    margin-bottom: 2rem;
}

.job-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.job-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.job-card-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.job-card-content {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.job-badge {
    display: inline-block;
    background: #10b981;
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.job-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.job-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.job-meta svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

.job-cta {
    margin-top: auto;
    padding-top: 1rem;
}

.btn-primary {
    display: inline-block;
    width: 100%;
    text-align: center;
    background: #667eea;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-primary:hover {
    background: #5568d3;
    transform: scale(1.02);
}

.btn-secondary {
    background: #667eea;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #5568d3;
}

.tips-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    padding: 2.5rem;
    margin-top: 3rem;
}

.tip-card {
    display: flex;
    gap: 1.5rem;
    align-items: start;
}

.tip-icon {
    width: 48px;
    height: 48px;
    background: #ede9fe;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tip-icon svg {
    width: 24px;
    height: 24px;
    color: #667eea;
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.input-field {
    flex: 1;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.input-field:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.select-field {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.select-field:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}
</style>

<div class="job-hero">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">Live Internship & Graduate Opportunities</h1>
            <p class="text-white text-lg opacity-90">Fresh opportunities updated every 10 minutes from BrighterMonday</p>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4">
    <!-- Filter Section -->
    <div class="filter-section">
        <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center">
            <input type="text" id="searchJobs" placeholder="Search by title or company..." class="input-field">
            <select id="filterLocation" class="select-field">
                <option value="">All Locations</option>
                <option value="Nairobi">Nairobi</option>
                <option value="Mombasa">Mombasa</option>
                <option value="Kisumu">Kisumu</option>
                <option value="Remote">Remote</option>
            </select>
            <button onclick="refreshJobs()" class="btn-secondary whitespace-nowrap">
                <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Jobs Grid -->
    <div id="jobsContainer" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <?php if ($jobs): ?>
            <?php foreach ($jobs as $index => $job): 
                $imageIndex = $index % count($jobImages);
                $jobImage = $jobImages[$imageIndex];
            ?>
                <div class="job-card" data-title="<?= strtolower(htmlspecialchars($job['title'])) ?>" data-company="<?= strtolower(htmlspecialchars($job['company'])) ?>" data-location="<?= htmlspecialchars($job['location']) ?>">
                    <img src="<?= $jobImage ?>" alt="<?= htmlspecialchars($job['company']) ?>" class="job-card-image">
                    
                    <div class="job-card-content">
                        <?php if ($index < 3): ?>
                            <span class="job-badge">New</span>
                        <?php endif; ?>
                        
                        <h3 class="job-title">
                            <?= htmlspecialchars($job['title']) ?>
                        </h3>
                        
                        <div class="job-meta">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span><?= htmlspecialchars($job['company']) ?></span>
                        </div>
                        
                        <div class="job-meta">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span><?= htmlspecialchars($job['location']) ?></span>
                        </div>
                        
                        <div class="job-meta">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><?= htmlspecialchars($job['posted']) ?></span>
                        </div>
                        
                        <div class="job-cta">
                            <a href="<?= htmlspecialchars($job['link']) ?>" target="_blank" class="btn-primary">
                                View Details
                                <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-3 text-center bg-white p-10 rounded-lg shadow">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500 text-lg">No jobs found at the moment. Try again later or adjust your filters.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Job Application Tips -->
    <div class="tips-section">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Job Application Tips</h2>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="tip-card">
                <div class="tip-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2 text-gray-900">Tailor Your CV</h3>
                    <p class="text-gray-600">Customize your resume for each application to highlight relevant skills and experiences that match the job requirements.</p>
                </div>
            </div>
            
            <div class="tip-card">
                <div class="tip-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2 text-gray-900">Research the Company</h3>
                    <p class="text-gray-600">Learn about the company's mission, values, culture, and recent projects before applying to demonstrate genuine interest.</p>
                </div>
            </div>
            
            <div class="tip-card">
                <div class="tip-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2 text-gray-900">Write a Strong Cover Letter</h3>
                    <p class="text-gray-600">Explain why you're interested in the role and how your unique skills and experiences can contribute to the team's success.</p>
                </div>
            </div>
            
            <div class="tip-card">
                <div class="tip-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2 text-gray-900">Apply Early</h3>
                    <p class="text-gray-600">Submit your application as soon as possible to increase your chances of being noticed and considered by hiring managers.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Search and filter functionality
document.getElementById('searchJobs').addEventListener('input', filterJobs);
document.getElementById('filterLocation').addEventListener('change', filterJobs);

function filterJobs() {
    const searchTerm = document.getElementById('searchJobs').value.toLowerCase();
    const locationFilter = document.getElementById('filterLocation').value;
    const jobCards = document.querySelectorAll('.job-card');
    
    jobCards.forEach(card => {
        const title = card.getAttribute('data-title');
        const company = card.getAttribute('data-company');
        const location = card.getAttribute('data-location');
        
        const matchesSearch = title.includes(searchTerm) || company.includes(searchTerm);
        const matchesLocation = !locationFilter || location === locationFilter;
        
        if (matchesSearch && matchesLocation) {
            card.style.display = 'flex';
            card.classList.add('fade-in');
        } else {
            card.style.display = 'none';
        }
    });
}

function refreshJobs() {
    window.location.reload();
}
</script>

<?php require 'includes/footer.php'; ?>