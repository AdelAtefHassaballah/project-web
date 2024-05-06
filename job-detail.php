<?php
session_start();
include("./database/config.php");
include("./Traits/CrudOperationsTrait.php");

class DatabaseOperations
{
    use CrudOperationsTrait;

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function findJobById($id)
    {
        $sql = "SELECT job_postings.*, 
        companies.name as company_name, companies.id as company_id, companies.location as company_location, companies.image as company_image, companies.category as company_category, companies.description as company_description,
        users.name as recruiter_name, 
        job_requirements.title as job_requirement_title, job_requirements.description as job_requirement_description
        FROM job_postings
        JOIN companies ON job_postings.company_id = companies.id
        JOIN users ON job_postings.user_id = users.id
        LEFT JOIN job_requirements ON job_postings.id = job_requirements.job_posting_id
        WHERE job_postings.id = $id";

        return $this->executeQuery($sql);
    }

    public function getCommentsByJobId($jobId)
    {
        $sql = "SELECT comments.*, users.name AS user_name 
                FROM comments 
                INNER JOIN users ON comments.user_id = users.id 
                WHERE job_posting_id = $jobId AND comments.status = 1 
                ORDER BY comments.created_at DESC";
        return $this->executeQuery($sql);
    }


    public function addComment($content, $jobPostingId, $userId)
    {
        $sql = "INSERT INTO comments (content, job_posting_id, user_id) VALUES ('$content', $jobPostingId, $userId)";
        return $this->executeQuery($sql);
    }
}

if (!isset($_REQUEST['id'])) {
    header('location: index.php');
    exit;
}

$id = $_GET['id'];
$databaseOperations = new DatabaseOperations($conn);
$jobData = $databaseOperations->findJobById($id);
$comments = $databaseOperations->getCommentsByJobId($id);

if (!$jobData) {
    header('location: index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    $content = $_POST['comment'];
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $result = $databaseOperations->addComment($content, $id, $userId);
    if ($result) {
        // Refresh the page or show success message
        header("Refresh:0");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Jobs Details</title>
    <?php require_once 'components/head.php'; ?>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <?php require_once 'components/spinner.php'; ?>
        <!-- Navbar Start -->
        <?php require_once 'components/navbar.php'; ?>


        <!-- Job Detail Start -->
        <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="row gy-5 gx-4">
                    <div class="col-lg-8">
                        <?php if ($jobData) : ?>
                            <?php foreach ($jobData as $job) : ?>
                                <div class="d-flex align-items-center mb-5">
                                    <img class="flex-shrink-0 img-fluid border rounded" src="<?php echo $job['image']; ?>" alt="" style="width: 80px; height: 80px;">
                                    <div class="text-start ps-4">
                                        <h3 class="mb-3"><?php echo $job['title']; ?></h3>
                                        <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i><?php echo $job['company_location']; ?></span>
                                        <span class="text-truncate me-3"><i class="far fa-clock text-primary me-2"></i><?php echo $job['type']; ?></span>
                                        <span class="text-truncate me-0"><i class="far fa-money-bill-alt text-primary me-2"></i>$<?php echo $job['salary']; ?></span>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <h4 class="mb-3">Job Description</h4>
                                    <p><?php echo $job['description']; ?></p>
                                    <h4 class="mb-3">Job Requirements</h4>
                                    <?php if (!empty($job['job_requirement_title'])) : ?>
                                        <?php foreach ($jobData as $requirement) : ?>
                                            <p class="fw-bold fs-5 text-primary"><?php echo $requirement['job_requirement_title']; ?></p>
                                            <p><?php echo $requirement['job_requirement_description']; ?></p>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p>No job requirements found.</p>
                                    <?php endif; ?>
                                </div>

                                <!-- Comment Section -->
                                <div class="mb-5">
                                    <h3 class="mb-3">Comments</h3>
                                    <button class="btn btn-primary" onclick="openCommentModal(<?php echo $job['id']; ?>)">Show</button>
                                </div>

                                <!-- comments Modal -->
                                <div class="modal" id="jobDetailsModal">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Comments</h4>
                                                <button type="button" class="close btn btn-outline-primary" data-bs-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <?php foreach ($comments as $comment) : ?>
                                                    <div class="mb-3">
                                                        <div class="comment d-flex gap-1">
                                                            <p class="comment-user text-primary"><em><?php echo $comment['user_name']; ?>:</em></p>
                                                            <p class="comment-content"><strong><?php echo $comment['content']; ?></strong></p>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="modal-footer d-block">
                                                <?php if (isset($_SESSION['user_id'])) { ?>
                                                    <!-- Comment Form -->
                                                    <form method="POST" action="">
                                                        <div class="mb-3">
                                                            <label for="comment" class="form-label">Add a Comment</label>
                                                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </form>
                                                <?php } else { ?>
                                                    <p class="text-muted">You must login to add a comment.</p>
                                                <?php } ?>
                                            </div>
                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No job data found.</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-4">
                        <div class="bg-light rounded p-5 mb-4 wow slideInUp" data-wow-delay="0.1s">
                            <h4 class="mb-4">Company Detail</h4>
                            <img class="flex-shrink-0 img-fluid border rounded mb-2" src="<?php echo $job['company_image']; ?>" alt="" style="width: 80px; height: 80px;">
                            <br>
                            <a class="m-0 " href="company-detail.php?id=<?php echo $job['company_id']; ?>"><span class="fw-bold text-primary">Name:</span> <?php echo $job['company_name']; ?></a>
                            <p class="m-0"><span class="fw-bold text-primary">Company Description:</span> <?php echo $job['company_description']; ?></p>
                            <p class="m-0"><span class="fw-bold text-primary">Company Location:</span> <?php echo $job['company_location']; ?></p>
                            <p class="m-0"><span class="fw-bold text-primary">Company Field:</span> <?php echo $job['company_category']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Start -->
        <?php require_once 'components/footer.php'; ?>
    </div>
    <?php require_once 'components/scripts.php'; ?>
</body>

</html>