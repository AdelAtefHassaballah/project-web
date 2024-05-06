<?php
// include("./database/config.php");

trait CrudOperationsTrait
{
    private $connection;

    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        global $conn; // Import $conn from config.php
        $this->connection = $conn;
    }

    /*
    |--------------------------------------------------------------------------
    | Create Record Function
    |--------------------------------------------------------------------------
    */
    public function createRecord($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_map(function ($value) {
            return "'" . $value . "'";
        }, array_values($data)));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        return $this->executeQuery($sql);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Record Function
    |--------------------------------------------------------------------------
    */
    public function getRecord($table, $condition = '')
    {
        $sql = "SELECT * FROM $table";
        if (!empty($condition)) {
            $sql .= " WHERE $condition";
        }
        return $this->executeQuery($sql);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Companies by Category
    |--------------------------------------------------------------------------
    */
    public function getByCategory($table, $column, $category)
    {
        $sql = "SELECT * FROM $table WHERE $column = '$category'";
        return $this->executeQuery($sql);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Record with Relations Function
    |--------------------------------------------------------------------------
    */
    public function getWithRelations($table)
    {
        switch ($table) {
            case 'jobs':
                return "SELECT job_postings.*, companies.name as company_name, users.name as recruiter_name
                        FROM job_postings
                        JOIN companies ON job_postings.company_id = companies.id
                        JOIN users ON job_postings.user_id = users.id";
                break;

            case 'users':
                return "SELECT users.*, user_details.specialization, user_details.education
                        FROM users
                        LEFT JOIN user_details ON users.user_details_id = user_details.id";
                break;

            case 'companies':
                return "SELECT companies.*, COUNT(job_postings.id) as total_jobs
                        FROM companies
                        LEFT JOIN job_postings ON companies.id = job_postings.company_id
                        GROUP BY companies.id";
                break;

            default:
                return null;
                break;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Find Record with Relations Function
    |--------------------------------------------------------------------------
    */
    public function findWithRelationsByID($table, $id)
    {
        switch ($table) {
            case 'jobs':
                return "SELECT job_postings.*, companies.name as company_name, users.name as recruiter_name, job_requirements.title as job_requirement_title, job_requirements.description as job_requirement_description
                    FROM job_postings
                    JOIN companies ON job_postings.company_id = companies.id
                    JOIN users ON job_postings.user_id = users.id
                    LEFT JOIN job_requirements ON job_postings.id = job_requirements.job_posting_id
                    WHERE job_postings.id = $id";
                break;

            case 'users':
                return "SELECT users.*, user_details.specialization, user_details.education
                    FROM users
                    LEFT JOIN user_details ON users.user_details_id = user_details.id
                    WHERE users.id = $id";
                break;

            case 'companies':
                return "SELECT companies.*, COUNT(job_postings.id) as total_jobs, users.name as recruiter_name, social_links.name as social_link_name, social_links.url as social_link_url
                    FROM companies
                    JOIN users ON companies.user_id = users.id
                    LEFT JOIN job_postings ON companies.id = job_postings.company_id
                    LEFT JOIN social_links ON companies.id = social_links.company_id
                    WHERE companies.id = $id
                    GROUP BY companies.id";
                break;

            default:
                return null;
        }
    }



    /*
    |--------------------------------------------------------------------------
    | Update Record Function
    |--------------------------------------------------------------------------
    */
    public function updateRecord($table, $data, $condition)
    {
        $set = implode(', ', array_map(function ($key, $value) {
            return "$key = '" . $value . "'";
        }, array_keys($data), array_values($data)));
        $sql = "UPDATE $table SET $set WHERE $condition";
        return $this->executeQuery($sql);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Record Function
    |--------------------------------------------------------------------------
    */
    public function deleteRecord($table, $condition)
    {
        $sql = "DELETE FROM $table WHERE $condition";
        return $this->executeQuery($sql);
    }

    /*
    |--------------------------------------------------------------------------
    | Execute SQL Query Function
    |--------------------------------------------------------------------------
    */
    private function executeQuery($sql)
    {
        $result = $this->connection->query($sql);
        if ($result === false) {
            return json_encode(['error' => $this->connection->error], 400);
        } else {
            return $result;
        }
    }
}
