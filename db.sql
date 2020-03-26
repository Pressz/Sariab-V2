-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 30, 2019 at 08:07 PM
-- Server version: 5.7.28-0ubuntu0.18.04.4-log
-- PHP Version: 7.2.24-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sariab_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `Keywords`
--

CREATE TABLE `Keywords` (
  `Id` int(11) NOT NULL,
  `Title` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Posts`
--

CREATE TABLE `Posts` (
  `Id` bigint(20) NOT NULL,
  `Title` varchar(3000) CHARACTER SET latin1 NOT NULL,
  `Abstract` longtext CHARACTER SET latin1 NOT NULL,
  `Submit` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Canonical` varchar(3000) CHARACTER SET latin1 NOT NULL,
  `Meta` text CHARACTER SET latin1 NOT NULL,
  `Publisher` varchar(300) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Keywords`
--
ALTER TABLE `Keywords`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Posts`
--
ALTER TABLE `Posts`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Keywords`
--
ALTER TABLE `Keywords`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Posts`
--
ALTER TABLE `Posts`
  MODIFY `Id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


CREATE TABLE `Podcasts` ( `Id` INT NOT NULL AUTO_INCREMENT , `EpisodeNumber` VARCHAR(5) NOT NULL , `PublishDate` VARCHAR(20) NOT NULL , `Title` VARCHAR(300) NOT NULL , `About` LONGTEXT NOT NULL , `FileUrl` VARCHAR(3000) NOT NULL , PRIMARY KEY (`Id`)) ENGINE = InnoDB;

CREATE TABLE `Supports` ( `Id` BIGINT NOT NULL AUTO_INCREMENT , `Name` VARCHAR(300) NOT NULL , `Url` VARCHAR(3000) NULL , `IsAuthor` BIT(1) NULL , `IsFan` BIT(1) NULL , `IsContributer` BIT(1) NULL , `IsCoach` BIT(1) NULL , `IsDonater` BIT(1) NULL , `IsMedia` BIT(1) NULL , PRIMARY KEY (`Id`)) ENGINE = InnoDB;

CREATE TABLE `Positions` ( `Id` INT NOT NULL AUTO_INCREMENT , `Title` VARCHAR(500) NOT NULL , `IsPaid` BIT(1) NULL DEFAULT b'0' , `Salary` VARCHAR(300) NULL , `JobDescription` LONGTEXT NOT NULL , PRIMARY KEY (`Id`)) ENGINE = InnoDB;
ALTER TABLE `Positions` ADD `IsActive` BIT(1) NULL DEFAULT b'1' AFTER `Title`;