-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2013 at 09:33 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `online_order`
--
CREATE DATABASE IF NOT EXISTS `online_order` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `online_order`;

-- --------------------------------------------------------

--
-- Table structure for table `cart_order_items`
--

CREATE TABLE IF NOT EXISTS `cart_order_items` (
  `temp_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `item_id` int(11) NOT NULL,
  `selected_options` varchar(255) NOT NULL,
  `extra_items` varchar(255) NOT NULL,
  `extra_price` varchar(255) NOT NULL,
  `pizzaname` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `pizzatype` varchar(255) NOT NULL,
  `crusttype` varchar(255) NOT NULL,
  `option` varchar(255) NOT NULL,
  `toppingsideA` varchar(255) NOT NULL,
  `toppingsideB` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `pizzaid` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `selected_options_name` varchar(255) DEFAULT NULL,
  `extra_items_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`temp_order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `cart_order_items`
--

INSERT INTO `cart_order_items` (`temp_order_id`, `order_id`, `session_id`, `item_id`, `selected_options`, `extra_items`, `extra_price`, `pizzaname`, `size`, `price`, `pizzatype`, `crusttype`, `option`, `toppingsideA`, `toppingsideB`, `userid`, `status`, `pizzaid`, `item_name`, `selected_options_name`, `extra_items_name`) VALUES
(3, 2748359, '32a197366e30ce13b09661d30ba5164c', 105, '32,34', '35,36', '28', '', 0, '12.00', '', '', '', '', '', 11, 'enable', 0, 'pizza', 'extra cheese, extra topping', 'jalepeno, cheese'),
(4, 1827364, '6cd241a8fdd55ddc8c0f18d73448f0ba', 105, '32', '33,34', '23', '', 0, '12.00', '', '', '', '', '', 11, 'enable', 0, 'pizza', 'extra cheese, extra topping', 'jalepeno, cheese'),
(5, 1827364, '6cd241a8fdd55ddc8c0f18d73448f0ba', 109, '35', '34,35', '39', '', 0, '24.00', '', '', '', '', '', 11, 'enable', 0, 'pizza', 'extra cheese, extra topping', 'jalepeno, cheese'),
(6, 1827364, '6cd241a8fdd55ddc8c0f18d73448f0ba', 113, '34', '32,33', '51', '', 0, '45.00', '', '', '', '', '', 11, 'enable', 0, 'pizza', 'extra cheese, extra topping', 'jalepeno, cheese'),
(7, 1827364, '6cd241a8fdd55ddc8c0f18d73448f0ba', 0, '', '', '', 'Test', 12, '31.00', '', '', '', '', '', 11, 'enable', 67, 'pizza', 'extra cheese, extra topping', 'jalepeno, cheese'),
(8, 1827364, '6cd241a8fdd55ddc8c0f18d73448f0ba', 0, '', '', '', 'Custom Pizza', 12, '15.00', 'Custom', 'cheese', ',sauce_3 ,cheese_q', 'olive', 'ytftiy,olive', 11, 'enable', 0, 'pizza', 'extra cheese, extra topping', 'jalepeno, cheese'),
(9, 1613984, 'ab4e79fa2f1824ad31879431ed046de1', 0, '', '', '', 'Test', 12, '31.00', '', '', '', '', '', 11, 'enable', 67, 'pizza', 'extra cheese, extra topping', 'jalepeno, cheese'),
(10, 1613984, 'ab4e79fa2f1824ad31879431ed046de1', 0, '', '', '', 'Test', 12, '31.00', '', '', '', '', '', 11, 'enable', 67, 'pizza', 'extra cheese, extra topping', 'jalepeno, cheese');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `mainorder_id` int(15) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `order_date` date NOT NULL,
  `order_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time_mode` text NOT NULL,
  `status_deliver` text NOT NULL,
  `status_pickup` text NOT NULL,
  `status_dineup` text NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `add1` varchar(255) NOT NULL,
  `apt_no` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `dlinedate` varchar(45) NOT NULL,
  `dlinetime` varchar(25) NOT NULL,
  `appar_avail` varchar(12) NOT NULL,
  `subtotal` decimal(11,2) NOT NULL,
  `combo_dis` decimal(11,2) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_total` decimal(11,2) NOT NULL,
  `coupon_discount` decimal(11,2) NOT NULL,
  `tax` decimal(11,2) NOT NULL,
  `delivery_charge` decimal(11,2) NOT NULL,
  `order_status` int(11) NOT NULL,
  `payment_mode` varchar(12) NOT NULL,
  PRIMARY KEY (`mainorder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`mainorder_id`, `ip_address`, `session_id`, `order_date`, `order_time`, `time_mode`, `status_deliver`, `status_pickup`, `status_dineup`, `first_name`, `last_name`, `email`, `phone`, `add1`, `apt_no`, `city`, `zip`, `userid`, `status`, `dlinedate`, `dlinetime`, `appar_avail`, `subtotal`, `combo_dis`, `guest_id`, `user_id`, `order_total`, `coupon_discount`, `tax`, `delivery_charge`, `order_status`, `payment_mode`) VALUES
(1613984, '117.212.45.179', 'ab4e79fa2f1824ad31879431ed046de1', '2013-09-23', '2013-09-23 11:15:00', '', 'no', 'yes', 'no', 'apple ', 'jam', 'tanvi.geni@gmail.com', '1234-5678-789', 'Address 1', 74, ' Washington', 123456, 11, 'enable', '', '', '', '31.00', '0.00', 4, 3, '45.03', '20.00', '4.03', '10.00', 1, 'cod'),
(1827364, '59.89.204.15', '6cd241a8fdd55ddc8c0f18d73448f0ba', '2013-09-24', '2013-09-24 18:29:18', '', 'no', 'yes', 'no', 'apple ', 'jam', 'tanvi.geni@gmail.com', '4567-5678-5678', '', 0, '', 0, 11, 'enable', '', '', '', '159.00', '0.00', 0, 3, '189.67', '20.00', '20.67', '10.00', 1, 'cod'),
(2748359, '116.202.64.146', '32a197366e30ce13b09661d30ba5164c', '2013-09-23', '2013-09-23 11:18:37', '', 'no', 'yes', 'no', 'arti', 'arzoo', 'artiweb@projectpays.com', '123-454-555', '', 0, '', 0, 11, 'enable', '', '', '', '28.00', '0.00', 0, 2, '41.64', '20.00', '3.64', '10.00', 1, 'cod');

-- --------------------------------------------------------

--
-- Table structure for table `order_updt_status`
--

CREATE TABLE IF NOT EXISTS `order_updt_status` (
  `username` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `update_status` varchar(20) NOT NULL DEFAULT 'New',
  `mainorder_id` int(11) DEFAULT NULL, 
  UNIQUE KEY (`userid`, `status`, `mainorder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_updt_status`
--

INSERT INTO `order_updt_status` (`username`,`userid` ,`status`, `update_status`, `mainorder_id`) VALUES
('admin',11, '1', 'New', NULL),
('root',11, '3', 'Pending', 1827364),
('user',11, '2', 'Complete', 2748359);

-- --------------------------------------------------------

--
-- Table structure for table `usr_mgmnt`
--

CREATE TABLE IF NOT EXISTS `usr_mgmnt` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_status` varchar(10) DEFAULT 'disable',
  `role` int(2) NOT NULL DEFAULT '0',
  `userid` int(11) NULL,
  `status` varchar(255) NULL,
  `last_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `usr_mgmnt`
--

INSERT INTO `usr_mgmnt` (`user_id`,`username`, `password`, `user_status`, `role`,`userid`,`status`,`last_time`) VALUES
(NULL,'admin', 'admin', 'enable',1,11,'enable', '2013-09-25 04:55:27'),
(NULL,'root', '123', 'enable',0,0,'enable','2013-09-25 04:55:27'),
(NULL,'user', 'user', 'enable',0,11,'enable' ,'2013-09-25 04:55:27');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
