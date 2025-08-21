-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2024 at 04:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loxion`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `AnswerID` int(11) NOT NULL,
  `QuestionID` int(11) NOT NULL,
  `AnswerText` text NOT NULL,
  `IsCorrect` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`AnswerID`, `QuestionID`, `AnswerText`, `IsCorrect`) VALUES
(45, 12, 'Political map', 0),
(46, 12, 'Thematic map', 0),
(47, 12, 'Road map', 0),
(48, 12, 'Physical map', 1),
(49, 13, 'Political map', 0),
(50, 13, 'Thematic map', 1),
(51, 13, 'Road map', 0),
(52, 13, 'Physical map', 0);

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `AssignmentID` int(11) NOT NULL,
  `StartDate` date NOT NULL,
  `DueDate` date NOT NULL,
  `FilePath` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Grade` int(11) DEFAULT NULL,
  `StreamID` int(11) DEFAULT NULL,
  `SubjectStreamID` int(11) DEFAULT NULL,
  `SubjectID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`AssignmentID`, `StartDate`, `DueDate`, `FilePath`, `status`, `created_at`, `Grade`, `StreamID`, `SubjectStreamID`, `SubjectID`) VALUES
(22, '2024-11-06', '2024-11-06', 'C:\\xampp\\htdocs\\Loxion\\Admin/uploads/Matome-Michael-402201784.pdf', 'active', '2024-11-06 07:31:08', 8, NULL, NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `AttendanceID` int(11) NOT NULL,
  `TeacherID` int(11) DEFAULT NULL,
  `ClassDate` datetime DEFAULT NULL,
  `Status` enum('Sent','Completed') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`AttendanceID`, `TeacherID`, `ClassDate`, `Status`) VALUES
(25, 16, '2024-10-26 12:18:00', NULL),
(26, 16, '2024-10-26 16:42:00', NULL),
(27, 16, '2024-11-05 15:05:00', NULL),
(28, 16, '2024-11-06 11:14:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attendance_confirmation`
--

