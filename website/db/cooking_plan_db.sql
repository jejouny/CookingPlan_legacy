-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 08, 2016 at 12:12 PM
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
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`) VALUES
(1, 'Administator account'),
(2, 'Guest account 1');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `price` decimal(6,0) NOT NULL,
  `account_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`, `picture`, `price`, `account_id`) VALUES
(1, 'AAAAAAAAA', '1.jpeg', 0, 2),
(2, 'BBBBBBBBB', '2.jpeg', 0, 2),
(3, 'CCCCCCCC', '3.jpg', 1, 2),
(4, 'DDDDDDDDDD', NULL, 0, 2);

--
-- Triggers `ingredients`
--
DROP TRIGGER IF EXISTS `onIngredientRemoved`;
DELIMITER //
CREATE TRIGGER `onIngredientRemoved` AFTER DELETE ON `ingredients`
 FOR EACH ROW BEGIN
UPDATE recipes_ingredients SET ingredient_id=-1 WHERE ingredient_id = OLD.id;
END
//
DELIMITER ;

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
  `account_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `recipe_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `name`, `description`, `picture`, `type_id`, `account_id`) VALUES
(2, 'Recette BBBBB', 'Description de la recette BBBBB', '66.jpg', 2, 2),
(3, 'Recette CCCCC', 'Description de la recette CCCCC', NULL, 3, 2),
(4, 'Recette BBBBB', 'Description de la recette BBBBB\r\nDescription de la recette BBBBB\r\nDescription de la recette BBBBB\r\nDescription de la recette BBBBB\r\n', '66.jpg', 2, 2);

--
-- Triggers `recipes`
--
DROP TRIGGER IF EXISTS `onRecipeRemoved`;
DELIMITER //
CREATE TRIGGER `onRecipeRemoved` AFTER DELETE ON `recipes`
 FOR EACH ROW BEGIN
DELETE FROM recipes_ingredients WHERE recipe_id = OLD.id;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `recipes_ingredients`
--

CREATE TABLE IF NOT EXISTS `recipes_ingredients` (
  `recipe_id` int(10) NOT NULL,
  `ingredient_id` int(10) NOT NULL,
  `ingredient_amount` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `recipes_ingredients`
--

INSERT INTO `recipes_ingredients` (`recipe_id`, `ingredient_id`, `ingredient_amount`) VALUES
(1, -1, 0),
(2, -1, 0),
(2, 3, 0),
(2, 4, 0);

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
  `account_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Users' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `account_id`) VALUES
(1, 'cooking_plan_admin', 'Emmanuelle_83', 1),
(2, 'julien', 'Clarisse_14', 2),
(3, 'emmanuelle', 'Clarisse_14', 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
