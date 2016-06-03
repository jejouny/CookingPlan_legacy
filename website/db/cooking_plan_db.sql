-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 03, 2016 at 05:37 PM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cooking_plan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `price` decimal(6,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`, `picture`, `price`) VALUES
(1, 'AAAAAAAAA', '1.jpeg', 0),
(2, 'BBBBBBBBB', '2.jpeg', 0),
(3, 'CCCCCCCC', '3.jpg', 1),
(4, 'DDDDDDDD', '4.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE IF NOT EXISTS `recipes` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `type_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `recipe_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `name`, `description`, `picture`, `type_id`) VALUES
(1, 'Recette AAAAAA', 'Description de la recette AAAAA', '1.jpeg', 2),
(2, 'Recette BBBBB', 'Description de la recette BBBBB', '66.jpg', 2),
(3, 'Recette CCCCC', 'Description de la recette CCCCC', NULL, 3),
(4, 'Recette BBBBB', 'Description de la recette BBBBB\r\nDescription de la recette BBBBB\r\nDescription de la recette BBBBB\r\nDescription de la recette BBBBB\r\n', '66.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `recipes_ingredients`
--

CREATE TABLE IF NOT EXISTS `recipes_ingredients` (
  `recipe_id` int(10) NOT NULL,
  `ingredient_id` int(10) NOT NULL,
  `ingredient_amount` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recipes_owners`
--

CREATE TABLE IF NOT EXISTS `recipes_owners` (
  `recipe_id` int(255) NOT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `recipes_owners`
--

INSERT INTO `recipes_owners` (`recipe_id`, `user_id`) VALUES
(1, 1),
(1, 2),
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_types`
--

CREATE TABLE IF NOT EXISTS `recipe_types` (
  `type_id` int(10) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `recipe_types`
--

INSERT INTO `recipe_types` (`type_id`, `type_name`) VALUES
(1, 'Entrée'),
(2, 'Plat principal'),
(3, 'Dessert');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Users' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `admin`) VALUES
(1, 'cooking_plan_admin', 'Emmanuelle_83', 1),
(2, 'julien', 'Clarisse_14', 0),
(3, 'emmanuelle', 'Clarisse_14', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
