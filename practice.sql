-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2022 at 11:25 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `practice`
--
CREATE DATABASE IF NOT EXISTS `practice` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `practice`;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `CommentID` int(11) NOT NULL,
  `IssueID` int(11) NOT NULL,
  `CommentUserID` int(11) NOT NULL,
  `CommentText` text NOT NULL,
  `DateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `issue`
--

CREATE TABLE `issue` (
  `IssueID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `IssuerID` int(11) NOT NULL,
  `Description` text NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT 1,
  `DateCreated` date NOT NULL,
  `HoursToSolve` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `issuehistory`
--

CREATE TABLE `issuehistory` (
  `HistoryID` int(11) NOT NULL,
  `IssueID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `FromStatus` int(11) NOT NULL,
  `ToStatus` int(11) NOT NULL,
  `DateMade` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `ProjectID` int(11) NOT NULL,
  `ProjectName` varchar(255) NOT NULL,
  `Description` text NOT NULL DEFAULT '\'\'',
  `DateCreated` date NOT NULL,
  `DateStarted` date DEFAULT NULL,
  `DateEnded` date DEFAULT NULL,
  `Status` int(255) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `usertoproject`
--

CREATE TABLE `usertoproject` (
  `ConnectionID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `CommentIssue` (`IssueID`);

--
-- Indexes for table `issue`
--
ALTER TABLE `issue`
  ADD PRIMARY KEY (`IssueID`),
  ADD KEY `ProjectIssue` (`ProjectID`);

--
-- Indexes for table `issuehistory`
--
ALTER TABLE `issuehistory`
  ADD PRIMARY KEY (`HistoryID`),
  ADD KEY `HistoryIssue` (`IssueID`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`ProjectID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `usertoproject`
--
ALTER TABLE `usertoproject`
  ADD PRIMARY KEY (`ConnectionID`),
  ADD UNIQUE KEY `ConnectionID` (`ConnectionID`),
  ADD KEY `User` (`UserID`),
  ADD KEY `Project` (`ProjectID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issue`
--
ALTER TABLE `issue`
  MODIFY `IssueID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issuehistory`
--
ALTER TABLE `issuehistory`
  MODIFY `HistoryID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `ProjectID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usertoproject`
--
ALTER TABLE `usertoproject`
  MODIFY `ConnectionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `CommentIssue` FOREIGN KEY (`IssueID`) REFERENCES `issue` (`IssueID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `issue`
--
ALTER TABLE `issue`
  ADD CONSTRAINT `ProjectIssue` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `issuehistory`
--
ALTER TABLE `issuehistory`
  ADD CONSTRAINT `HistoryIssue` FOREIGN KEY (`IssueID`) REFERENCES `issue` (`IssueID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usertoproject`
--
ALTER TABLE `usertoproject`
  ADD CONSTRAINT `Project` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `User` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
