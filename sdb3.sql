-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Nov 05, 2023 at 11:47 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_ac`
--

CREATE TABLE `admin_ac` (
  `id` int(10) NOT NULL,
  `name` varchar(40) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_ac`
--

INSERT INTO `admin_ac` (`id`, `name`, `password`) VALUES
(18, 'admin123', '40bd001563085fc35165329ea1ff5c5ecbdbbeef'),
(20, 'admin_222', '1c6637a8f2e1f75e06ff9984894d6bd16a3a36a9');

-- --------------------------------------------------------

--
-- Table structure for table `most_viewed`
--

CREATE TABLE `most_viewed` (
  `product_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL,
  `price` int(10) NOT NULL,
  `image_01` varchar(100) NOT NULL,
  `image_02` varchar(100) NOT NULL,
  `image_03` varchar(100) NOT NULL,
  `details` varchar(500) NOT NULL,
  `stock` int(10) NOT NULL,
  `shop_name` varchar(30) NOT NULL,
  `shop_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `image_01`, `image_02`, `image_03`, `details`, `stock`, `shop_name`, `shop_id`) VALUES
(20, 'Elevidys ', 'Duchenne Muscular Dy', 7001, 'Patient_TG_ES_min.jpg', '', 'download (1).png', 'Home Articles Print 10 of the Most Expensive Drugs in the U.S. Medically reviewed by Leigh Ann Anderson, PharmD. Last updated on Oct 13, 2023.  1. Hemgenix Cost: $3.5 million per one-time dose Manufacturer: CSL Behring Use: Hemophilia B FDA Approval Date: November 22, 2022 Hemgenix (etranacogene dezaparvovec-drlb) is approved to treat hemophilia B, a rare, lifelong bleeding disorder. Given as a single, one-time intravenous (IV) infusion, it&#39;s list price of $3.5 million makes it the most expe', 3, 'Rishi Herbs & Medicine Store', 11),
(21, 'Zolgensma', 'Spinal muscular atro', 9999, 'jndjq.jpeg', 'we.png', 'jndjq.jpeg', 'Zolgensma uses an adeno-associated virus as a vector to deliver a functional copy of the human SMN gene into the target motor neuron cells so that normal SMN proteins are produced to support muscle functions.', 2, 'Rishi Herbs & Medicine Store', 11),
(22, 'Brineura 30mg/ml', 'late-infantile neuro', 4970, 'nfew5peg.jpeg', 'downlopvW5;dwcsdjpeg.jpeg', 'fkqew;&#39;r.jpeg', 'Brineura is a first-in-class medication that treats a specific form of Batten disease called late-infantile neuronal ceroid lipofuscinosis type 2 (CLN2). Specifically, Brineura helps treat the slow loss of walking ability in pediatric patients 3 years and older.  Following a price increase of 3.5% this January, the list price of Brineura is now $29,073 for a kit of 2 vials of 150 mg. The recommended dosage is 300 mg every 2 weeks, meaning that over the course of one year, costs add up to $755,89', 7, 'Mehta Medical Store', 14),
(23, ' Soliris', 'paroxysmal nocturnal', 789, 'ew.jpeg', 'LLMFEW5md.jpeg', 'm4703n).jpeg', ' Soliris is used to treat paroxysmal nocturnal hemoglobinuria and atypical hemolytic uremic syndrome, blood disorders that result in the destruction of red blood cells. The dosing regimen for Soliris can differ depending on the age of the patient and the condition being treated. But most patients need a maintenance dose of 1200 mg every 2 weeks, which only a healthcare provider can administer. Because Soliris comes with a high risk of infection, the drug is part of a Risk Evaluation Mitigation S', 3, 'Ambaji Medical Medicine And ', 15),
(24, 'Myalept Liquid 100ml', 'Leptin deficiency', 879, '1ne2kn.jpeg', 'nj2ej.jpeg', 'brain-1.jpg', 'Myalept, a recombinant form of human leptin, won FDA approval back in 2014 to treat leptin deficiency in patients with congenital or acquired generalized lipodystrophy. Lipodystrophy is a group of rare conditions characterized by widespread loss of fat tissue under the skin, which causes a deficiency in the leptin hormone and leads to metabolic conditions.  The subcutaneous injection has changed hands many times over the years. At the time of its approval, AstraZeneca was working on acquiring it', 6, 'Mehta Medical Store', 14),
(25, 'Soliris tablet 300mg', 'paroxysmal nocturnal', 930, 'LLMFEW5md.jpeg', 'ew.jpeg', 'm4703n).jpeg', ' Soliris is used to treat paroxysmal nocturnal hemoglobinuria and atypical hemolytic uremic syndrome, blood disorders that result in the destruction of red blood cells. The dosing regimen for Soliris can differ depending on the age of the patient and the condition being treated. But most patients need a maintenance dose of 1200 mg every 2 weeks, which only a healthcare provider can administer. Because Soliris comes with a high risk of infection, the drug is part of a Risk Evaluation Mitigation S', 11, 'Mehta Medical Store', 14);

-- --------------------------------------------------------

--
-- Table structure for table `shopkeeper_ac`
--

CREATE TABLE `shopkeeper_ac` (
  `id` int(10) NOT NULL,
  `shop_name` varchar(50) NOT NULL,
  `shopkeeper_name` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  `details` varchar(200) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shopkeeper_ac`
--

INSERT INTO `shopkeeper_ac` (`id`, `shop_name`, `shopkeeper_name`, `image`, `details`, `address`, `email`, `password`) VALUES
(11, 'Rishi Herbs & Medicine Store', 'Rishi Dhawan', 'prlfw4er.jpeg', 'You can Find Any rare herbs in our store!', 'Faiz Co-Op Hsg Soc, Shop No.8/B,Lovely Home, Santacruz – Chembur Link Rd, Friends Colony, Kalina, Ku', '321ahmad0042@gmail.com', '24fb6bc944cfe84acb9eec9f5a4c332c059725b1'),
(14, 'Mehta Medical Store', 'Kiran Mehta', 'f69dw.jpeg', 'Find all your important medicines here!', 'Shop No 13, Kastur Mahal, Sion Trombay Road, Sion, Mumbai, Maharashtra 400022', '999kiran@gmail.com', 'afc97ea131fd7e2695a98ef34013608f97f34e1d'),
(15, 'Ambaji Medical Medicine And ', 'Anup Bhandari', 'p1ne2.jpeg', 'Medicine Product', 'shop no.2 bldg no. 34, near sbi bank, Tilak Nagar, Kurla, Mumbai, Maharashtra 400089', '478anup@gmail.com', 'fbea31c7083ef34d19f4b946b94b60560c709e34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_ac`
--
ALTER TABLE `admin_ac`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shopkeeper_ac`
--
ALTER TABLE `shopkeeper_ac`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_ac`
--
ALTER TABLE `admin_ac`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `shopkeeper_ac`
--
ALTER TABLE `shopkeeper_ac`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
