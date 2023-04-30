-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: May 16, 2022 at 07:55 AM
-- Server version: 8.0.18
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_chateau`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_accounts`
--

DROP TABLE IF EXISTS `tbl_accounts`;
CREATE TABLE IF NOT EXISTS `tbl_accounts` (
  `auto_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(56) NOT NULL,
  `password` varchar(200) NOT NULL,
  `account_type` varchar(56) NOT NULL,
  PRIMARY KEY (`auto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf16;

--
-- Dumping data for table `tbl_accounts`
--

INSERT INTO `tbl_accounts` (`auto_id`, `name`, `email`, `password`, `account_type`) VALUES
(1, '', 'chadeujamilla@gmail.com', '$argon2i$v=19$m=2048,t=4,p=3$QzBLbDlpNDMxVnRQRzBtdg$9l4XJLK+gpoU2yvvixYehVwmNEdJHcaVsyC1QbDWQ2I', 'Admin'),
(2, '', 'marklesterrabi@gmail.com', '$argon2i$v=19$m=2048,t=4,p=3$MEtKUFpCRjU3QldUWEN5Zg$2olcJMTZZstla0hvPYCBVTFoAA3BXRUTTGmoW7KYBNQ', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_booking_rooms`
--

DROP TABLE IF EXISTS `tbl_booking_rooms`;
CREATE TABLE IF NOT EXISTS `tbl_booking_rooms` (
  `br_auto_increment_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(255) NOT NULL,
  `br_room_id` varchar(255) NOT NULL,
  `br_guest_id` varchar(255) NOT NULL,
  `br_guest_fname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `br_guest_lname` varchar(255) NOT NULL,
  `br_guest_contact_num` varchar(20) NOT NULL,
  `br_guest_email` varchar(244) NOT NULL,
  `br_checkin_date` date NOT NULL,
  `br_checkout_date` date NOT NULL,
  `br_number_of_days_to_stay` int(11) NOT NULL,
  `br_downpayment` int(11) NOT NULL,
  `br_payment_method` varchar(50) NOT NULL,
  `br_payment_reference_number` varchar(255) NOT NULL,
  `br_payment_status` varchar(50) NOT NULL,
  `br_booking_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `br_transaction_date` varchar(50) NOT NULL,
  PRIMARY KEY (`br_auto_increment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_booking_rooms`
--

INSERT INTO `tbl_booking_rooms` (`br_auto_increment_id`, `booking_id`, `br_room_id`, `br_guest_id`, `br_guest_fname`, `br_guest_lname`, `br_guest_contact_num`, `br_guest_email`, `br_checkin_date`, `br_checkout_date`, `br_number_of_days_to_stay`, `br_downpayment`, `br_payment_method`, `br_payment_reference_number`, `br_payment_status`, `br_booking_status`, `br_transaction_date`) VALUES
(1, 'CBR-706544', 'c4ca4238a0b923820dcc509a6f75849b', 'RJ-1278-2022', 'Richard Mark', 'Jamilla', '09265311278', 'chadeujamilla@gmail.com', '2022-04-21', '2022-04-23', 2, 3500, 'GCASH P2P', '#126516611', 'Pending', 'Pending', '2022-04-21 03:35:51 PM'),
(2, 'CBR-462975', 'c81e728d9d4c2f636f067f89cc14862c', 'RJ-1412-2022', 'Rolando', 'Jamilla', '09667821412', 'rolandojamilla@gmail.com', '2022-04-25', '2022-04-27', 2, 3000, 'GCASH P2P', '#1923809123112', 'Pending', 'Pending', '2022-04-25 09:52:08 AM'),
(3, 'CBR-93325', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 'KJ-2312-2022', 'King', 'June', '09876542312', 'king-june@gmail.com', '2022-04-27', '2022-04-29', 2, 2500, 'GCASH P2P', '#219380128313121', 'Pending', 'Pending', '2022-04-25 09:54:02 AM'),
(4, 'CBR-296805', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 'DP-1234-2022', 'Dey', 'Pinohermoso', '09267891234', 'deypino@gmail.com', '2022-04-25', '2022-04-26', 1, 1250, 'GCASH P2P', '#1029819021', 'Pending', 'Pending', '2022-04-25 10:14:29 AM'),
(5, 'CBR-518365', 'a87ff679a2f3e71d9181a67b7542122c', 'JC-5642-2022', 'Jose', 'Chan', '09258725642', 'josechan@gmail.com', '2022-04-25', '2022-04-30', 5, 6250, 'GCASH P2P', '#81297398123123', 'Pending', 'Pending', '2022-04-25 10:34:29 AM'),
(6, 'CBR-527261', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', 'PR-9373-2022', 'Prince', 'Rehuel', '09267209373', 'princerehuel@gmail.com', '2022-05-01', '2022-05-03', 2, 2500, 'GCASH P2P', '#918230913123211312', 'Pending', 'Pending', '2022-04-25 02:59:04 PM');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

DROP TABLE IF EXISTS `tbl_cart`;
CREATE TABLE IF NOT EXISTS `tbl_cart` (
  `cart_auto_id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_guest_id` varchar(255) NOT NULL,
  `cart_menu_item_id` varchar(255) NOT NULL,
  `cart_quantity` int(11) NOT NULL,
  `cart_added_on_date` varchar(255) NOT NULL,
  `cart_status` varchar(255) NOT NULL,
  PRIMARY KEY (`cart_auto_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_cart`
--

INSERT INTO `tbl_cart` (`cart_auto_id`, `cart_guest_id`, `cart_menu_item_id`, `cart_quantity`, `cart_added_on_date`, `cart_status`) VALUES
(1, 'G-2205255352', 'CM-98265', 1, '2022-05-02', 'PLACED'),
(2, 'G-2205255352', 'CM-631016', 1, '2022-05-02', 'PLACED');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu`
--

DROP TABLE IF EXISTS `tbl_menu`;
CREATE TABLE IF NOT EXISTS `tbl_menu` (
  `menu_auto_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_item_id` varchar(255) NOT NULL,
  `menu_item_name` varchar(255) NOT NULL,
  `menu_item_category` varchar(255) NOT NULL,
  `menu_item_description` varchar(255) NOT NULL,
  `menu_item_min_pax` int(11) NOT NULL,
  `menu_item_max_pax` int(11) NOT NULL,
  `menu_item_price` float NOT NULL,
  `menu_item_measurement` varchar(255) NOT NULL,
  `menu_item_unit_of_measurement` varchar(255) NOT NULL,
  `menu_item_preparation_time` varchar(255) NOT NULL,
  `menu_item_photo_link` varchar(255) NOT NULL,
  `menu_item_status` varchar(255) NOT NULL,
  PRIMARY KEY (`menu_auto_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_menu`
--

INSERT INTO `tbl_menu` (`menu_auto_id`, `menu_item_id`, `menu_item_name`, `menu_item_category`, `menu_item_description`, `menu_item_min_pax`, `menu_item_max_pax`, `menu_item_price`, `menu_item_measurement`, `menu_item_unit_of_measurement`, `menu_item_preparation_time`, `menu_item_photo_link`, `menu_item_status`) VALUES
(1, 'CM-98265', 'Arcinas (Dae Jae)', 'Silog', 'N/A', 1, 1, 150, '1', 'order', 'N/A', 'https://www.goodfreephotos.com/albums/food/fried-and-yummy-food.jpg', 'Available'),
(2, 'CM-813887', 'Tapsilog', 'Silog', 'N/A', 1, 1, 150, '1', 'order', 'N/A', 'https://thenotsocreativecook.files.wordpress.com/2013/07/thenotsocreativecook-wordpress-com-tapsilog.jpg', 'Available'),
(3, 'CM-194178', 'Porksilog', 'Silog', 'N/A', 1, 1, 150, '1', 'order', 'NA', 'https://www.rwmanila.com/web/silogue/img/porksilog_thumbnail.png', 'Available'),
(4, 'CM-509655', 'Spamsilog', 'Silog', 'N/A', 1, 1, 150, '1', 'order', 'N/A', 'N/A', 'Available'),
(5, 'CM-450505', 'Hungariansilog', 'Silog', 'N/A', 1, 1, 150, '1', 'order', 'N/A', 'https://hellolipa.com/wp-content/uploads/2018/01/hungarian-sausage.jpg', 'Available'),
(6, 'CM-344967', 'Cornsilog', 'Silog', 'N/A', 1, 1, 150, '1', 'order', 'N/A', 'N/A', 'Available'),
(7, 'CM-761574', 'Bangsilog', 'Silog', 'N/A', 1, 1, 150, '1', 'order', 'N/A', 'N/A', 'Available'),
(8, 'CM-958646', 'Footsilog', 'Silog', 'N/A', 1, 1, 150, '1', 'order', 'N/A', 'N/A', 'Available'),
(9, 'CM-631016', 'Pork Burger', 'Burger and Pizza', 'N/A', 1, 1, 200, '1', 'order', 'N/A', 'N/A', 'Available'),
(10, 'CM-569610', 'Beef Burger', 'Burger and Pizza', 'NA', 1, 1, 200, '1', 'order', 'NA', 'NA', 'Available'),
(11, 'CM-411997', 'Chicken Burger', 'Burger and Pizza', 'NA', 1, 1, 200, '1', 'order', 'NA', 'NA', 'Available'),
(12, 'CM-745081', 'Margarita Pizza', 'Burger and Pizza', 'NA', 1, 1, 250, '1', 'order', 'NA', 'NA', 'Available'),
(13, 'CM-287602', 'Hawaiian Pizza', 'Burger and Pizza', 'NA', 1, 1, 250, '1', 'order', 'NA', 'NA', 'Available'),
(14, 'CM-510660', 'Meat Lover Pizza', 'Burger and Pizza', 'N/A', 1, 1, 300, '1', 'order', 'NA', 'NA', 'Available'),
(15, 'CM-159325', 'Vegetables Pizza', 'Burger and Pizza', 'NA', 1, 1, 350, '1', 'order', 'NA', 'NA', 'Available'),
(16, 'CM-371601', 'Arcinas Pizza', 'Burger and Pizza', 'NA', 1, 1, 400, '1', 'order', 'NA', 'NA', 'Available'),
(17, 'CM-419549', 'Pepperoni Pizza', 'Burger and Pizza', 'NA', 1, 1, 400, '1', 'order', 'NA', 'NA', 'Available'),
(18, 'CM-108102', 'Seafoods Pizza', 'Burger and Pizza', 'NA', 1, 1, 400, '1', 'order', 'NA', 'NA', 'Available'),
(19, 'CM-440747', 'French Fries', 'Burger and Pizza', 'NA', 1, 1, 150, '1', 'order', 'NA', 'NA', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

DROP TABLE IF EXISTS `tbl_orders`;
CREATE TABLE IF NOT EXISTS `tbl_orders` (
  `order_auto_increment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) NOT NULL,
  `order_guest_id` varchar(255) NOT NULL,
  `order_guest_fname` varchar(255) NOT NULL,
  `order_guest_lname` varchar(255) NOT NULL,
  `order_guest_contact_number` varchar(255) NOT NULL,
  `order_guest_email` varchar(255) NOT NULL,
  `order_mode_of_order` varchar(255) NOT NULL,
  `order_pickup_or_deliver_date` date NOT NULL,
  `order_pickup_or_deliver_time` varchar(255) NOT NULL,
  `order_payment_method` varchar(255) NOT NULL,
  `order_total_amount_to_pay` varchar(255) NOT NULL,
  `order_payment_status` varchar(255) NOT NULL,
  `order_payment_reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `order_transaction_dateTime` varchar(255) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  PRIMARY KEY (`order_auto_increment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`order_auto_increment_id`, `order_id`, `order_guest_id`, `order_guest_fname`, `order_guest_lname`, `order_guest_contact_number`, `order_guest_email`, `order_mode_of_order`, `order_pickup_or_deliver_date`, `order_pickup_or_deliver_time`, `order_payment_method`, `order_total_amount_to_pay`, `order_payment_status`, `order_payment_reference_number`, `order_transaction_dateTime`, `order_status`) VALUES
(1, 'ORDR-20220502-73689', 'G-2205255352', 'Chad', 'Jamilla', '09265311278', 'chadeujamilla@gmail.com', 'Pickup', '2022-05-03', '16:05', 'Pickup', '350', 'Pending', '#13123121', 'May 2, 2022 | 03:48:16 PM', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rooms`
--

DROP TABLE IF EXISTS `tbl_rooms`;
CREATE TABLE IF NOT EXISTS `tbl_rooms` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_name` varchar(255) NOT NULL,
  `room_details` varchar(255) NOT NULL,
  `room_price` varchar(255) NOT NULL,
  `room_limit` varchar(255) NOT NULL,
  `room_photo_link` varchar(255) NOT NULL,
  `room_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`room_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_rooms`
--

INSERT INTO `tbl_rooms` (`room_id`, `room_name`, `room_details`, `room_price`, `room_limit`, `room_photo_link`, `room_status`) VALUES
(1, 'Room 1', '2 - 3 Pax <br> 2 Free Breakfast', '3500', '3', 'room-1.jpg', 'Available'),
(2, 'Room 2', '2 - 3 Pax <br> 2 Free Breakfast', '3000', '3', 'room-2.jpg', 'Available'),
(3, 'Room 3', '2 - 3 Pax <br> 2 Free Breakfast', '2500', '3', 'room-3.jpg', 'Available'),
(4, 'Room 4', '2 - 3 Pax <br> 2 Free Breakfast', '2500', '3', 'room-2.jpg', 'Available'),
(5, 'Room 5 | Family Room', '4-5 Pax <br> 2 Free Breakfast', '4000', '5', 'room-1.jpg', 'Available'),
(6, 'Room 6 | Family Room', '4-5 Pax <br> 2 Free Breakfast', '4000', '5', 'room-2.jpg', 'Available'),
(7, 'Room 7', '8-10 Pax <br> 2 Free Breakfast', '6000', '10', 'room-3.jpg', 'Available'),
(8, 'Room 8', '8-10 Pax <br> 2 Free Breakfast', '6000', '10', 'room-2.jpg', 'Available'),
(9, 'Room 9', '3-4 Pax <br> 2 Free Breakfast', '3000', '4', 'room-1.jpg', 'Available'),
(10, 'Room 10', '3-4 Pax <br> 2 Free Breakfast', '3500', '4', 'room-1.jpg', 'Available');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
