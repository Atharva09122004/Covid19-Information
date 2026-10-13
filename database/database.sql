-- ========================================================
-- COVID-19 Information and Awareness Website
-- Database Setup SQL Script
-- Database Name: covid19_db
-- ========================================================

-- Create database if it does not exist
CREATE DATABASE IF NOT EXISTS `covid19_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `covid19_db`;

-- --------------------------------------------------------
-- Table Structure: users
-- --------------------------------------------------------
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `mobile` VARCHAR(15) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `gender` VARCHAR(20) DEFAULT 'Prefer not to say',
  `role` VARCHAR(20) DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table Structure: covid_info
-- --------------------------------------------------------
DROP TABLE IF EXISTS `covid_info`;

CREATE TABLE `covid_info` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table Structure: statistics
-- --------------------------------------------------------
DROP TABLE IF EXISTS `statistics`;

CREATE TABLE `statistics` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `country` VARCHAR(100) NOT NULL,
  `cases` INT NOT NULL DEFAULT 0,
  `recovered` INT NOT NULL DEFAULT 0,
  `deaths` INT NOT NULL DEFAULT 0,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table Structure: comments
-- --------------------------------------------------------
CREATE TABLE `comments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `comment` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table Structure: contact_messages
-- --------------------------------------------------------
DROP TABLE IF EXISTS `contact_messages`;

CREATE TABLE `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- Seed Data Insertion
-- ========================================================

-- Detailed COVID-19 Educational Information (Module 2)
INSERT INTO `covid_info` (`title`, `description`, `category`) VALUES
('What is COVID-19?', 'Coronavirus disease (COVID-19) is an infectious disease caused by the SARS-CoV-2 virus. Most people infected experience mild to moderate respiratory illness and recover without requiring special treatment.', 'Overview'),
('How COVID-19 Spreads', 'The virus spreads mainly between people who are in close contact with each other via respiratory droplets produced when an infected person coughs, sneezes, speaks, or breathes.', 'Overview'),

('Common Symptoms', 'Early signs of COVID-19 infection include fever or chills, dry cough, fatigue, loss of taste or smell, and sore throat.', 'Symptoms'),
('Serious Symptoms', 'Seek immediate medical attention if experiencing difficulty breathing or shortness of breath, persistent chest pain or pressure, or confusion.', 'Symptoms'),

('Hand Hygiene', 'Wash your hands thoroughly with soap and water for at least 20 seconds, or use an alcohol-based hand sanitizer with at least 60% alcohol.', 'Prevention'),
('Mask Usage', 'Wear a properly fitted multi-layer mask in crowded public places, poorly ventilated spaces, or when around individuals at high risk.', 'Prevention'),
('Respiratory Etiquette', 'Cover your mouth and nose with a bent elbow or tissue when coughing or sneezing, and dispose of used tissues immediately in a closed bin.', 'Prevention'),

('Social Distancing', 'Maintain a physical distance of at least 1 meter (3 feet) from others, especially from individuals who are coughing or showing flu-like symptoms.', 'Safety'),
('Indoor Ventilation', 'Ensure good ventilation in indoor settings by opening windows or using air purification systems to circulate fresh outdoor air.', 'Safety'),
('Staying Home When Sick', 'Isolate at home if you feel unwell or test positive for COVID-19 to prevent transmission to family members and coworkers.', 'Safety'),

('Importance of Vaccination', 'COVID-19 vaccines train your immune system to recognize and fight the coronavirus, significantly reducing the risk of severe illness, hospitalization, and death.', 'Vaccination'),
('Vaccine Protection & Boosters', 'Vaccines offer strong immunity, but booster doses help maintain high protection against evolving viral variants over time.', 'Vaccination'),

('Rest and Hydration', 'For mild cases, adequate rest, staying hydrated with fluids, and taking over-the-counter fever reducers can help relieve common symptoms.', 'Treatment'),
('When to Seek Medical Help', 'Consult healthcare professionals if symptoms worsen, or if you belong to high-risk groups such as elderly individuals or those with underlying health conditions.', 'Treatment');

-- Country COVID-19 Statistics Data (Module 3)
INSERT INTO `statistics` (`country`, `cases`, `recovered`, `deaths`) VALUES
('India', 45035300, 44500300, 533500),
('United States', 103430000, 101245000, 1180000),
('Brazil', 37700000, 36996000, 702000),
('United Kingdom', 24900000, 24650000, 228000),
('Germany', 38430000, 38240000, 175000),
('Japan', 33800000, 33720000, 74700);

-- Initial Admin Seed User (Module 4)
-- Default Password: AdminPass123! (Hashed securely via password_hash)
INSERT INTO `users` (`name`, `email`, `mobile`, `username`, `password`, `gender`, `role`) VALUES
('System Administrator', 'admin@covidshield.org', '9876543210', 'admin', '$2y$10$w8T0hR.tD5Wd5R5.e3t.2eN1S8n0xK3f2Z9q4L7v2X8W5m1P0Q3R2', 'Prefer not to say', 'admin');
