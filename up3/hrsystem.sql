-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               10.4.14-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for hrsystem
CREATE DATABASE IF NOT EXISTS `hrsystem` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `hrsystem`;

-- Dumping structure for table hrsystem.contract
CREATE TABLE IF NOT EXISTS `contract` (
  `Contract_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Type` varchar(25) NOT NULL,
  `Hijri_Date` date DEFAULT NULL,
  `Gregorian_Date` date DEFAULT NULL,
  `Job_Position` varchar(50) NOT NULL,
  `Salary` double NOT NULL,
  `Housing_Allowance` int(15) NOT NULL,
  `Transportation_Allowance` int(15) NOT NULL,
  `Start_Date` date NOT NULL,
  `End_Date` date NOT NULL,
  PRIMARY KEY (`Contract_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table hrsystem.contract: ~13 rows (approximately)
DELETE FROM `contract`;
/*!40000 ALTER TABLE `contract` DISABLE KEYS */;
INSERT INTO `contract` (`Contract_ID`, `Type`, `Hijri_Date`, `Gregorian_Date`, `Job_Position`, `Salary`, `Housing_Allowance`, `Transportation_Allowance`, `Start_Date`, `End_Date`) VALUES
	(1, 'Saudi Employment', '2020-11-21', '2020-11-21', 'Human Resources Officer', 12000, 3000, 2000, '2020-11-22', '2021-01-01'),
	(37, 'Saudi Employment', NULL, NULL, 'HR Manager', 50000, 1000, 1000, '2020-12-02', '2021-11-18'),
	(38, 'Saudi Employment', NULL, NULL, 'Developer', 50000, 1000, 1000, '2020-12-02', '2021-11-02'),
	(39, 'Saudi Employment', NULL, NULL, 'Developer', 50000, 1000, 1000, '2020-12-02', '2021-11-02'),
	(40, 'Saudi Employment', NULL, NULL, 'Developer', 50000, 1000, 1000, '2020-12-02', '2021-11-02'),
	(41, 'Saudi Employment', NULL, NULL, 'CEO', 50000, 1000, 1000, '2020-12-02', '2021-12-02'),
	(42, 'Saudi Employment', NULL, NULL, 'CEO', 50000, 1000, 1000, '2020-12-02', '2021-12-02'),
	(43, 'Saudi Employment', NULL, NULL, 'CEO', 50000, 1000, 1000, '2020-12-02', '2021-12-02'),
	(44, 'Saudi Employment', NULL, '2020-12-02', 'CEO', 50000, 1000, 1000, '2020-12-02', '2021-12-02'),
	(45, 'Saudi Employment', NULL, '2020-12-02', 'Sales Intern', 10000, 1000, 1000, '2020-12-02', '2022-11-02'),
	(46, 'Saudi Employment', NULL, '2020-12-03', 'موارد بشرية', 2000, 2000, 2000, '2020-12-04', '2020-12-12'),
	(47, 'Saudi Employment', NULL, '2020-12-03', 'موارد بشرية', 2000, 2000, 2000, '2020-12-04', '2020-12-12'),
	(48, 'Saudi Employment', NULL, '2020-12-03', 'موارد بشرية', 2000, 2000, 2000, '2020-12-04', '2020-12-12'),
	(49, 'Saudi Employment', NULL, '2020-12-03', 'موارد بشرية', 2000, 2000, 2000, '2020-12-04', '2020-12-12'),
	(50, 'Saudi Employment', NULL, '2020-12-03', 'موارد بشرية', 2000, 2000, 2000, '2020-12-04', '2020-12-12');
/*!40000 ALTER TABLE `contract` ENABLE KEYS */;

-- Dumping structure for table hrsystem.department
CREATE TABLE IF NOT EXISTS `department` (
  `Department_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) NOT NULL,
  PRIMARY KEY (`Department_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table hrsystem.department: ~6 rows (approximately)
DELETE FROM `department`;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` (`Department_ID`, `Name`) VALUES
	(1, 'Human Resources'),
	(2, 'Information Systems'),
	(3, 'Sales'),
	(4, 'Operations'),
	(5, 'Marketing'),
	(6, 'Administration');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;

-- Dumping structure for table hrsystem.employee
CREATE TABLE IF NOT EXISTS `employee` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `Employee_ID` varchar(10) DEFAULT NULL,
  `Contract_ID` int(10) DEFAULT NULL,
  `Department_ID` int(10) DEFAULT NULL,
  `Manager_ID` int(10) DEFAULT NULL,
  `Phone Number` int(10) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `National_ID` varchar(10) DEFAULT NULL,
  `Password` varchar(255) DEFAULT '',
  `First_Name` varchar(25) NOT NULL,
  `Middle_Name` varchar(25) NOT NULL,
  `Last_Name` varchar(25) NOT NULL,
  `Street` varchar(50) NOT NULL,
  `Neighborhood` varchar(50) NOT NULL,
  `City` varchar(30) NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `Nationality` varchar(25) NOT NULL,
  `Role` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Phone Number` (`Phone Number`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `Contract_ID` (`Contract_ID`),
  KEY `Employee_Department` (`Department_ID`),
  KEY `Employee_Roles` (`Role`),
  KEY `Manager_ID` (`Manager_ID`),
  CONSTRAINT `Employee_Contract` FOREIGN KEY (`Contract_ID`) REFERENCES `contract` (`Contract_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Employee_Department` FOREIGN KEY (`Department_ID`) REFERENCES `department` (`Department_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Employee_Roles` FOREIGN KEY (`Role`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table hrsystem.employee: ~4 rows (approximately)
DELETE FROM `employee`;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` (`ID`, `Employee_ID`, `Contract_ID`, `Department_ID`, `Manager_ID`, `Phone Number`, `Email`, `National_ID`, `Password`, `First_Name`, `Middle_Name`, `Last_Name`, `Street`, `Neighborhood`, `City`, `Gender`, `Nationality`, `Role`) VALUES
	(1, 'E1', 1, 1, 0, 1234453674, 'bhagyamad@mail.com', '1112223334', '12345678', 'Bha', 'C', 'D', 'ddd', 'ddd', 'ddd', 'Male', 'Sri Lankan', 1),
	(12, 'E12', 37, 1, 1, 76678899, 'bhagyamad892@gmail.com', '89067812', '22368235', 'Manger', 't', 't', 'c', 'حي', 'c', 'Male', 'Sri', 1),
	(19, 'E19', 44, 1, 0, 1212121212, 'chamira@mail.com', '1212121212', '08704935', 'Chmira', 'a', 'a', 'c', 'حي', 'c', 'Male', 'Sri', 1),
	(20, 'E20', 45, 3, 1, 2147483647, 'tharindu@mail.com', '2223334445', '01560111', 'Tharindu`', 't', 'M', 'c', 'حي', 'c', 'Male', 'Sri', 5);
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;

-- Dumping structure for table hrsystem.leave
CREATE TABLE IF NOT EXISTS `leave` (
  `Leave_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Employee_ID` int(10) NOT NULL,
  `Reason` varchar(255) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `Start_Time` time(6) DEFAULT NULL,
  `End_Time` time(6) DEFAULT NULL,
  `Start_Date` date NOT NULL,
  `End_Date` date NOT NULL,
  `Type` varchar(25) NOT NULL,
  PRIMARY KEY (`Leave_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table hrsystem.leave: ~2 rows (approximately)
DELETE FROM `leave`;
/*!40000 ALTER TABLE `leave` DISABLE KEYS */;
INSERT INTO `leave` (`Leave_ID`, `Employee_ID`, `Reason`, `Status`, `Start_Time`, `End_Time`, `Start_Date`, `End_Date`, `Type`) VALUES
	(5, 1, 'This is a test', 'Pending', '08:00:00.000000', '08:00:00.000000', '2020-12-02', '2020-12-03', 'Test'),
	(6, 12, 'asdasd', 'Approved', '08:14:00.000000', '08:14:00.000000', '2020-12-04', '2020-12-05', 'Test');
/*!40000 ALTER TABLE `leave` ENABLE KEYS */;

-- Dumping structure for table hrsystem.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table hrsystem.roles: ~5 rows (approximately)
DELETE FROM `roles`;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `role_name`) VALUES
	(1, 'HR'),
	(2, 'Employee'),
	(3, 'Manager'),
	(4, 'CEO'),
	(5, 'IT');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table hrsystem.timesheet
CREATE TABLE IF NOT EXISTS `timesheet` (
  `Timesheet_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Employee_ID` int(10) NOT NULL,
  `Date` date NOT NULL,
  `Start_Time` time(6) DEFAULT NULL,
  `End_Time` time(6) DEFAULT NULL,
  `Excuse` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Timesheet_ID`),
  KEY `Fk_employee_timesheet` (`Employee_ID`),
  CONSTRAINT `Fk_employee_timesheet` FOREIGN KEY (`Employee_ID`) REFERENCES `employee` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table hrsystem.timesheet: ~1 rows (approximately)
DELETE FROM `timesheet`;
/*!40000 ALTER TABLE `timesheet` DISABLE KEYS */;
INSERT INTO `timesheet` (`Timesheet_ID`, `Employee_ID`, `Date`, `Start_Time`, `End_Time`, `Excuse`) VALUES
	(6, 1, '2020-12-02', '08:36:00.000000', '18:36:00.000000', NULL);
/*!40000 ALTER TABLE `timesheet` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
