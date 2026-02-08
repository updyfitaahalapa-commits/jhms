<?php
// seeker/browse_jobs.php
require_once '../includes/db_connect.php';
require_once 'header.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Build Query
$sql = "SELECT * FROM jobs WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (title LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($category_filter)) {
    $sql .= " AND category = :category";
    $params[':category'] = $category_filter;
}

$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$jobs = $stmt->fetchAll();

// Categories for filters
$categories = ['IT', 'Marketing', 'Finance', 'Engineering', 'Healthcare', 'Education', 'Customer Service', 'Operations', 'Other'];
?>

<div class="container-fluid py-4">
    <!-- Horizontal Filter Bar -->
    <div class="row mb-5 fade-in-up">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <form action="browse_jobs.php" method="GET" class="row g-0">
                        <div class="col-md-5 border-end">
                            <div class="input-group input-group-lg h-100">
                                <span class="input-group-text bg-white border-0 ps-4">
                                    <i class="fas fa-search text-muted opacity-50"></i>
                                </span>
                                <input type="text" class="form-control border-0 py-4 bg-white" id="search" name="search" placeholder="Job title, keywords, or company..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4 border-end">
                            <div class="input-group input-group-lg h-100">
                                <span class="input-group-text bg-white border-0 ps-4">
                                    <i class="fas fa-layer-group text-muted opacity-50"></i>
                                </span>
                                <select class="form-select border-0 py-4 bg-white" id="category" name="category">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat; ?>" <?php echo $category_filter == $cat ? 'selected' : ''; ?>><?php echo $cat; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 p-3 d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm hvr-grow">
                                Search Opportunities
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php if (!empty($search) || !empty($category_filter)): ?>
                <div class="mt-3 px-1">
                    <a href="browse_jobs.php" class="text-muted small text-decoration-none">
                        <i class="fas fa-circle-xmark me-1"></i> Clear all active filters
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Job Results Section -->
    <div class="row fade-in-up" style="animation-delay: 0.2s">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-end mb-5 px-1">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Available Roles</h2>
                    <p class="text-muted mb-0">Discover your next career milestone in Somalia.</p>
                </div>
                <span class="text-muted small fw-bold text-uppercase ls-wide border-bottom border-2 border-primary pb-1">
                    <?php echo count($jobs); ?> matches found
                </span>
            </div>

            <div class="row g-4">
                <?php if (count($jobs) > 0): ?>
                    <?php foreach ($jobs as $job): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 job-card border-0 shadow-sm hover-shadow-lg transition-all">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-4">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                            <i class="fas fa-briefcase fa-lg"></i>
                                        </div>
                                        <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill small fw-bold"><?php echo htmlspecialchars($job['category']); ?></span>
                                    </div>
                                    
                                    <h5 class="fw-bold text-dark mb-1 hvr-text-primary transition-all"><?php echo htmlspecialchars($job['title']); ?></h5>
                                    <p class="text-muted small mb-3">
                                        <i class="fas fa-location-dot me-1 text-accent opacity-75"></i> <?php echo htmlspecialchars($job['location']); ?>
                                    </p>
                                    
                                    <p class="text-secondary small mb-4 lh-base opacity-75">
                                        <?php echo substr(htmlspecialchars($job['description']), 0, 120) . (strlen($job['description']) > 120 ? '...' : ''); ?>
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top border-light">
                                        <div class="fw-bold text-dark small">
                                            <i class="fas fa-money-bill-wave text-success me-1"></i> <?php echo htmlspecialchars($job['salary_range']); ?>
                                        </div>
                                        <?php 
                                        $job_is_expired = strtotime($job['deadline']) < time();
                                        if ($job_is_expired): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border-0">Closed</span>
                                        <?php else: ?>
                                            <small class="text-accent fw-bold" title="Deadline: <?php echo date('M d, Y H:i', strtotime($job['deadline'])); ?>">
                                                <i class="far fa-clock me-1"></i> <?php echo date('M d', strtotime($job['deadline'])); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0 p-4 pt-0">
                                    <a href="view_job.php?id=<?php echo $job['job_id']; ?>" class="btn btn-outline-primary w-100 rounded-3 py-2 fw-bold">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="glass p-5 rounded-4 d-inline-block shadow-sm overflow-hidden position-relative">
                            <img src="https://illustrations.popsy.co/slate/abstract-searching.svg" width="200" alt="No results" class="opacity-50 mb-4 position-relative z-index-1">
                            <h4 class="fw-bold text-dark">No matches found</h4>
                            <p class="text-muted mb-4">We couldn't find any jobs matching your current search criteria.</p>
                            <a href="browse_jobs.php" class="btn btn-primary rounded-pill px-5 py-3 shadow">
                                <i class="fas fa-rotate-left me-2"></i>Reset All Filters
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
