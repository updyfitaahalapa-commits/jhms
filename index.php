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
    <link href="assets/css/style.css" rel="stylesheet">
</head>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-briefcase me-2"></i>JHMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <?php 
                        $dashboard_link = '#';
                        if ($_SESSION['role'] == 'admin') $dashboard_link = 'admin/dashboard.php';
                        elseif ($_SESSION['role'] == 'employer') $dashboard_link = 'employer/dashboard.php';
                        else $dashboard_link = 'seeker/dashboard.php';
                        ?>
                        <a class="nav-link" href="<?php echo $dashboard_link; ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white ms-2" href="register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Find Your Dream Job Today</h1>
        <p class="lead mb-5">Connecting the best talent with top employers.</p>
        
        <div class="row justify-content-center">
            <div class="col-md-10">
                <form action="seeker/browse_jobs.php" method="GET" class="hero-search-box">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating text-dark">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Job Title">
                                <label for="title">Job Title or Keyword</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating text-dark">
                                <select class="form-select" id="category" name="category">
                                    <option value="">All Categories</option>
                                    <option value="IT">IT & Software</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Engineering">Engineering</option>
                                </select>
                                <label for="category">Category</label>
                            </div>
                        </div>
                        <div class="col-md-4 d-grid">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-search me-2"></i>Search Jobs</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <?php if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'employer'): ?>
                <a href="employer/post_job.php" class="btn btn-outline-light mt-3"><i class="fas fa-plus-circle me-2"></i>Post a Job</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Recent Jobs Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Latest Job Openings</h2>
        
        <div class="row">
            <?php if (count($recent_jobs) > 0): ?>
                <?php foreach ($recent_jobs as $job): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 job-card border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title fw-bold text-primary mb-1"><?php echo htmlspecialchars($job['title']); ?></h5>
                                        <h6 class="card-subtitle text-muted mb-2"><i class="fas fa-building me-1"></i> <?php echo htmlspecialchars($job['employer_name']); ?></h6>
                                    </div>
                                    <span class="badge bg-light text-dark border">New</span>
                                </div>
                                <p class="card-text text-secondary small mb-3"><?php echo substr(htmlspecialchars($job['description']), 0, 100) . '...'; ?></p>
                                <div class="d-flex justify-content-between text-muted small mb-3">
                                    <span><i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($job['location']); ?></span>
                                    <span><i class="fas fa-money-bill-wave me-1"></i> <?php echo htmlspecialchars($job['salary_range']); ?></span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 pt-0">
                                <a href="seeker/view_job.php?id=<?php echo $job['job_id']; ?>" class="btn btn-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No active jobs found at the moment. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="seeker/browse_jobs.php" class="btn btn-dark btn-lg">View All Jobs</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <div class="mb-3">
            <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
        </div>
        <p class="mb-0">&copy; <?php echo date('Y'); ?> Online Job Hunting Management System. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
