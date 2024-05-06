<?php
include("./controller/JobDetailController.php");

class Job
{
    private $id;
    private $title;
    private $description;
    private $type;
    private $salary;
    private $companyId;
    private $companyName;
    private $companyLocation;
    private $companyCategory;
    private $companyDescription;
    private $recruiterName;
    private $requirements = [];

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->type = $data['type'];
        $this->salary = $data['salary'];
        $this->companyId = $data['company_id'];
        $this->companyName = $data['company_name'];
        $this->companyLocation = $data['company_location'];
        $this->companyCategory = $data['company_category'];
        $this->companyDescription = $data['company_description'];
        $this->recruiterName = $data['recruiter_name'];

        if (!empty($data['job_requirement_title'])) {
            $this->requirements[] = [
                'title' => $data['job_requirement_title'],
                'description' => $data['job_requirement_description']
            ];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSalary()
    {
        return $this->salary;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function getCompanyLocation()
    {
        return $this->companyLocation;
    }

    public function getCompanyCategory()
    {
        return $this->companyCategory;
    }

    public function getCompanyDescription()
    {
        return $this->companyDescription;
    }

    public function getRecruiterName()
    {
        return $this->recruiterName;
    }

    public function getRequirements()
    {
        return $this->requirements;
    }
}


class DatabaseOperations
{
    use CrudOperationsTrait;

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function findJob($id)
    {
        $jobData = $this->getJobData($id);

        if (!$jobData) {
            return null;
        }

        return new Job($jobData);
    }

    private function getJobData($id)
    {
        $sql = "SELECT job_postings.*, 
        companies.name as company_name, companies.id as company_id, companies.location as company_location, companies.category as company_category, companies.description as company_description,
        users.name as recruiter_name, 
        job_requirements.title as job_requirement_title, job_requirements.description as job_requirement_description
        FROM job_postings
        JOIN companies ON job_postings.company_id = companies.id
        JOIN users ON job_postings.user_id = users.id
        LEFT JOIN job_requirements ON job_postings.id = job_requirements.job_posting_id
        WHERE job_postings.id = $id";

        return $this->executeQuery($sql);
    }
}


?>
