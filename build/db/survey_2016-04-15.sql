# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.42)
# Database: survey
# Generation Time: 2016-04-15 16:04:13 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table answer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `answer`;

CREATE TABLE `answer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(11) unsigned DEFAULT NULL,
  `answer_text` varchar(1000) NOT NULL DEFAULT '',
  `order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_answer_question_id` (`question_id`),
  CONSTRAINT `fk_answer_question_id` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

LOCK TABLES `answer` WRITE;
/*!40000 ALTER TABLE `answer` DISABLE KEYS */;

INSERT INTO `answer` (`id`, `question_id`, `answer_text`, `order`)
VALUES
	(1,1,'Blue',2),
	(2,1,'I don\'t know that',1),
	(3,1,'Blue. No, yellow...',3),
	(4,1,'An African or European swallow?',4),
	(5,2,'Mark',2),
	(6,2,'John Stewart',1),
	(7,2,'Bill Gates',3),
	(8,2,'Billy Joel',4),
	(9,2,'Gandalf',5);

/*!40000 ALTER TABLE `answer` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table question
# ------------------------------------------------------------

DROP TABLE IF EXISTS `question`;

CREATE TABLE `question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `correct_answer_id` int(10) unsigned DEFAULT NULL,
  `question_text` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `fk_question_correct_answer_id` (`correct_answer_id`),
  CONSTRAINT `fk_question_correct_answer_id` FOREIGN KEY (`correct_answer_id`) REFERENCES `answer` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;

INSERT INTO `question` (`id`, `correct_answer_id`, `question_text`)
VALUES
	(1,1,'What is your favourite color?'),
	(2,9,'What is your name?');

/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table submission
# ------------------------------------------------------------

DROP TABLE IF EXISTS `submission`;

CREATE TABLE `submission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `answer_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_submission_Answer_id` (`answer_id`),
  CONSTRAINT `fk_submission_Answer_id` FOREIGN KEY (`answer_id`) REFERENCES `answer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

LOCK TABLES `submission` WRITE;
/*!40000 ALTER TABLE `submission` DISABLE KEYS */;

INSERT INTO `submission` (`id`, `answer_id`)
VALUES
	(1,1),
	(2,1),
	(15,1),
	(16,1),
	(23,1),
	(27,1),
	(32,1),
	(37,1),
	(3,2),
	(17,2),
	(18,2),
	(20,2),
	(31,2),
	(33,2),
	(4,3),
	(12,3),
	(14,3),
	(19,3),
	(21,3),
	(24,3),
	(25,3),
	(29,3),
	(36,3),
	(5,4),
	(6,4),
	(7,4),
	(11,4),
	(13,4),
	(22,4),
	(26,4),
	(28,4),
	(30,4),
	(34,4),
	(35,4),
	(8,6),
	(9,6),
	(10,7);

/*!40000 ALTER TABLE `submission` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
