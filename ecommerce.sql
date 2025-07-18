-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2025 at 10:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `store_id`, `name`, `parent_id`, `created_at`) VALUES
(1, 2, 'cat -1', NULL, '2025-07-16 06:14:21'),
(2, 2, 'catt - 2', 1, '2025-07-16 06:32:05'),
(3, 2, 'cat - 3', NULL, '2025-07-16 06:32:13');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `store_id`, `product_id`, `stock`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 5, '2025-07-17 06:27:41', '2025-07-17 06:33:45'),
(2, 2, 2, 0, '2025-07-17 06:28:15', '2025-07-17 06:34:16'),
(3, 2, 3, 4, '2025-07-17 06:28:20', '2025-07-17 06:28:20');

-- --------------------------------------------------------

--
-- Table structure for table `master_admins`
--

CREATE TABLE `master_admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_admins`
--

INSERT INTO `master_admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Master Admin', 'masteradmin@gmail.com', 'masteradmin@123', '2025-07-18 05:58:04');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `store_id`, `user_id`, `customer_name`, `customer_email`, `phone`, `total`, `status`, `payment_method`, `created_at`, `address`) VALUES
(1, 2, NULL, NULL, NULL, NULL, 10.00, 'processing', NULL, '2025-07-16 06:59:39', NULL),
(2, 2, NULL, NULL, NULL, NULL, 23.00, 'pending', NULL, '2025-07-16 06:59:53', NULL),
(3, 2, NULL, NULL, NULL, NULL, 50.00, 'cancelled', NULL, '2025-07-16 07:01:09', NULL),
(4, 2, NULL, 'edrftgy', NULL, NULL, 195.00, 'delivered', 'cod', '2025-07-16 07:30:43', NULL),
(5, 2, NULL, 'pro 1', NULL, '123456789', 33.00, 'shipped', 'cod', '2025-07-16 07:33:21', NULL),
(6, 2, NULL, 'test 2', 'test@gmail.com', '12345678', 33.00, 'processing', 'upi', '2025-07-16 07:38:15', NULL),
(7, 2, NULL, 'test final ', 'final@gmail.co', '23456', 33.00, 'pending', 'cod', '2025-07-16 07:44:11', 'filabl'),
(8, 2, NULL, 'rtyui', 'dfghj@gmail.com', '345678', 50.00, 'pending', 'cod', '2025-07-17 06:33:45', 'ghjkl;'),
(9, 2, NULL, 'fg', 'j@gmail.com', '34567', 23.00, 'pending', 'cod', '2025-07-17 06:34:16', 'dfgv');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 10.00),
(2, 2, 2, 1, 23.00),
(3, 3, 3, 1, 50.00),
(4, 4, 3, 1, 50.00),
(5, 4, 2, 2, 23.00),
(6, 4, 6, 2, 33.00),
(7, 4, 5, 1, 33.00),
(8, 5, 6, 1, 33.00),
(9, 6, 5, 1, 33.00),
(10, 7, 5, 1, 33.00),
(11, 8, 1, 5, 10.00),
(12, 9, 2, 1, 23.00);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `module` varchar(50) NOT NULL,
  `action` enum('view','add','edit','delete') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `module`, `action`) VALUES
(1, 'categories', 'view'),
(2, 'categories', 'add'),
(3, 'categories', 'edit'),
(4, 'categories', 'delete'),
(21, 'inventory', 'view'),
(22, 'inventory', 'add'),
(23, 'inventory', 'edit'),
(24, 'inventory', 'delete'),
(9, 'orders', 'view'),
(10, 'orders', 'add'),
(11, 'orders', 'edit'),
(12, 'orders', 'delete'),
(5, 'products', 'view'),
(6, 'products', 'add'),
(7, 'products', 'edit'),
(8, 'products', 'delete'),
(17, 'roles', 'view'),
(18, 'roles', 'add'),
(19, 'roles', 'edit'),
(20, 'roles', 'delete'),
(13, 'users', 'view'),
(14, 'users', 'add'),
(15, 'users', 'edit'),
(16, 'users', 'delete');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `store_id`, `category_id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(1, 2, 1, 'product 1', 'yes', 10.00, 'product_1.png', '2025-07-16 06:15:34'),
(2, 2, 1, 'product 2', 'ef', 23.00, NULL, '2025-07-16 06:31:51'),
(3, 2, 3, 'product - 3', 'df', 50.00, NULL, '2025-07-16 06:32:46'),
(4, 2, 2, 'product 4', 'df', 55.00, NULL, '2025-07-16 06:33:06'),
(5, 2, 2, 'product 5', 'rf', 33.00, NULL, '2025-07-16 07:11:27'),
(6, 2, 2, 'product 5', 'rf', 33.00, NULL, '2025-07-16 07:12:12');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `store_id`, `name`, `description`) VALUES
(1, NULL, 'superadmin', 'Super Admin with all access'),
(3, 2, 'manager', 'manage only products'),
(6, 3, 'mart role 1', 'full assess'),
(7, 3, 'mart role 2', 'product manage'),
(8, 2, 'accounts', 'invoice only');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `store_id`, `role_id`, `permission_id`) VALUES
(6, 3, 6, 1),
(7, 3, 7, 9),
(24, 2, 3, 1),
(25, 2, 3, 2),
(26, 2, 3, 3),
(27, 2, 3, 21),
(28, 2, 3, 22),
(29, 2, 3, 5),
(30, 2, 3, 6),
(31, 2, 3, 7),
(32, 2, 3, 13),
(33, 2, 3, 15),
(34, 2, 3, 16),
(35, 2, 8, 9);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subdomain` varchar(50) NOT NULL,
  `owner_email` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `subdomain`, `owner_email`, `created_at`, `status`) VALUES
(1, 'kunalll mart', 'kunal', 'kunal@gmail.com', '2025-07-16 01:46:40', 'approved'),
(2, 'yash mart', 'yy', 'yash@gmail.com', '2025-07-16 02:20:33', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `store_id`, `name`, `email`, `password`, `role`, `role_id`, `created_at`, `status`) VALUES
(1, 1, 'Superadmin', 'kunal@gmail.com', '$2y$10$DohhmD7blZRq.lVOA6k9KuehvKAdO3swhFq0vVH5R0hX6QIgAwTbK', 'superadmin', 1, '2025-07-16 01:46:40', 'active'),
(2, 2, 'yash', 'yash@gmail.com', '$2y$10$yHforuWK5vZ7dHjECHH.8uqGEhF5zy4Zie.2YCJnwoPWdprZtWz2i', 'superadmin', 1, '2025-07-16 02:20:33', 'active'),
(6, 2, 'demo user', 'test1@gmail.com', '$2y$10$P6qB32VZEX/fDhigSNhcoeCTkZK8Hu.HlY6/17kqXFvvxsbYJFEpK', 'manager', 3, '2025-07-17 03:14:27', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `master_admins`
--
ALTER TABLE `master_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `module_action` (`module`,`action`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subdomain` (`subdomain`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `fk_users_role` (`role_id`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_admins`
--
ALTER TABLE `master_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `categories_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_inventory_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inventory_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
