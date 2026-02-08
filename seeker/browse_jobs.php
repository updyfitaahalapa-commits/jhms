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
?>

<div class="container-fluid">
    <!-- Horizontal Filter Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="browse_jobs.php" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="search" name="search" placeholder="Job Title" value="<?php echo htmlspecialchars($search); ?>">
                                <label for="search fw-bold"><i class="fas fa-search me-1 small"></i> Job Title / Keywords</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" id="category" name="category">
                                    <option value="">All Categories</option>
                                    <option value="IT" <?php echo $category_filter == 'IT' ? 'selected' : ''; ?>>IT & Software</option>
                                    <option value="Marketing" <?php echo $category_filter == 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                                    <option value="Finance" <?php echo $category_filter == 'Finance' ? 'selected' : ''; ?>>Finance</option>
                                    <option value="Engineering" <?php echo $category_filter == 'Engineering' ? 'selected' : ''; ?>>Engineering</option>
                                    <option value="Healthcare" <?php echo $category_filter == 'Healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                                    <option value="Education" <?php echo $category_filter == 'Education' ? 'selected' : ''; ?>>Education</option>
                                    <option value="Other" <?php echo $category_filter == 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                                <label for="category fw-bold"><i class="fas fa-tags me-1 small"></i> Select Category</label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="d-flex gap-2 h-100 align-items-stretch">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm flex-grow-1" style="max-height: 58px;">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                                <a href="browse_jobs.php" class="btn btn-outline-secondary d-flex align-items-center justify-content-center px-3" style="max-height: 58px;" title="Reset Filters">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Results Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                <h4 class="mb-0 fw-bold text-dark"><i class="fas fa-list-ul me-2 text-primary"></i>Job Listings</h4>
                <span class="badge bg-white text-secondary border shadow-sm p-2 px-3"><?php echo count($jobs); ?> jobs match your search</span>
            </div>

            <div class="row g-4">
                <?php if (count($jobs) > 0): ?>
                    <?php foreach ($jobs as $job): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 job-card border-0 bg-white text-start">
                                <div class="card-body w-100 d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="icon-box bg-light text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                        <span class="badge bg-light text-dark border align-self-start fa-sm"><?php echo htmlspecialchars($job['category']); ?></span>
                                    </div>
                                    
                                    <h5 class="card-title fw-bold text-dark mb-1"><?php echo htmlspecialchars($job['title']); ?></h5>
                                    <p class="card-text text-muted small mb-3"><i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($job['location']); ?></p>
                                    
                                    <p class="card-text text-secondary small mb-4">
                                        <?php echo substr(htmlspecialchars($job['description']), 0, 150) . (strlen($job['description']) > 150 ? '...' : ''); ?>
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                        <small class="text-muted fw-bold"><?php echo htmlspecialchars($job['salary_range']); ?></small>
                                        <?php 
                                        $job_is_expired = strtotime($job['deadline']) < time();
                                        if ($job_is_expired): ?>
                                            <span class="badge bg-danger">Waqtigu waa dhammaaday (Expired)</span>
                                        <?php else: ?>
                                            <small class="text-danger" title="Deadline: <?php echo date('M d, Y H:i', strtotime($job['deadline'])); ?>">
                                                <i class="far fa-clock me-1"></i> <?php echo date('M d, H:i', strtotime($job['deadline'])); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0 w-100 pb-3 pt-0">
                                    <a href="view_job.php?id=<?php echo $job['job_id']; ?>" class="btn btn-outline-primary w-100">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-light border text-center py-5 shadow-sm">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5>No jobs found</h5>
                            <p class="text-muted">Try adjusting your filters or search criteria.</p>
                            <a href="browse_jobs.php" class="btn btn-primary mt-2 px-4 shadow-sm">Clear Filters</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
