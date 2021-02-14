-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 14, 2021 at 02:28 PM
-- Server version: 5.7.32
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `myDatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `fileCheck`
--

CREATE TABLE `fileCheck` (
  `id` int(255) NOT NULL,
  `filePath` varchar(255) NOT NULL,
  `dangerWordsJSON` json DEFAULT NULL,
  `date` datetime NOT NULL,
  `isSafe` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fileCheck`
--
ALTER TABLE `fileCheck`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fileCheck`
--
ALTER TABLE `fileCheck`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
