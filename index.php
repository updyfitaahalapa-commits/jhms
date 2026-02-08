<?php
// index.php
require_once 'includes/db_connect.php';
session_start();

// Fetch 5 most recent active jobs
$current_time = date('Y-m-d H:i:s');
$sql = "SELECT j.*, u.full_name as employer_name 
        FROM jobs j 
        JOIN users u ON j.employer_id = u.user_id 
        WHERE j.deadline >= :current_time
        ORDER BY j.created_at DESC 
        LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':current_time', $current_time);
$stmt->execute();
$recent_jobs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Job Hunting Management System</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top py-3 shadow-none" style="background: #0f172a !important; border-bottom: 1px solid rgba(255,255,255,0.08);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <span class="bg-accent p-2 rounded-3 me-2"><i class="fas fa-briefcase text-white fa-xs"></i></span>
            <span class="fw-800 fs-3 tracking-tighter">JH<span class="text-accent-color">MS</span></span>
        </a>
        <style>.fw-800 { font-weight: 800; } .tracking-tighter { letter-spacing: -0.05em; } .text-accent-color { color: #818cf8; }</style>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="fas fa-bars-staggered text-white"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item"><a class="nav-link px-3 active fw-semibold" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link px-3 text-white-50 fw-medium" href="seeker/browse_jobs.php">Find Jobs</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link px-3 text-white-50 fw-medium" href="seeker/dashboard.php">Dashboard</a></li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-outline-danger btn-sm px-4 rounded-pill border-opacity-25" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link px-3 text-white-50 fw-medium" href="login.php">Login</a></li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-light btn-sm px-4 rounded-pill shadow-lg border-0 fw-bold text-dark" href="register.php">Get Started</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section d-flex align-items-center min-vh-100 position-relative" style="background: radial-gradient(circle at top right, #1E1B4B 0%, #0F172A 100%);">
    <div class="container position-relative z-2 py-5">
        <div class="row align-items-center">
            <div class="col-lg-7 text-start fade-in-up">
                <h1 class="display-3 fw-800 text-white mb-4 lh-sm">Somalia's Premier <br><span class="text-accent">Talent Ecosystem</span></h1>
                <p class="lead text-white-50 mb-5 fs-5 pe-lg-5">Bridging the gap between specialized talent and global industrial opportunities with precision matching.</p>
                
                <div class="glass p-3 rounded-4 shadow-2xl mb-5">
                    <form action="seeker/browse_jobs.php" method="GET" class="m-0">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <div class="input-group input-group-lg h-100">
                                    <span class="input-group-text bg-white border-0 ps-3 text-muted"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control border-0 bg-white" id="title" name="title" placeholder="Job title or company">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select form-select-lg border-0 bg-white border-start" id="category" name="category">
                                    <option value="">All Categories</option>
                                    <option value="IT">IT & Software</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Engineering">Engineering</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button type="submit" class="btn btn-accent btn-lg rounded-3 shadow-accent">Search Jobs</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-4 text-white-50">
                    <div class="d-flex -space-x-2">
                        <img src="https://ui-avatars.com/api/?name=User+1&background=random" class="rounded-circle border border-2 border-primary" width="32" height="32">
                        <img src="https://ui-avatars.com/api/?name=User+2&background=random" class="rounded-circle border border-2 border-primary" width="32" height="32" style="margin-left: -12px;">
                        <img src="https://ui-avatars.com/api/?name=User+3&background=random" class="rounded-circle border border-2 border-primary" width="32" height="32" style="margin-left: -12px;">
                    </div>
                    <span class="small">Trusted by <span class="text-white fw-bold">2,500+</span> Somali professionals</span>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block fade-in-up" style="animation-delay: 0.3s">
                <div class="position-relative">
                    <div class="position-absolute top-50 start-50 translate-middle bg-accent opacity-25 blur-3xl rounded-circle" style="width: 400px; height: 400px;"></div>
                    <img src="https://illustrations.popsy.co/white/abstract-art-6.svg" alt="Career growth" class="img-fluid position-relative z-1 animate-float">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Grid -->
<section class="position-relative z-3" style="margin-top: -50px;">
    <div class="container">
        <div class="glass p-5 rounded-4 shadow-xl border-white border-opacity-10 py-5">
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <h2 class="fw-extrabold text-dark h1 mb-1">10k+</h2>
                    <p class="text-muted small text-uppercase ls-wide m-0">Active Users</p>
                </div>
                <div class="col-md-3 border-start border-light">
                    <h2 class="fw-extrabold text-dark h1 mb-1">450+</h2>
                    <p class="text-muted small text-uppercase ls-wide m-0">Top Employers</p>
                </div>
                <div class="col-md-3 border-start border-light">
                    <h2 class="fw-extrabold text-dark h1 mb-1">1.2k</h2>
                    <p class="text-muted small text-uppercase ls-wide m-0">Monthly Hires</p>
                </div>
                <div class="col-md-3 border-start border-light">
                    <h2 class="fw-extrabold text-dark h1 mb-1">98%</h2>
                    <p class="text-muted small text-uppercase ls-wide m-0">Success Rate</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Top Categories -->
<section class="section-padding bg-soft">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary-soft text-accent px-4 py-2 rounded-pill mb-3 fw-bold">MARKET SECTORS</span>
            <h2 class="display-5 fw-bold text-dark mb-3">Popular Categories</h2>
            <p class="text-muted lead mx-auto w-50">Explore opportunities across high-growth industries in Somalia's evolving economy.</p>
        </div>
        
        <div class="row g-4 justify-content-center">
            <?php 
            $cats = [
                ['name' => 'IT & Software', 'icon' => 'fa-code', 'count' => '124'],
                ['name' => 'Banking & Finance', 'icon' => 'fa-building-columns', 'count' => '86'],
                ['name' => 'Healthcare', 'icon' => 'fa-stethoscope', 'count' => '54'],
                ['name' => 'Engineering', 'icon' => 'fa-gears', 'count' => '42'],
                ['name' => 'Education', 'icon' => 'fa-graduation-cap', 'count' => '39'],
                ['name' => 'Customer Support', 'icon' => 'fa-headset', 'count' => '67']
            ];
            foreach ($cats as $c): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="seeker/browse_jobs.php?category=<?php echo urlencode($c['name']); ?>" class="card h-100 cat-card text-center text-decoration-none">
                        <div class="card-body p-4">
                            <div class="bg-primary-soft p-3 rounded-circle d-inline-block mb-3 transition-all">
                                <i class="fas <?php echo $c['icon']; ?> fa-xl text-accent"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1 d-block"><?php echo $c['name']; ?></h6>
                            <small class="text-muted"><?php echo $c['count']; ?> Live Roles</small>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Recent Jobs Section -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-3">
            <div>
                <span class="text-accent fw-bold text-uppercase ls-wide small d-block mb-2">New Openings</span>
                <h2 class="display-6 fw-bold m-0">Latest Opportunities</h2>
            </div>
            <a href="seeker/browse_jobs.php" class="btn btn-link text-decoration-none fw-bold p-0 text-accent hvr-text-primary">
                Explore all jobs <i class="fas fa-arrow-right ms-2 fs-7"></i>
            </a>
        </div>
        
        <div class="row">
            <?php if (count($recent_jobs) > 0): ?>
                <?php foreach ($recent_jobs as $job): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all" style="background: var(--bg-soft); border: 1px solid var(--border-color) !important;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="bg-white rounded-3 shadow-sm p-3 mb-2">
                                        <i class="fas fa-building fa-xl text-accent opacity-75"></i>
                                    </div>
                                    <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill small fw-bold">Active</span>
                                </div>
                                
                                <h5 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($job['title']); ?></h5>
                                <p class="text-muted small mb-4 fw-medium"><?php echo htmlspecialchars($job['employer_name']); ?></p>
                                
                                <div class="row g-2 mb-4">
                                    <div class="col-6 text-start">
                                        <div class="bg-white border rounded-3 p-2 text-center text-muted small shadow-sm">
                                            <i class="fas fa-map-marker-alt me-1 text-accent"></i> <?php echo htmlspecialchars($job['location']); ?>
                                        </div>
                                    </div>
                                    <div class="col-6 text-start">
                                        <div class="bg-white border rounded-3 p-2 text-center text-muted small shadow-sm">
                                            <i class="fas fa-money-bill-wave me-1 text-success"></i> <?php echo htmlspecialchars($job['salary_range']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 p-4 pt-0">
                                <a href="seeker/view_job.php?id=<?php echo $job['job_id']; ?>" class="btn btn-primary w-100 fw-bold py-3">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="https://illustrations.popsy.co/slate/shaking-hands.svg" width="200" alt="No jobs" class="opacity-25 mb-4">
                    <p class="text-muted fs-5">No active jobs found at the moment. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- How it Works -->
<section class="section-padding bg-white">
    <div class="container text-center">
        <h2 class="display-6 fw-bold mb-5">How JHMS Works</h2>
        <div class="row g-5">
            <div class="col-md-4 px-4 overflow-hidden">
                <div class="bg-primary bg-opacity-10 p-4 rounded-4 d-inline-block mb-4">
                    <i class="fas fa-user-plus text-accent fa-2x"></i>
                </div>
                <h5 class="fw-bold">1. Professional Signup</h5>
                <p class="text-muted">Create your profile, upload your credentials, and build an AI-optimized resume in minutes.</p>
            </div>
            <div class="col-md-4 px-4 overflow-hidden border-start border-end border-light">
                <div class="bg-primary bg-opacity-10 p-4 rounded-4 d-inline-block mb-4">
                    <i class="fas fa-crosshairs text-accent fa-2x"></i>
                </div>
                <h5 class="fw-bold">2. Precision Matching</h5>
                <p class="text-muted">Our algorithms analyze your skill set to present the most relevant high-impact roles.</p>
            </div>
            <div class="col-md-4 px-4 overflow-hidden">
                <div class="bg-primary bg-opacity-10 p-4 rounded-4 d-inline-block mb-4">
                    <i class="fas fa-rocket text-accent fa-2x"></i>
                </div>
                <h5 class="fw-bold">3. Scale your Career</h5>
                <p class="text-muted">Apply effortlessly with one-click submissions and track your interview progress live.</p>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories -->
<section class="section-padding bg-soft position-relative overflow-hidden">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0">
                <span class="badge bg-primary-soft text-accent px-4 py-2 rounded-pill mb-4 border border-accent border-opacity-10 fw-bold">SUCCESS STORIES</span>
                <h2 class="display-5 fw-bold text-dark mb-4 lh-sm">Voices of Our <br><span class="text-accent">Thriving Community</span></h2>
                <p class="lead text-muted mb-4 fs-6">Join thousands of professionals who have found their breakthrough careers through Somalia's most trusted recruitment ecosystem.</p>
                <div class="d-flex align-items-center gap-3">
                    <a href="register.php" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-lg">JOIN THE COMMUNITY</a>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="row g-4">
                    <div class="col-md-6 fade-in-up">
                        <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
                            <div class="text-warning mb-3">
                                <i class="fas fa-star"></i><i class="fas fa-star mx-1"></i><i class="fas fa-star"></i><i class="fas fa-star mx-1"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="text-dark fw-medium mb-4 fs-6 italic">"The precision of matching on JHMS is unmatched. I secured a Senior Engineering role within weeks of signing up."</p>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Ali+Hassan&background=4F46E5&color=fff" class="rounded-circle me-3" width="48">
                                <div>
                                    <h6 class="mb-0 fw-bold">Ali Hassan</h6>
                                    <small class="text-muted">Senior Infrastructure Engineer</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 fade-in-up mt-md-4">
                        <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
                            <div class="text-warning mb-3">
                                <i class="fas fa-star"></i><i class="fas fa-star mx-1"></i><i class="fas fa-star"></i><i class="fas fa-star mx-1"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="text-dark fw-medium mb-4 fs-6 italic">"As an employer, finding verified talent used to be a challenge. JHMS has transformed our recruitment speed."</p>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Sarah+S&background=059669&color=fff" class="rounded-circle me-3" width="48">
                                <div>
                                    <h6 class="mb-0 fw-bold">Sarah S.</h6>
                                    <small class="text-muted">HR Director @ GlobalTech</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-primary text-white pt-5 pb-4" style="background: #0F172A !important;">
    <div class="container pt-5">
        <div class="row g-5">
            <div class="col-lg-4 text-start">
                <div class="d-flex align-items-center mb-4">
                    <span class="bg-accent p-2 rounded-3 me-2"><i class="fas fa-briefcase text-white fa-xs"></i></span>
                    <h3 class="fw-800 fs-3 tracking-tighter text-white m-0">JH<span class="text-indigo-400">MS</span></h3>
                </div>
                <style>.text-indigo-400 { color: #818cf8; }</style>
                <p class="text-white-50 lh-lg pe-lg-5 fw-medium">The digital bridge between specialized Somali talent and global industrial opportunities. 100% Secure, Verified, and Professional.</p>
                <div class="d-flex gap-3 mt-4">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-md-4 col-lg-2 ms-lg-auto text-start">
                <h6 class="fw-bold text-uppercase ls-wide fs-7 text-white mb-4">Platform</h6>
                <ul class="list-unstyled text-white-50 fw-medium">
                    <li class="mb-3"><a href="seeker/browse_jobs.php" class="text-reset text-decoration-none hover-white transition-all">Browse Jobs</a></li>
                    <li class="mb-3"><a href="register.php" class="text-reset text-decoration-none hover-white transition-all">Career Profile</a></li>
                    <li class="mb-3"><a href="#" class="text-reset text-decoration-none hover-white transition-all">Skill Tests</a></li>
                </ul>
            </div>
            <div class="col-md-4 col-lg-3 text-start">
                <h6 class="fw-bold text-uppercase ls-wide fs-7 text-white mb-4">Enterprise</h6>
                <ul class="list-unstyled text-white-50 fw-medium">
                    <li class="mb-3"><a href="employer/post_job.php" class="text-reset text-decoration-none hover-white transition-all">Post Opportunity</a></li>
                    <li class="mb-3"><a href="#" class="text-reset text-decoration-none hover-white transition-all">Talent Acquisition</a></li>
                    <li class="mb-3"><a href="#" class="text-reset text-decoration-none hover-white transition-all">Employer Branding</a></li>
                </ul>
            </div>
            <div class="col-md-4 col-lg-3 text-start">
                <h6 class="fw-bold text-uppercase ls-wide fs-7 text-white mb-4">Stay Contented</h6>
                <p class="small text-white-50 mb-4 fw-medium">Subscribe for weekly high-impact job alerts.</p>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white bg-opacity-10 border-0 text-white px-3 py-3 rounded-start" placeholder="Email...">
                    <button class="btn btn-accent px-3 py-3 rounded-end">Join</button>
                </div>
            </div>
        </div>
        <hr class="my-5 border-white border-opacity-10">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center opacity-75 small fw-medium">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> JHMS Somalia. All Rights Reserved.</p>
            <div class="d-flex gap-4 mt-3 mt-md-0">
                <a href="#" class="text-reset text-decoration-none">Privacy</a>
                <a href="#" class="text-reset text-decoration-none">Terms</a>
            </div>
        </div>
    </div>
</footer>
<style>.hover-white:hover { color: white !important; }</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
