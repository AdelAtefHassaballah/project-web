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

    public function findCompanyById($id)
    {
        $sql = "SELECT companies.*, 
        GROUP_CONCAT(DISTINCT social_links.name) as social_name,
        GROUP_CONCAT(DISTINCT social_links.url SEPARATOR '<br>') as social_link,
        GROUP_CONCAT(DISTINCT job_postings.title SEPARATOR '<br>') as job_titles,
        GROUP_CONCAT(DISTINCT job_postings.id SEPARATOR '<br>') as job_ids,
        users.name as owner_name, users.image as owner_image, users.email as owner_email
        FROM companies
        LEFT JOIN social_links ON companies.id = social_links.company_id
        LEFT JOIN job_postings ON companies.id = job_postings.company_id
        JOIN users ON companies.user_id = users.id
        WHERE companies.id = $id
        GROUP BY companies.id";
    

        return $this->executeQuery($sql);
    }
}

if (!isset($_GET['id'])) {
    header('location: index.php');
    exit;
}

$id = $_GET['id'];
$databaseOperations = new DatabaseOperations($conn);
$companyData = $databaseOperations->findCompanyById($id);

if (!$companyData) {
    header('location: index.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Company Details</title>
    <?php require_once 'components/head.php'; ?>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .social-icons a {
            font-size: 24px;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <?php require_once 'components/spinner.php'; ?>
        <!-- Navbar Start -->
        <?php require_once 'components/navbar.php'; ?>


        <!-- Company Detail Start -->
        <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="row gy-5 gx-4">
                    <div class="col-lg-8">
                        <?php if ($companyData) : ?>
                            <?php foreach ($companyData as $company) : ?>
                                <div class="w-100 my-5 d-flex align-items-center justify-content-center" style="max-height:400px">
                                    <img src="<?php echo $company['image']; ?>" alt="">
                                </div>
                                <div class="mb-5">
                                    <h3 class="mb-3"><?php echo $company['name']; ?></h3>
                                    <p><?php echo $company['description']; ?></p>
                                    <h4 class="mb-3">Social Links</h4>
                                    <div class="social-icons">
                                        <?php 
                                        $social_name = explode("<br>", $company['social_link']);
                                        $social_names = explode("<br>", $company['social_name']);
                                        foreach ($social_name as $key => $link) {
                                            echo '<a href="' . $link . '" target="_blank" rel="noopener noreferrer" class="fs-1"><i class="bi bi-' . $social_names[$key] . '"></i></a>';
                                        }
                                        ?>
                                    </div>
                                    <h4 class="mt-4 mb-3">Jobs Posted</h4>
                                    <?php 
                                    $job_titles = explode("<br>", $company['job_titles']);
                                    $job_ids = explode("<br>", $company['job_ids']);
                                    foreach ($job_titles as $key => $title) {
                                        echo '<div class="job-item p-2 mb-4">
                                            <div class="row g-4 justify-content-between">
                                                <div class="col-sm-12 col-md-6 d-flex align-items-center">
                                                    <div class="text-start ps-4">
                                                        <h5 class="mb-3">' . $title . '</h5>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                                                    <div class="d-flex w-100">
                                                        <a class="btn btn-primary btn-sm-hover w-100" href="job-detail.php?id=' . $job_ids[$key] . '">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                    }
                                    ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No company data found.</p>
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-4">
                        <div class="bg-light rounded p-5 mb-4 wow slideInUp" data-wow-delay="0.1s">
                            <h4 class="mb-4">Company Owner</h4>
                            <?php foreach ($companyData as $owner) : ?>
                                <div style="width: 170px; height:170px; border:2px solid;" class=" mb-2 rounded-circle border-primary overflow-hidden d-flex justify-content-center align-items-center">
                                <img class="img-fluid rounded-circle" src="<?php echo $owner['owner_image']; ?>" alt="<?php echo $owner['owner_name']; ?>"  style="width: 160px; height:160px; padding:2px;">
                                </div>
                                <p class="m-0"><span class="fw-bold text-primary">Name:</span> <?php echo $owner['owner_name']; ?></p>
                                <p class="m-0"><span class="fw-bold text-primary">Email:</span> <?php echo $owner['owner_email']; ?></p>
                                <ul class="list-unstyled mt-3 d-flex gap-2">
                                    <?php 
                                    $social_name = explode("<br>", $owner['social_link']);
                                    $social_names = explode("<br>", $owner['social_name']);
                                    foreach ($social_name as $key => $link) {
                                        echo '<li class="my-2"><a href="' . $link . '" target="_blank" rel="noopener noreferrer" class="fs-1"><i class="bi bi-' . $social_names[$key] . '"></i></a></li>';
                                    }
                                    ?>
                                </ul>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Company Detail End -->

        <!-- Footer Start -->
        <?php require_once 'components/footer.php'; ?>
    </div>
    <?php require_once 'components/scripts.php'; ?>
</body>

</html>
