-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2024 at 12:45 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job_seekers_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `role` enum('superAdmin','admin','editor') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('full time','part time') NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `job_posting_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `job_posting_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` enum('Web Development','UI/UX Design','App Development','Software Development','Database Administration','Network Engineering','Embedded Systems','IoT') DEFAULT NULL,
  `location` ENUM('Alexandria', 'Aswan', 'Asyut', 'Beheira', 'Beni Suef', 'Cairo', 'Dakahlia', 'Damietta', 'Faiyum', 'Gharbia', 'Giza', 'Ismailia', 'Kafr El Sheikh', 'Luxor', 'Matruh', 'Minya', 'Monufia', 'New Valley', 'North Sinai', 'Port Said', 'Qalyubia', 'Qena', 'Red Sea', 'Sharqia', 'Sohag', 'South Sinai', 'Suez') DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `description`, `image`, `category`, `location`, `email`, `phone`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Software Solutions Co.', 'Providing cutting-edge software solutions', NULL, 'Web Development', 'Alexandria, Exampleville', 'contact@softwaresolutions.com', '123456789', 3, '2024-05-05 10:38:14', '2024-05-05 10:38:14'),
(2, 'Web Experts Ltd.', 'Experts in web development and design', NULL, 'UI/UX Design', 'Beheira', 'contact@webexperts.com', '987654321', 1, '2024-05-05 10:38:14', '2024-05-05 10:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `job_postings`
--

CREATE TABLE `job_postings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `salary` double DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `type` enum('full time','part time') DEFAULT NULL,
  `category` enum('Web Development','UI/UX Design','App Development','Software Development','Database Administration','Network Engineering','Embedded Systems','IoT') DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_postings`
--

INSERT INTO `job_postings` (`id`, `title`, `description`, `salary`, `status`, `type`, `category`, `image`, `user_id`, `company_id`, `created_at`, `updated_at`) VALUES
(1, 'Junour Software Engineer', 'We are looking for an experienced software engineer to join our team.', 75000, 1, 'part time', 'Software Development', NULL, 1, 1, '2024-05-05 10:40:11', '2024-05-05 10:40:11'),
(2, 'Senior Software Engineer', 'We are looking for an experienced software engineer to join our team.', 75000, 1, 'full time', 'Software Development', NULL, 1, 1, '2024-05-05 10:40:11', '2024-05-05 10:40:11'),
(3, 'Web Developer', 'Join our team to work on exciting web development projects.', 60000, 1, 'full time', 'Web Development', NULL, 2, 2, '2024-05-05 10:40:11', '2024-05-05 10:40:11'),
(4, 'Data Analyst', 'We need a skilled data analyst to analyze our data and provide insights.', 65000, 1, 'full time', 'Database Administration', NULL, 3, 1, '2024-05-05 10:40:11', '2024-05-05 10:40:11'),
(5, 'Senior Software Engineer', 'We are looking for an experienced software engineer to join our team.', 75000, 1, 'full time', 'Software Development', NULL, 1, 1, '2024-05-05 10:42:00', '2024-05-05 10:42:00'),
(6, 'Web Developer', 'Join our team to work on exciting web development projects.', 60000, 1, 'full time', 'Web Development', NULL, 2, 2, '2024-05-05 10:42:00', '2024-05-05 10:42:00'),
(7, 'Data Analyst', 'We need a skilled data analyst to analyze our data and provide insights.', 65000, 1, 'part time', 'Database Administration', NULL, 3, 1, '2024-05-05 10:42:00', '2024-05-05 10:42:00');

-- --------------------------------------------------------

--
-- Table structure for table `job_requirements`
--

CREATE TABLE `job_requirements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `job_posting_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_requirements`
--

