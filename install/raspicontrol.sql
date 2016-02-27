-- phpMyAdmin SQL Dump
-- version 4.2.12
-- http://www.phpmyadmin.net

-- Generation Time: Feb 27, 2016 at 12:03 PM
-- Server version: 5.5.46-0
-- PHP Version: 5.6.14-0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `testcontrol`
--

-- --------------------------------------------------------

--
-- Table structure for table `AUTHORIZATIONS`
--

CREATE TABLE IF NOT EXISTS `AUTHORIZATIONS` (
  `ID_USER`   varchar(20) NOT NULL,
  `ID_SCRIPT` INT(11)     NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table that stores users authorizations';

-- --------------------------------------------------------

--
-- Table structure for table `CATEGORIES`
--

CREATE TABLE IF NOT EXISTS `CATEGORIES` (
  `CATEGORY` VARCHAR(20) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1
  COMMENT = 'Categories of available scripts';

-- --------------------------------------------------------

--
-- Table structure for table `SCRIPTS`
--

CREATE TABLE IF NOT EXISTS `SCRIPTS` (
  `ID`       INT(11)     NOT NULL,
  `NAME`     VARCHAR(20) NOT NULL,
  `CATEGORY` varchar(20) NOT NULL,
  `CMD`      TEXT        NOT NULL,
  `TYPE`     SMALLINT(6) NOT NULL DEFAULT '1',
  `ALERT`    TEXT
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = latin1
  COMMENT = 'Table that stores available scripts';

-- --------------------------------------------------------

--
-- Table structure for table `USERS`
--

CREATE TABLE IF NOT EXISTS `USERS` (
  `NID`      INT(11)     NOT NULL,
  `ID`       VARCHAR(20) NOT NULL,
  `PASSWORD` varchar(32) NOT NULL,
  `SALT`     VARCHAR(32)          DEFAULT NULL,
  `VALID`    TINYINT(1)  NOT NULL DEFAULT '1',
  `ADMIN`    TINYINT(1)  NOT NULL DEFAULT '0'
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = latin1
  COMMENT = 'Table that stores users';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AUTHORIZATIONS`
--
ALTER TABLE `AUTHORIZATIONS`
ADD PRIMARY KEY (`ID_USER`, `ID_SCRIPT`), ADD KEY `ID_SCRIPT` (`ID_SCRIPT`);

--
-- Indexes for table `CATEGORIES`
--
ALTER TABLE `CATEGORIES`
ADD PRIMARY KEY (`CATEGORY`);

--
-- Indexes for table `SCRIPTS`
--
ALTER TABLE `SCRIPTS`
ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `USERS`
--
ALTER TABLE `USERS`
ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `NID` (`NID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `SCRIPTS`
--
ALTER TABLE `SCRIPTS`
MODIFY `ID` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 1;
--
-- AUTO_INCREMENT for table `USERS`
--
ALTER TABLE `USERS`
MODIFY `NID` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 1;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `AUTHORIZATIONS`
--
ALTER TABLE `AUTHORIZATIONS`
ADD CONSTRAINT `AUTHORIZATIONS_ibfk_1` FOREIGN KEY (`ID_USER`) REFERENCES `USERS` (`ID`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `AUTHORIZATIONS_ibfk_2` FOREIGN KEY (`ID_SCRIPT`) REFERENCES `SCRIPTS` (`ID`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
