<?php
include("./database/config.php");
include("./Traits/CrudOperationsTrait.php");

class JobListController
{
    use CrudOperationsTrait;

    public function index()
    {
        // جلب جميع الوظائف
        $jobList = $this->getRecord('job_postings');

        // تخزين $jobList في الـ Session
        $_SESSION['jobList'] = $jobList;

        header('Location: ./job-list.php');
        exit; // تأكد من الخروج بعد استخدام header لتجنب أي أخطاء إضافية
    }
}



$jobListController = new JobListController();
$jobListController->index();
?>