INSERT INTO `job_requirements` (`id`, `title`, `description`, `job_posting_id`, `created_at`, `updated_at`) VALUES
(2, 'Bachelor\'s Degree in Computer Science', 'A Bachelor\'s degree in Computer Science or related field is required.', 2, '2024-05-05 10:40:36', '2024-05-05 10:40:36'),
(3, '3+ Years of Experience', 'Minimum 3 years of experience in software development.', 2, '2024-05-05 10:40:36', '2024-05-05 10:40:36'),
(4, 'Proficiency in Java and Python', 'Strong proficiency in Java and Python programming languages.', 2, '2024-05-05 10:40:36', '2024-05-05 10:40:36'),
(5, 'Experience with Web Development Frameworks', 'Experience with frameworks like Django or Spring is a plus.', 3, '2024-05-05 10:40:36', '2024-05-05 10:40:36'),
(6, 'Strong Analytical Skills', 'Ability to analyze complex data sets and derive meaningful insights.', 4, '2024-05-05 10:40:36', '2024-05-05 10:40:36');

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

CREATE TABLE `social_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` ENUM('facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'snapchat', 'whatsapp', 'tiktok', 'pinterest') NOT NULL,
  `url` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('recruiter','employee') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `user_details_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `image`, `phone`, `user_details_id`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'john.doe@example.com', 'johnspassword', 'employee', 1, NULL, '123456789', 1, '2024-05-05 10:35:42', '2024-05-05 10:35:42'),
(2, 'Jane Smith', 'jane.smith@example.com', 'janespassword', 'recruiter', 1, NULL, '987654321', 2, '2024-05-05 10:35:42', '2024-05-05 10:35:42'),
(3, 'Michael Johnson', 'michael.johnson@example.com', 'michaelspassword', 'employee', 1, NULL, '555555555', 3, '2024-05-05 10:35:42', '2024-05-05 10:35:42');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `location` ENUM('Alexandria', 'Aswan', 'Asyut', 'Beheira', 'Beni Suef', 'Cairo', 'Dakahlia', 'Damietta', 'Faiyum', 'Gharbia', 'Giza', 'Ismailia', 'Kafr El Sheikh', 'Luxor', 'Matruh', 'Minya', 'Monufia', 'New Valley', 'North Sinai', 'Port Said', 'Qalyubia', 'Qena', 'Red Sea', 'Sharqia', 'Sohag', 'South Sinai', 'Suez') DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `specialization`, `location`, `education`, `cv`, `created_at`, `updated_at`) VALUES
(1, 'Software Engineer', 'Cairo', 'Bachelor of Science in Computer Science', 'cv_file1.pdf', '2024-05-05 10:34:13', '2024-05-05 10:34:13'),
(2, 'Network Engineer', 'Alexandria', 'Bachelor of Science in Network Engineering', 'cv_file2.pdf', '2024-05-05 10:34:13', '2024-05-05 10:34:13'),
(3, 'Data Scientist', 'Giza', 'Master of Science in Data Science', 'cv_file3.pdf', '2024-05-05 10:34:13', '2024-05-05 10:34:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applications_user_id_foreign` (`user_id`),
  ADD KEY `applications_job_posting_id_foreign` (`job_posting_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_job_posting_id_foreign` (`job_posting_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companies_user_id_foreign` (`user_id`);

--
-- Indexes for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_postings_user_id_foreign` (`user_id`),
  ADD KEY `job_postings_company_id_foreign` (`company_id`);

--
-- Indexes for table `job_requirements`
--
ALTER TABLE `job_requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_requirements_job_posting_id_foreign` (`job_posting_id`);

--
-- Indexes for table `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `social_links_user_id_foreign` (`user_id`),
  ADD KEY `social_links_company_id_foreign` (`company_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_user_details_id_unique` (`user_details_id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `job_postings`
--
ALTER TABLE `job_postings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `job_requirements`
--
ALTER TABLE `job_requirements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `social_links`
--
ALTER TABLE `social_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_job_posting_id_foreign` FOREIGN KEY (`job_posting_id`) REFERENCES `job_postings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_job_posting_id_foreign` FOREIGN KEY (`job_posting_id`) REFERENCES `job_postings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD CONSTRAINT `job_postings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_postings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_requirements`
--
ALTER TABLE `job_requirements`
  ADD CONSTRAINT `job_requirements_job_posting_id_foreign` FOREIGN KEY (`job_posting_id`) REFERENCES `job_postings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `social_links`
--
ALTER TABLE `social_links`
  ADD CONSTRAINT `social_links_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `social_links_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_user_details_id_foreign` FOREIGN KEY (`user_details_id`) REFERENCES `user_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
