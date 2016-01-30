-- SQL Dump
-- RaspiControl
-- http://lucadoglione.altervista.org
-- https://github.com/lukdog/RaspiControl.git

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `raspicontrol`
--
--
-- Table `CATEGORIES`
--

CREATE TABLE IF NOT EXISTS `CATEGORIES` (
  `CATEGORY` varchar(20) NOT NULL,
  PRIMARY KEY (`CATEGORY`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Categories of available scripts';

-- --------------------------------------------------------

--
-- Table `AUTHORIZATIONS`
--

CREATE TABLE IF NOT EXISTS `AUTHORIZATIONS` (
  `ID_USER` varchar(20) NOT NULL,
  `ID_SCRIPT` int(11) NOT NULL,
  PRIMARY KEY (`ID_USER`,`ID_SCRIPT`),
  KEY `ID_SCRIPT` (`ID_SCRIPT`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table that stores users authorizations';

-- --------------------------------------------------------

--
-- Table `SCRIPTS`
--

CREATE TABLE IF NOT EXISTS `SCRIPTS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(20) NOT NULL,
  `CATEGORY` varchar(20) NOT NULL,
  `CMD` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table that stores available scripts' AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Table `UTSERS`
--

CREATE TABLE IF NOT EXISTS `USERS` (
  `NID` int(11) NOT NULL AUTO_INCREMENT,
  `ID` varchar(20) NOT NULL,
  `PASSWORD` varchar(32) NOT NULL,
  `SALT`varchar(32) NOT NULL,
  `VALID` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `NID` (`NID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table that stores users' AUTO_INCREMENT=0 ;

--
-- Limiti per le tabelle scaricate
--

-- Limiti per la tabella `AUTHORIZATIONS`
--
ALTER TABLE `AUTHORIZATIONS`
  ADD CONSTRAINT `AUTHORIZATIONS_ibfk_2` FOREIGN KEY (`ID_SCRIPT`) REFERENCES `SCRIPTS` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `AUTHORIZATIONS_ibfk_1` FOREIGN KEY (`ID_USER`) REFERENCES `USERS` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