CREATE TABLE `attendance_confirmation` (
  `ConfirmationID` int(11) NOT NULL,
  `RegisterID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `Latitude` double DEFAULT NULL,
  `Longitude` double DEFAULT NULL,
  `ConfirmedStatus` varchar(20) NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_confirmation`
--

INSERT INTO `attendance_confirmation` (`ConfirmationID`, `RegisterID`, `StudentID`, `Latitude`, `Longitude`, `ConfirmedStatus`, `CreatedAt`) VALUES
(13, 25, 17, -23.916635286932063, 29.457479632613165, 'Absent', '2024-10-26 12:20:05'),
(14, 27, 17, -23.908656952033205, 29.45386279025732, 'Absent', '2024-11-05 15:08:45'),
(15, 28, 17, -23.908619977240754, 29.45390739186269, '', '2024-11-06 11:20:47');

-- --------------------------------------------------------

--
-- Table structure for table `behavior_reports`
--

CREATE TABLE `behavior_reports` (
  `ReportID` int(11) NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `ReportDate` date DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `ReportedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `behavior_reports`
--

INSERT INTO `behavior_reports` (`ReportID`, `StudentID`, `ReportDate`, `Description`, `ReportedBy`) VALUES
(8, 17, '2024-10-26', 'didnt come to school!!!!!!!', 18),
(9, 17, '2024-11-06', 'disresgjghhkmm', 18);

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `MessageID` int(11) NOT NULL,
  `SenderID` int(11) DEFAULT NULL,
  `ReceiverID` int(11) DEFAULT NULL,
  `Message` text DEFAULT NULL,
  `SentAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`MessageID`, `SenderID`, `ReceiverID`, `Message`, `SentAt`) VALUES
(7, 17, 0, 'hi', '2024-10-14 13:28:27'),
(8, 17, 14, 'hi', '2024-10-14 13:28:43'),
(9, 16, 18, 'afternoon', '2024-10-14 13:36:28'),
(10, 18, 16, 'hi', '2024-10-14 13:37:32'),
(11, 16, 0, 'Morning', '2024-10-18 09:13:12'),
(12, 16, 0, 'Morning', '2024-10-18 09:14:34'),
(13, 16, 0, 'Morning', '2024-10-18 09:14:50'),
(14, 16, 0, 'Morning', '2024-10-18 09:15:30'),
(15, 16, 0, 'Morning', '2024-10-18 09:15:45'),
(16, 16, 15, 'hy', '2024-10-18 09:15:50'),
(17, 16, 0, 'hey', '2024-10-18 09:18:59'),
(18, 16, 0, 'hey', '2024-10-18 09:19:22'),
(19, 15, 16, 'morning', '2024-10-26 01:58:13'),
(20, 15, 16, 'morning', '2024-10-26 01:59:00'),
(21, 15, 16, 'morning', '2024-10-26 01:59:05'),
(22, 15, 16, 'morning', '2024-10-26 01:59:11'),
(23, 15, 16, 'morning', '2024-10-26 01:59:37'),
(24, 15, 16, 'morning', '2024-10-26 02:03:35'),
(25, 15, 16, 'hi', '2024-10-26 02:04:21'),
(26, 15, 16, 'hi', '2024-10-26 02:04:29'),
(27, 15, 16, 'hi', '2024-10-26 02:04:35'),
(28, 15, 16, 'morning', '2024-10-26 02:07:41'),
(29, 15, 16, 'morning', '2024-10-26 02:07:46'),
(30, 18, 16, 'richfield', '2024-11-06 09:41:13'),
(31, 16, 0, 'heyy', '2024-11-06 09:42:07'),
(32, 15, 18, 'hy', '2024-11-08 06:49:42'),
(33, 16, 0, 'morning', '2024-11-08 06:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `EventID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`EventID`, `Title`, `Description`, `StartDate`, `EndDate`, `Location`, `CreatedBy`) VALUES
(8, 'Holidays', 'le skatla skolong', '2024-10-18', '2024-10-21', 'Polokwane High School', 18),
(9, 'Holidays', 'le skatla skolong', '2024-10-18', '2024-10-21', 'Polokwane High School', 18),
(10, 'trip', 'school trip', '2024-10-26', '2024-10-26', 'polokwane', 18),
(11, 'presentation', 'project', '2024-11-06', '2024-11-07', 'richfield', 18);

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `GradeID` int(11) NOT NULL,
  `GradeNumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grade_subjects`
--

CREATE TABLE `grade_subjects` (
  `GradeID` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `MessageID` int(11) NOT NULL,
  `GroupID` int(11) DEFAULT NULL,
  `SenderID` int(11) DEFAULT NULL,
  `MessageText` text DEFAULT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `NotificationID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Message` varchar(255) NOT NULL,
  `NotificationType` enum('Assignment','Result','Attendance','General','Communication','Profile') NOT NULL,
  `DateSent` datetime DEFAULT current_timestamp(),
  `IsRead` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`NotificationID`, `UserID`, `Message`, `NotificationType`, `DateSent`, `IsRead`) VALUES
(1, 16, 'New Message: \'morning\' received on \'2024-10-26 03:58:13\'.', '', '2024-10-26 07:22:15', 0),
(2, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:00\'.', '', '2024-10-26 07:22:15', 0),
(3, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:05\'.', '', '2024-10-26 07:22:15', 0),
(4, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:11\'.', '', '2024-10-26 07:22:15', 0),
(5, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:37\'.', '', '2024-10-26 07:22:15', 0),
(6, 16, 'New Message: \'morning\' received on \'2024-10-26 04:03:35\'.', '', '2024-10-26 07:22:15', 0),
(7, 16, 'New Message: \'hi\' received on \'2024-10-26 04:04:21\'.', '', '2024-10-26 07:22:15', 0),
(8, 16, 'New Message: \'hi\' received on \'2024-10-26 04:04:29\'.', '', '2024-10-26 07:22:15', 0),
(9, 16, 'New Message: \'hi\' received on \'2024-10-26 04:04:35\'.', '', '2024-10-26 07:22:15', 0),
(10, 16, 'New Message: \'morning\' received on \'2024-10-26 04:07:41\'.', '', '2024-10-26 07:22:15', 0),
(11, 16, 'New Message: \'morning\' received on \'2024-10-26 04:07:46\'.', '', '2024-10-26 07:22:15', 0),
(12, 16, 'New Message: \'morning\' received on \'2024-10-26 03:58:13\'.', '', '2024-10-26 07:22:16', 0),
(13, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:00\'.', '', '2024-10-26 07:22:16', 0),
(14, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:05\'.', '', '2024-10-26 07:22:16', 0),
(15, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:11\'.', '', '2024-10-26 07:22:16', 0),
(16, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:37\'.', '', '2024-10-26 07:22:16', 0),
(17, 16, 'New Message: \'morning\' received on \'2024-10-26 04:03:35\'.', '', '2024-10-26 07:22:16', 0),
(18, 16, 'New Message: \'hi\' received on \'2024-10-26 04:04:21\'.', '', '2024-10-26 07:22:16', 0),
(19, 16, 'New Message: \'hi\' received on \'2024-10-26 04:04:29\'.', '', '2024-10-26 07:22:16', 0),
(20, 16, 'New Message: \'hi\' received on \'2024-10-26 04:04:35\'.', '', '2024-10-26 07:22:16', 0),
(21, 16, 'New Message: \'morning\' received on \'2024-10-26 04:07:41\'.', '', '2024-10-26 07:22:16', 0),
(22, 16, 'New Message: \'morning\' received on \'2024-10-26 04:07:46\'.', '', '2024-10-26 07:22:16', 0),
(23, 16, 'New Message: \'morning\' received on \'2024-10-26 03:58:13\'.', '', '2024-10-26 07:22:28', 0),
(24, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:00\'.', '', '2024-10-26 07:22:28', 0),
(25, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:05\'.', '', '2024-10-26 07:22:28', 0),
(26, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:11\'.', '', '2024-10-26 07:22:28', 0),
(27, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:37\'.', '', '2024-10-26 07:22:28', 0),
(28, 16, 'New Message: \'morning\' received on \'2024-10-26 04:03:35\'.', '', '2024-10-26 07:22:28', 0),
(29, 16, 'New Message: \'hi\' received on \'2024-10-26 04:04:21\'.', '', '2024-10-26 07:22:28', 0),
(30, 16, 'New Message: \'hi\' received on \'2024-10-26 04:04:29\'.', '', '2024-10-26 07:22:28', 0),
(31, 16, 'New Message: \'hi\' received on \'2024-10-26 04:04:35\'.', '', '2024-10-26 07:22:28', 0),
(32, 16, 'New Message: \'morning\' received on \'2024-10-26 04:07:41\'.', '', '2024-10-26 07:22:28', 0),
(33, 16, 'New Message: \'morning\' received on \'2024-10-26 04:07:46\'.', '', '2024-10-26 07:22:28', 0),
(34, 16, 'New Message: \'morning\' received on \'2024-10-26 03:58:13\'.', '', '2024-10-26 07:22:33', 0),
(35, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:00\'.', '', '2024-10-26 07:22:33', 0),
(36, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:05\'.', '', '2024-10-26 07:22:33', 0),
(37, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:11\'.', '', '2024-10-26 07:22:33', 0),
(38, 16, 'New Message: \'morning\' received on \'2024-10-26 03:59:37\'.', '', '2024-10-26 07:22:33', 0),
(39, 16, 'New Message: \'morning\' received on \'2024-10-26 04:03:35\'.', '', '2024-10-26 07:22:33', 0),
(40, 16, 'New Message: \'hi\' received on \'2024-10-26 04:04:21\'.', '', '2024-10-26 07:22:33', 0),
(41, 16, 'New Message: \'hi\' received on \'2024-10-26 04:04:29\'.', '', '2024-10-26 07:22:33', 0),
(42, 16, 'New Message: \'hi\' received on \'2024-10-26 04:04:35\'.', '', '2024-10-26 07:22:33', 0),
(43, 16, 'New Message: \'morning\' received on \'2024-10-26 04:07:41\'.', '', '2024-10-26 07:22:33', 0),
(44, 16, 'New Message: \'morning\' received on \'2024-10-26 04:07:46\'.', '', '2024-10-26 07:22:33', 0);

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `ParentID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`ParentID`, `UserID`, `FirstName`, `LastName`) VALUES
(1, 15, 'parent', 'parent'),
(2, 21, 'Nicole', 'Mohlala');

-- --------------------------------------------------------

--
-- Table structure for table `parent_student`
--

CREATE TABLE `parent_student` (
  `ParentID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent_student`
--

INSERT INTO `parent_student` (`ParentID`, `StudentID`) VALUES
(15, 17);

-- --------------------------------------------------------

--
-- Table structure for table `pre_registration_tokens`
--

CREATE TABLE `pre_registration_tokens` (
  `TokenID` int(11) NOT NULL,
  `Token` varchar(255) NOT NULL,
  `Role` enum('student','parent','teacher','admin') NOT NULL,
  `Grade` int(11) DEFAULT NULL,
  `Classroom` varchar(10) DEFAULT NULL,
  `UsedByUserID` int(11) DEFAULT NULL,
  `UsedOn` timestamp NULL DEFAULT NULL,
  `GeneratedOn` timestamp NOT NULL DEFAULT current_timestamp(),
  `ExpiresOn` timestamp NULL DEFAULT NULL,
  `is_used` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pre_registration_tokens`
--

INSERT INTO `pre_registration_tokens` (`TokenID`, `Token`, `Role`, `Grade`, `Classroom`, `UsedByUserID`, `UsedOn`, `GeneratedOn`, `ExpiresOn`, `is_used`) VALUES
(15, 'b8ddf8', 'parent', NULL, NULL, 15, '2024-10-12 14:02:43', '2024-10-12 14:00:49', '2024-12-19 21:59:59', 1),
(16, 'a8d1ea', 'teacher', NULL, NULL, 16, '2024-10-12 14:06:05', '2024-10-12 14:04:24', '2024-12-19 21:59:59', 1),
(17, 'ee2e3a', 'student', 9, 'B', 17, '2024-10-12 14:10:37', '2024-10-12 14:09:35', '2024-12-19 21:59:59', 1),
(18, '869332', 'admin', NULL, NULL, 18, '2024-10-12 16:12:25', '2024-10-12 16:11:22', '2024-12-19 21:59:59', 1),
(21, '916158', 'student', 8, 'D', NULL, NULL, '2024-10-26 07:59:13', '2024-12-19 21:59:59', 0),
(22, '99d9bf', 'parent', NULL, NULL, NULL, NULL, '2024-10-26 08:01:59', '2024-12-19 21:59:59', 0),
(23, '8bb14e', 'admin', NULL, NULL, NULL, NULL, '2024-10-26 08:03:08', '2024-12-19 21:59:59', 0),
(24, 'ee7729', 'parent', NULL, NULL, NULL, NULL, '2024-10-26 08:03:34', '2024-12-19 21:59:59', 0),
(25, '6e9eaa', 'parent', NULL, NULL, 21, '2024-11-06 09:30:32', '2024-11-06 09:29:38', '2024-12-19 21:59:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `QuestionID` int(11) NOT NULL,
  `QuizID` int(11) NOT NULL,
  `QuestionText` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`QuestionID`, `QuizID`, `QuestionText`) VALUES
(12, 10, 'Which type of map shows natural features such as mountains, rivers, and lakes?'),
(13, 11, 'Which type of map shows natural features such as mountains, rivers, and lakes?');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `QuizID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `SubjectID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`QuizID`, `UserID`, `SubjectID`, `Title`, `Description`, `CreatedAt`) VALUES
(10, 16, 4, 'Geography Knowledge Check', 'understand', '2024-11-06 08:07:54'),
(11, 16, 4, 'Geography Knowledge Check', 'cgfgh', '2024-11-06 11:17:26');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `ResultID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `QuizID` int(11) NOT NULL,
  `Score` int(11) NOT NULL,
  `TotalQuestions` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_results`
--

INSERT INTO `quiz_results` (`ResultID`, `StudentID`, `QuizID`, `Score`, `TotalQuestions`, `created_at`) VALUES
(11, 17, 10, 0, 1, '2024-11-06 06:15:41'),
(12, 17, 10, 1, 1, '2024-11-06 09:21:34');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `grade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `title`, `link`, `description`, `created_at`, `grade`) VALUES
(5, 'Mathematics', '../Teacher_or_Lecture/resources/grades (8).pdf', 'grades (8).pdf', '2024-11-06 05:49:42', 8),
(6, 'Mathematics', '../Teacher_or_Lecture/resources/grades (10).pdf', 'grades (10).pdf', '2024-11-06 09:19:12', 9);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `ResultID` int(11) NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `Grade` int(11) DEFAULT NULL,
  `Term` int(11) DEFAULT NULL,
  `Subject` varchar(100) DEFAULT NULL,
  `Mark` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`ResultID`, `StudentID`, `Grade`, `Term`, `Subject`, `Mark`) VALUES
(14, 5, 9, 1, 'Sepedi', 0),
(18, 17, 8, 1, 'Mathematics', 76),
(19, 17, 8, 1, 'Creative Arts', 0);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `day` date NOT NULL,
  `time` time NOT NULL,
  `subject` varchar(255) NOT NULL,
  `grade` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `day`, `time`, `subject`, `grade`) VALUES
(12, '2024-10-14', '10:00:00', 'Mathematics', 'Grade 9'),
(13, '2024-10-21', '13:00:00', 'Geography', 'Grade 8'),
(14, '2024-10-31', '10:04:00', 'Creative Arts', 'Grade 8'),
(15, '2024-11-06', '00:30:00', 'Sepedi', 'Grade 8'),
(16, '2024-11-06', '02:38:00', 'Natural Sciences', 'Grade 8');

-- --------------------------------------------------------

--
-- Table structure for table `streams`
--

CREATE TABLE `streams` (
  `StreamID` int(11) NOT NULL,
  `StreamName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `streams`
--

INSERT INTO `streams` (`StreamID`, `StreamName`) VALUES
(2, 'Science(Geography)'),
(3, 'Science(Agricultural science)'),
(4, 'Engineering'),
(40, 'CAT');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `StudentID` int(11) NOT NULL,
  `FirstName` varchar(100) DEFAULT NULL,
  `LastName` varchar(100) DEFAULT NULL,
  `Grade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`StudentID`, `FirstName`, `LastName`, `Grade`) VALUES
(17, 'Kamogelo', 'kamogelo@gmail.com', 8);

-- --------------------------------------------------------

--
-- Table structure for table `studentsubjects`
--

CREATE TABLE `studentsubjects` (
  `StudentID` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

CREATE TABLE `student_attendance` (
  `AttendanceID` int(11) NOT NULL,
  `RegisterID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `Status` enum('Present','Absent') NOT NULL,
  `ResponseTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_streams`
--

CREATE TABLE `student_streams` (
  `StudentID` int(11) NOT NULL,
  `StreamID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_streams`
--

INSERT INTO `student_streams` (`StudentID`, `StreamID`) VALUES
(7, 2),
(19, 40);

-- --------------------------------------------------------

--
-- Table structure for table `student_tasks`
--

CREATE TABLE `student_tasks` (
  `TaskID` int(11) NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `TaskDate` date DEFAULT NULL,
  `TaskDescription` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_tasks`
--

INSERT INTO `student_tasks` (`TaskID`, `StudentID`, `TaskDate`, `TaskDescription`, `created_at`) VALUES
(1, 17, '2024-11-01', 'exam', '2024-11-01 08:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `subjectdetails`
--

CREATE TABLE `subjectdetails` (
  `SubjectName` varchar(255) NOT NULL,
  `Instructions` text DEFAULT NULL,
  `AssignmentFile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `SubjectID` int(11) NOT NULL,
  `SubjectName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`SubjectID`, `SubjectName`) VALUES
(1, 'Mathematics'),
(2, 'Creative Arts'),
(3, 'Technology'),
(4, 'Geography'),
(5, 'History'),
(6, 'Natural Sciences'),
(7, 'English'),
(8, 'Sepedi');

-- --------------------------------------------------------

--
-- Table structure for table `subject_streams`
--

CREATE TABLE `subject_streams` (
  `StreamID` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `SubjectName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_streams`
--

INSERT INTO `subject_streams` (`StreamID`, `SubjectID`, `SubjectName`) VALUES
(2, 1, 'life orientation'),
(2, 2, 'mathematics'),
(2, 4, 'Geography'),
(2, 11, 'sepedi'),
(2, 12, 'english'),
(2, 13, 'physical science'),
(2, 14, 'life science'),
(3, 1, 'life orientation'),
(3, 2, 'mathematics'),
(3, 11, 'sepedi'),
(3, 12, 'english'),
(3, 13, 'physical science'),
(3, 14, 'life science'),
(3, 15, 'Agricultural science'),
(4, 1, 'life orientation'),
(4, 2, 'mathematics'),
(4, 11, 'sepedi'),
(4, 12, 'english'),
(4, 13, 'physical science'),
(4, 14, 'life science'),
(4, 16, 'civil engineering'),
(40, 24, 'Life Orientation'),
(40, 25, 'Mathematics'),
(40, 26, 'Sepedi'),
(40, 27, 'English'),
(40, 28, 'Web Tech'),
(40, 29, 'Programming'),
(40, 30, 'Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `SubmissionID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `AssignmentID` int(11) NOT NULL,
  `FilePath` varchar(255) NOT NULL,
  `SubmissionDate` datetime NOT NULL,
  `GradingStatus` varchar(50) DEFAULT 'Not graded'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `TeacherID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `Subject` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`TeacherID`, `UserID`, `FirstName`, `LastName`, `Subject`) VALUES
(3, 16, 'Teacher', 'Teacher', '');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_assignments`
--

CREATE TABLE `teacher_assignments` (
  `AssignmentID` int(11) NOT NULL,
  `TeacherID` int(11) DEFAULT NULL,
  `SubjectID` int(11) DEFAULT NULL,
  `Grade` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_assignments`
--

INSERT INTO `teacher_assignments` (`AssignmentID`, `TeacherID`, `SubjectID`, `Grade`) VALUES
(29, 16, 4, 'Grade 8'),
(31, 16, 6, 'Grade 8'),
(32, 16, 9, 'Grade 8'),
(33, 16, 8, 'Grade 8'),
(34, 16, 16, 'Grade 10'),
(35, 16, 8, 'Grade 9'),
(36, 16, 16, 'Grade 11');

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `TimetableID` int(11) NOT NULL,
  `ClassID` int(11) DEFAULT NULL,
  `Day` enum('Monday','Tuesday','Wednesday','Thursday','Friday') DEFAULT NULL,
  `Period` int(11) DEFAULT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  `TeacherID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timetables`
--

CREATE TABLE `timetables` (
  `TimetableID` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `Grade` int(11) NOT NULL,
  `Schedule` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Lastname` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `DOB` date DEFAULT NULL,
  `Gender` enum('Female','Male') DEFAULT NULL,
  `Class` varchar(50) DEFAULT NULL,
  `Role` enum('student','parent','teacher','admin') NOT NULL,
  `Grade` int(11) DEFAULT NULL,
  `StreamChosen` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `active` tinyint(1) DEFAULT 1,
  `IDNumber` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Name`, `Lastname`, `Email`, `Address`, `Password`, `DOB`, `Gender`, `Class`, `Role`, `Grade`, `StreamChosen`, `status`, `active`, `IDNumber`) VALUES
(15, 'parent', 'parent', 'parent@gmail.com', 'tuef', '$2y$10$qwY/IuWyQ3L6BpoDAgSP4e7xv7jNGfYwSd2BvrTFtAWRcnjQ9zX9i', '1990-05-15', 'Male', NULL, 'parent', NULL, 0, 'active', 1, '9005151234080'),
(16, 'Teacher', 'Teacher', 'teacher@gmail.com', 'seshego', '$2y$10$FMPmOZXRAUOoxOJ2047yMuqhPjtsdcZdTiHwhg3Xsd0glgJdztbnW', '2002-10-01', 'Male', NULL, 'teacher', NULL, 0, 'active', 1, '0210015000089'),
(17, 'Kamogelo', 'Student', 'kamogelo@gmail.com', 'student', '$2y$10$9H.QK5G9uRb/3L1o0szUgevaJB8MBdshCGC5V2WWGvMqb/0xnlnFC', '2008-10-15', 'Male', NULL, 'student', 9, 0, 'active', 1, '0810151234080'),
(18, 'Admin', 'Admin', 'admin@gmail.com', 'seshego', '$2y$10$LHRHMh3MUgaTGQutjXL2EOXC2KcZ5NHole4FcO0VpPJRHUXnBlm9G', '1995-10-22', 'Male', NULL, 'admin', NULL, 0, 'active', 1, '9510221230089'),
(21, 'Nicole', 'Mohlala', 'Mohlala@gmail.com', 'turf', '$2y$10$nvCIBOkod0Szn/R7aY7CMOGbgxNvCjDFPzvWiN9rm3/gnG8rScLkq', '2002-10-01', 'Female', NULL, 'parent', NULL, 0, 'active', 1, '0210015323089');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`AnswerID`),
  ADD KEY `QuestionID` (`QuestionID`);

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`AssignmentID`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD KEY `TeacherID` (`TeacherID`);

--
-- Indexes for table `attendance_confirmation`
--
ALTER TABLE `attendance_confirmation`
  ADD PRIMARY KEY (`ConfirmationID`),
  ADD KEY `RegisterID` (`RegisterID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indexes for table `behavior_reports`
--
ALTER TABLE `behavior_reports`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `ReportedBy` (`ReportedBy`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`MessageID`),
  ADD KEY `SenderID` (`SenderID`),
  ADD KEY `ReceiverID` (`ReceiverID`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`EventID`),
  ADD KEY `CreatedBy` (`CreatedBy`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`GradeID`);

--
-- Indexes for table `grade_subjects`
--
ALTER TABLE `grade_subjects`
  ADD PRIMARY KEY (`GradeID`,`SubjectID`),
  ADD KEY `SubjectID` (`SubjectID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`MessageID`),
  ADD KEY `GroupID` (`GroupID`),
  ADD KEY `SenderID` (`SenderID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`NotificationID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`ParentID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `parent_student`
--
ALTER TABLE `parent_student`
  ADD PRIMARY KEY (`ParentID`,`StudentID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indexes for table `pre_registration_tokens`
--
ALTER TABLE `pre_registration_tokens`
  ADD PRIMARY KEY (`TokenID`),
  ADD UNIQUE KEY `Token` (`Token`),
  ADD KEY `UsedByUserID` (`UsedByUserID`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`QuestionID`),
  ADD KEY `QuizID` (`QuizID`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`QuizID`),
  ADD KEY `SubjectID` (`SubjectID`),
  ADD KEY `fk_quizzes_userid` (`UserID`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`ResultID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `QuizID` (`QuizID`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`ResultID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `day` (`day`,`time`,`grade`);

--
-- Indexes for table `streams`
--
ALTER TABLE `streams`
  ADD PRIMARY KEY (`StreamID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`StudentID`);

--
-- Indexes for table `studentsubjects`
--
ALTER TABLE `studentsubjects`
  ADD PRIMARY KEY (`StudentID`,`SubjectID`),
  ADD KEY `SubjectID` (`SubjectID`);

--
-- Indexes for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD KEY `RegisterID` (`RegisterID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indexes for table `student_streams`
--
ALTER TABLE `student_streams`
  ADD PRIMARY KEY (`StudentID`,`StreamID`),
  ADD KEY `StreamID` (`StreamID`);

--
-- Indexes for table `student_tasks`
--
ALTER TABLE `student_tasks`
  ADD PRIMARY KEY (`TaskID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indexes for table `subjectdetails`
--
ALTER TABLE `subjectdetails`
  ADD PRIMARY KEY (`SubjectName`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`SubjectID`);

--
-- Indexes for table `subject_streams`
--
ALTER TABLE `subject_streams`
  ADD PRIMARY KEY (`StreamID`,`SubjectID`),
  ADD KEY `SubjectID` (`SubjectID`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`SubmissionID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `AssignmentID` (`AssignmentID`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`TeacherID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  ADD PRIMARY KEY (`AssignmentID`),
  ADD KEY `TeacherID` (`TeacherID`),
  ADD KEY `SubjectID` (`SubjectID`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`TimetableID`),
  ADD KEY `ClassID` (`ClassID`),
  ADD KEY `TeacherID` (`TeacherID`);

--
-- Indexes for table `timetables`
--
ALTER TABLE `timetables`
  ADD PRIMARY KEY (`TimetableID`),
  ADD KEY `SubjectID` (`SubjectID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `AnswerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `AssignmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `attendance_confirmation`
--
ALTER TABLE `attendance_confirmation`
  MODIFY `ConfirmationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `behavior_reports`
--
ALTER TABLE `behavior_reports`
  MODIFY `ReportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `GradeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `NotificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `ParentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pre_registration_tokens`
--
ALTER TABLE `pre_registration_tokens`
  MODIFY `TokenID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `QuestionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `QuizID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `ResultID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `ResultID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `streams`
--
ALTER TABLE `streams`
  MODIFY `StreamID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `student_attendance`
--
ALTER TABLE `student_attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_tasks`
--
ALTER TABLE `student_tasks`
  MODIFY `TaskID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `SubjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `subject_streams`
--
ALTER TABLE `subject_streams`
  MODIFY `SubjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `SubmissionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `TeacherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  MODIFY `AssignmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `TimetableID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timetables`
--
ALTER TABLE `timetables`
  MODIFY `TimetableID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`QuestionID`) REFERENCES `questions` (`QuestionID`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`QuizID`) REFERENCES `quizzes` (`QuizID`);

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `fk_quizzes_userid` FOREIGN KEY (`UserID`) REFERENCES `teachers` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quizzes_ibfk_2` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectID`);

--
-- Constraints for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_results_ibfk_2` FOREIGN KEY (`QuizID`) REFERENCES `quizzes` (`QuizID`) ON DELETE CASCADE;

--
-- Constraints for table `student_tasks`
--
ALTER TABLE `student_tasks`
  ADD CONSTRAINT `student_tasks_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
