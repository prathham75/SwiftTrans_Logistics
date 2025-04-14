-- SwiftTrans Logistics Database Setup
-- Version: 1.0
-- Cleaned and Ready for Deployment

-- Create Database
CREATE DATABASE IF NOT EXISTS `swifttrans`;
USE `swifttrans`;

-- --------------------------------------------------------
-- Table: admins
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table: bookings
-- --------------------------------------------------------
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `sender_contact` varchar(15) NOT NULL,
  `sender_location` varchar(255) NOT NULL,
  `vehicle_type` enum('bike','small_tempo','truck') NOT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `drop_location` varchar(255) NOT NULL,
  `distance` double GENERATED ALWAYS AS (case when `pickup_location` = 'Sangli' and `drop_location` = 'Satara' then 200 when `pickup_location` = 'Sangli' and `drop_location` = 'Kolhapur' then 45 when `pickup_location` = 'Sangli' and `drop_location` = 'Solapur' then 250 when `pickup_location` = 'Sangli' and `drop_location` = 'Karad' then 50 when `pickup_location` = 'Satara' and `drop_location` = 'Kolhapur' then 200 when `pickup_location` = 'Satara' and `drop_location` = 'Solapur' then 300 when `pickup_location` = 'Satara' and `drop_location` = 'Karad' then 60 when `pickup_location` = 'Satara' and `drop_location` = 'Sangli' then 200 when `pickup_location` = 'Kolhapur' and `drop_location` = 'Solapur' then 270 when `pickup_location` = 'Kolhapur' and `drop_location` = 'Karad' then 55 when `pickup_location` = 'Kolhapur' and `drop_location` = 'Sangli' then 45 when `pickup_location` = 'Kolhapur' and `drop_location` = 'Satara' then 200 when `pickup_location` = 'Solapur' and `drop_location` = 'Satara' then 300 when `pickup_location` = 'Solapur' and `drop_location` = 'Kolhapur' then 300 when `pickup_location` = 'Solapur' and `drop_location` = 'Sangli' then 250 when `pickup_location` = 'Solapur' and `drop_location` = 'Karad' then 275 else 0 end) STORED,
  `weight` float NOT NULL,
  `receiver_name` varchar(100) NOT NULL,
  `receiver_contact` varchar(15) NOT NULL,
  `receiver_location` varchar(255) NOT NULL,
  `fare` double NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','on the way','dispatched','out for delivery','delivered') NOT NULL DEFAULT 'pending'
)  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table structure for table `vehicles`

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_type` enum('bike','small_tempo','truck') NOT NULL,
  `available_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `vehicles`

INSERT INTO `vehicles` (`id`, `vehicle_type`, `available_count`) VALUES
(1, 'bike', 16),
(2, 'small_tempo', 8),
(3, 'truck', 4);

-- --------------------------------------------------------
-- Optional: Insert Default Admin
-- --------------------------------------------------------
INSERT INTO `admins` (`name`, `email`, `password`) VALUES
('Admin User', 'admin@example.com', '$2y$10$wCtxpT3dckZREDcU.ny2hea6bCcnmzJSttABdf3aHFaYTbuVFuO.u');

-- --------------------------------------------------------
-- Commit
-- --------------------------------------------------------
COMMIT;

-- Finished Database Setup for SwiftTrans Logistics 🚀
