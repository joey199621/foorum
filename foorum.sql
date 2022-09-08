-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 08, 2022 at 06:45 PM
-- Server version: 10.6.7-MariaDB-2ubuntu1.1
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foorum`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name_en-US` varchar(255) NOT NULL,
  `description_en-US` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name_en-US`, `description_en-US`) VALUES
(1, 'General', 'You can post anything in this category');

-- --------------------------------------------------------

--
-- Table structure for table `foorum_settings`
--

CREATE TABLE `foorum_settings` (
  `id` int(11) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `description_en` text NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `foorum_settings`
--

INSERT INTO `foorum_settings` (`id`, `name_en`, `description_en`, `name`) VALUES
(1, 'Theme', 'The global theme of the Foorum', 'theme'),
(2, 'Maintenance mode', 'If set to 1, your Foorum won\'t be available publicly.\r\nYour admin panel will still be accessible.\r\nYou can add IP addresses exception (typically yours).', 'maintenance_mode_on'),
(3, 'Language', 'Forum language. Defines the interface language.', 'lang');

-- --------------------------------------------------------

--
-- Table structure for table `foorum_setting_value`
--

CREATE TABLE `foorum_setting_value` (
  `id` int(11) NOT NULL,
  `foorum_setting_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `foorum_setting_value`
--

INSERT INTO `foorum_setting_value` (`id`, `foorum_setting_id`, `value`) VALUES
(1, 1, 'foorum'),
(2, 2, '0'),
(3, 3, 'en-US'),
(4, 3, 'fr-FR');

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE `topic` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `date_topic` datetime NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) NOT NULL,
  `locked` int(11) NOT NULL DEFAULT 0,
  `solved` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `topic`
--

INSERT INTO `topic` (`id`, `author`, `title`, `lang`, `date_topic`, `category_id`, `locked`, `solved`) VALUES
(7, 1, 'Hello everybody', 'en-US', '2022-09-08 18:44:27', 1, 0, 0),
(8, 1, 'Another topic', 'en-US', '2022-09-08 18:44:55', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `topic_message`
--

CREATE TABLE `topic_message` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `message_date` datetime NOT NULL DEFAULT current_timestamp(),
  `content` text NOT NULL,
  `lang` varchar(5) NOT NULL,
  `topic_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `topic_message`
--

INSERT INTO `topic_message` (`id`, `author`, `message_date`, `content`, `lang`, `topic_id`) VALUES
(28, 1, '2022-09-08 18:44:27', 'This is a test topic, please ignore it', 'en-US', 7),
(29, 1, '2022-09-08 18:44:40', 'Oh! And this is a test reply.', 'en-US', 7),
(30, 1, '2022-09-08 18:44:45', 'Ok, cool.', 'en-US', 7),
(31, 1, '2022-09-08 18:44:55', 'YEAH!', 'en-US', 8),
(32, 1, '2022-09-08 18:45:14', 'up... this topic should be bumped now', 'en-US', 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(500) NOT NULL,
  `pseudo` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `pseudo`) VALUES
(1, 'test@email.com', '$2y$10$6WXSxb56E9g2l/OL/D4aMuXeetUApNwkhiIJckC160HZJVUbSt2le', 'TestUser');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foorum_settings`
--
ALTER TABLE `foorum_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foorum_setting_value`
--
ALTER TABLE `foorum_setting_value`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foorum_setting_value_ibfk_1` (`foorum_setting_id`);

--
-- Indexes for table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author` (`author`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `topic_message`
--
ALTER TABLE `topic_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author` (`author`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `foorum_settings`
--
ALTER TABLE `foorum_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `foorum_setting_value`
--
ALTER TABLE `foorum_setting_value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `topic`
--
ALTER TABLE `topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `topic_message`
--
ALTER TABLE `topic_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `foorum_setting_value`
--
ALTER TABLE `foorum_setting_value`
  ADD CONSTRAINT `foorum_setting_value_ibfk_1` FOREIGN KEY (`foorum_setting_id`) REFERENCES `foorum_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `topic`
--
ALTER TABLE `topic`
  ADD CONSTRAINT `topic_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topic_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `topic_message`
--
ALTER TABLE `topic_message`
  ADD CONSTRAINT `topic_message_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topic_message_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
