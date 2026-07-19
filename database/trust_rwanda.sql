-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql204.infinityfree.com
-- Generation Time: Jul 19, 2026 at 04:16 PM
-- Server version: 11.4.12-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_42407546_trust_rwanda`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_ads`
--

CREATE TABLE `active_ads` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `target_url` text DEFAULT NULL,
  `ad_type` enum('banner','notification','popup') DEFAULT NULL,
  `placement` varchar(50) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `priority` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `active_ads`
--

INSERT INTO `active_ads` (`id`, `title`, `target_url`, `ad_type`, `placement`, `image_url`, `start_date`, `end_date`, `priority`, `status`, `created_at`) VALUES
(5, 'Flash Sale: 20% Off Electronics', 'index.php?route=products&category=electronics', 'notification', NULL, NULL, NULL, NULL, 0, 'active', '2026-03-05 15:39:25');

-- --------------------------------------------------------

--
-- Table structure for table `ad_analytics`
--

CREATE TABLE `ad_analytics` (
  `id` int(11) NOT NULL,
  `ad_id` int(11) NOT NULL,
  `event_type` enum('view','click') DEFAULT 'view',
  `user_ip` varchar(45) DEFAULT NULL,
  `logged_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ad_inquiries`
--

CREATE TABLE `ad_inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `business` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `package` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('new','contacted','closed') DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ad_inquiries`
--

INSERT INTO `ad_inquiries` (`id`, `name`, `business`, `email`, `package`, `message`, `status`, `created_at`) VALUES
(1, 'Steven IMANZI', 'ICYEREKEZO', 'stivenimanzi1@gmail.com', 'Native Sponsored (25k/mo)', 'Hello', 'new', '2026-03-05 14:55:28');

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_commissions`
--

CREATE TABLE `affiliate_commissions` (
  `id` int(11) NOT NULL,
  `referrer_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_price` decimal(15,2) NOT NULL,
  `commission_amount` decimal(15,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `icon_class` varchar(50) DEFAULT NULL,
  `type` enum('general','farm') DEFAULT 'general'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon_class`, `type`) VALUES
(1, 'Electronics', 'electronics', 'bi-phone', 'general'),
(2, 'Fashion', 'fashion', 'bi-handbag', 'general'),
(3, 'Building Materials', 'building', 'bi-bricks', 'general'),
(4, 'Food & Groceries', 'food', 'bi-basket2', 'general'),
(5, 'Vegetables', 'vegetables', 'bi-flower1', 'farm'),
(6, 'Fruits', 'fruits', 'bi-apple', 'farm'),
(7, 'Dairy', 'dairy', 'bi-cup-straw', 'farm'),
(8, 'Meat & Poultry', 'meat', 'bi-egg-fried', 'farm'),
(9, 'Grains & Cereals', 'grains', 'bi-box-seam', 'farm'),
(10, 'Beverages', 'beverages', 'bi-cup-hot', 'general'),
(11, 'Beauty & Personal Care', 'beauty', 'bi-droplet-half', 'general'),
(12, 'Home & Living', 'home', 'bi-house', 'general'),
(13, 'Furniture', 'furniture', 'bi-lamp', 'general'),
(14, 'Kitchen & Dining', 'kitchen', 'bi-cup', 'general'),
(15, 'Electrical Supplies', 'electrical', 'bi-lightning-charge', 'general'),
(16, 'Hardware Tools', 'hardware', 'bi-wrench-adjustable', 'general'),
(17, 'Automotive', 'automotive', 'bi-truck', 'general'),
(18, 'Motorcycle Parts', 'motorcycle', 'bi-bicycle', 'general'),
(19, 'Books & Stationery', 'books', 'bi-book', 'general'),
(20, 'Baby & Kids', 'baby', 'bi-balloon-heart', 'general'),
(21, 'Sports & Fitness', 'sports', 'bi-trophy', 'general'),
(22, 'Health & Wellness', 'health', 'bi-heart-pulse', 'general'),
(23, 'Pet Supplies', 'pets', 'bi-heart', 'general'),
(24, 'Professional Services', 'services', 'bi-briefcase', 'general'),
(15726, 'Real Estate', 'real-estate', 'bi-buildings', ''),
(15727, 'Rent - House', 'rent-house', 'bi-house-door', ''),
(15728, 'Rent - Apartment', 'rent-apartment', 'bi-building', ''),
(15729, 'Rent - Guest House', 'rent-guest-house', 'bi-house-heart', ''),
(15730, 'Rent - Ghetto', 'rent-ghetto', 'bi-houses', ''),
(15731, 'Sale - Land', 'sale-land', 'bi-map', ''),
(15732, 'Sale - House', 'sale-house', 'bi-house-check', ''),
(16377, 'Mobile Phones', 'mobile-phones', 'bi-phone-fill', ''),
(16378, 'Laptops & Computers', 'laptops-computers', 'bi-laptop-fill', ''),
(16379, 'Accessories', 'accessories', 'bi-headphones', ''),
(16380, 'TV & Home Systems', 'tv-systems', 'bi-tv-fill', ''),
(16381, 'Cameras & Drones', 'cameras-drones', 'bi-camera-fill', ''),
(16382, 'CCTV & Security', 'cctv-security', 'bi-camera-video-fill', ''),
(16383, 'Speakers & Audio', 'speakers-audio', 'bi-speaker-fill', ''),
(16384, 'Gaming', 'gaming', 'bi-controller', ''),
(16923, 'Tablets & E-Readers', 'tablets', 'bi-tablet-fill', ''),
(16924, 'Smartwatches & Wearables', 'smartwatches', 'bi-smartwatch', ''),
(16925, 'Monitors & Displays', 'monitors', 'bi-display-fill', ''),
(16926, 'Printers & Scanners', 'printers-scanners', 'bi-printer-fill', ''),
(16927, 'Networking & Routers', 'network-routers', 'bi-router-fill', ''),
(16928, 'Smart Home Devices', 'smart-home', 'bi-lightbulb-fill', ''),
(16929, 'PC Components', 'pc-components', 'bi-motherboard-fill', ''),
(17015, 'Second Hand', 'second-hand', 'bi-recycle', 'general'),
(17016, 'Used Electronics', 'used-electronics', 'bi-laptop', ''),
(17017, 'Used Furniture', 'used-furniture', 'bi-lamp', ''),
(17018, 'Used Vehicles', 'used-vehicles', 'bi-car-front', ''),
(17019, 'Used Clothing', 'used-clothing', 'bi-handbag', ''),
(17020, 'Used Appliances', 'used-appliances', 'bi-tv', ''),
(19252, 'Used Mobile Phones', 'used-mobile-phones', 'bi-phone-fill', ''),
(19253, 'Used Televisions', 'used-televisions', 'bi-tv-fill', ''),
(19254, 'Used Laptops', 'used-laptops', 'bi-laptop-fill', ''),
(38080, 'Used Home Items', 'used-home-items', 'bi-house-fill', ''),
(39160, 'Home Automations System', 'home-automations', 'bi-house-gear-fill', '');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_percentage` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `is_read`, `created_at`) VALUES
(1, 17, 31, 'Hello', 0, '2026-06-08 03:46:35');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(191) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsletter_subscribers`
--

INSERT INTO `newsletter_subscribers` (`id`, `email`, `created_at`) VALUES
(1, 'stivenimanzi1@gmail.com', '2026-01-28 20:59:57'),
(2, 'imanzilylics@gmail.com', '2026-01-29 13:49:20'),
(3, 'clarisseigihozo96@gmail.com', '2026-03-03 01:06:53'),
(5, 'basanzihariyves250@gmail.com', '2026-03-05 09:29:16'),
(6, 'bavonsanctus5@gmail.com', '2026-03-05 09:29:16'),
(7, 'bayisengenadinenady@gmail.com', '2026-03-05 09:29:16'),
(8, 'benandre61@gmail.com', '2026-03-05 09:29:16'),
(9, 'benbaevun@gmail.com', '2026-03-05 09:29:16'),
(10, 'benitdeveloper@gmail.com', '2026-03-05 09:29:16'),
(11, 'bernardhurera@gmail.com', '2026-03-05 09:29:16'),
(12, 'beulacquilas@gmail.com', '2026-03-05 09:29:16'),
(13, 'bibwireyesuyehoyada@gmail.com', '2026-03-05 09:29:16'),
(14, 'bigenimanadjaludi@gmail.com', '2026-03-05 09:29:16'),
(15, 'bigirimana994@gmail.com', '2026-03-05 09:29:16'),
(16, 'bizimanibra@gmail.com', '2026-03-05 09:29:16'),
(17, 'bnjamin202@gmail.com', '2026-03-05 09:29:16'),
(18, 'bnzayisenga3566@gmail.com', '2026-03-05 09:29:16'),
(19, 'bonfilsnba2020@gmail.com', '2026-03-05 09:29:16'),
(20, 'bonheurirumva43@gmail.com', '2026-03-05 09:29:16'),
(21, 'bonheurtunezerwe70@gmail.com', '2026-03-05 09:29:16'),
(22, 'bosembohubert22@gmail.com', '2026-03-05 09:29:16'),
(23, 'bpacifique941@gmail.com', '2026-03-05 09:29:16'),
(24, 'bpraisezonic2018@gmail.com', '2026-03-05 09:29:16'),
(25, 'breezyshemab@gmail.com', '2026-03-05 09:29:16'),
(26, 'byiringirodieudonne550@gmail.com', '2026-03-05 09:29:16'),
(27, 'byiringirosamuel593@gmail.com', '2026-03-05 09:29:16'),
(28, 'cartermenlor@gmail.com', '2026-03-05 09:29:16'),
(29, 'cathiampinganzima7@gmail.com', '2026-03-05 09:29:16'),
(30, 'cedricktuyiramye@gmail.com', '2026-03-05 09:29:16'),
(31, 'celineabakundana@gmail.com', '2026-03-05 09:29:16'),
(32, 'chantaluwitonze6@gmail.com', '2026-03-05 09:29:16'),
(33, 'christopheb.matabaro@gmail.com', '2026-03-05 09:29:16'),
(34, 'claudineuwizeyimana2004@gmail.com', '2026-03-05 09:29:16'),
(35, 'cluwizeyimana9@gmail.com', '2026-03-05 09:29:16'),
(36, 'crispinni013@gmail.com', '2026-03-05 09:29:16'),
(37, 'cton2023@gmail.com', '2026-03-05 09:29:16'),
(38, 'cyubahirokevin38@gmail.com', '2026-03-05 09:29:16'),
(39, 'cyusagislain62@gmail.com', '2026-03-05 09:29:16'),
(40, 'cyuzuzofabrice17@gmail.com', '2026-03-05 09:29:16'),
(41, 'danieldushimimana14@gmail.com', '2026-03-05 09:29:16'),
(42, 'dannyransford3@gmail.com', '2026-03-05 09:29:16'),
(43, 'dariussebanani040@gmail.com', '2026-03-05 09:29:16'),
(44, 'dathiveabizeyimana6@gmail.com', '2026-03-05 09:29:16'),
(45, 'david.ng.rw@gmail.com', '2026-03-05 09:29:16'),
(46, 'davidmutuyi@gmail.com', '2026-03-05 09:29:16'),
(47, 'daviesmuhabwa200@gmail.com', '2026-03-05 09:29:16'),
(48, 'davkamjr2015@gmail.com', '2026-03-05 09:29:16'),
(49, 'deborahiradukunda1999@gmail.com', '2026-03-05 09:29:16'),
(50, 'deliceagasaro7@gmail.com', '2026-03-05 09:29:16'),
(51, 'desirenza5@gmail.com', '2026-03-05 09:29:16'),
(52, 'destamlino862@gmail.com', '2026-03-05 09:29:16'),
(53, 'dianemfitumukiza2@gmail.com', '2026-03-05 09:29:16'),
(54, 'didier.nizeyimana@yahoo.com', '2026-03-05 09:29:16'),
(55, 'dignitekn500@gmail.com', '2026-03-05 09:29:16'),
(56, 'divin2250@gmail.com', '2026-03-05 09:29:16'),
(57, 'divinemuhozaa@gmail.com', '2026-03-05 09:29:16'),
(58, 'dntaganda412@gmail.com', '2026-03-05 09:29:16'),
(59, 'donatilletuyizere0@gmail.com', '2026-03-05 09:29:16'),
(60, 'dukundaneegide05@gmail.com', '2026-03-05 09:29:16'),
(61, 'dukuzimanamugishafabrice@gmail.com', '2026-03-05 09:29:16'),
(62, 'dusabimana694@gmail.com', '2026-03-05 09:29:16'),
(63, 'dusengeaimeparfait@gmail.com', '2026-03-05 09:29:16'),
(64, 'dusengeemmy2000@gmail.com', '2026-03-05 09:29:16'),
(65, 'dushimeaimeplacide@gmail.com', '2026-03-05 09:29:16'),
(66, 'dushimel502@gmail.com', '2026-03-05 09:29:16'),
(67, 'dusingizimanaelise88@gmail.com', '2026-03-05 09:29:16'),
(68, 'edgaurdniyitanga@gmail.com', '2026-03-05 09:29:16'),
(69, 'edisonbizimana01@gmail.com', '2026-03-05 09:29:16'),
(70, 'egide.dushimire@gmail.com', '2026-03-05 09:29:16'),
(71, 'eiradukunda443@gmail.com', '2026-03-05 09:29:16'),
(72, 'eirakiranuka@gmail.com', '2026-03-05 09:29:16'),
(73, 'ekazihalirwa2002@gmail.com', '2026-03-05 09:29:16'),
(74, 'eliemwitirehe7@gmail.com', '2026-03-05 09:29:16'),
(75, 'eliesteven30@gmail.com', '2026-03-05 09:29:16'),
(76, 'elimwirinzi@gmail.com', '2026-03-05 09:29:16'),
(77, 'elisanshimyumikiza@gmail.com', '2026-03-05 09:29:16'),
(78, 'elisharugwiro@gmail.com', '2026-03-05 09:29:16'),
(79, 'elissamugambi@gmail.com', '2026-03-05 09:29:16'),
(80, 'emilemuhirwa12@gmail.com', '2026-03-05 09:29:16'),
(81, 'emmanueldushime9@gmail.com', '2026-03-05 09:29:16'),
(82, 'emmanuelnsabimana2@gmail.com', '2026-03-05 09:29:16'),
(83, 'emmybritta@gmail.com', '2026-03-05 09:29:16'),
(84, 'emmylingabire@gmail.com', '2026-03-05 09:29:16'),
(85, 'emmyman60@gmail.com', '2026-03-05 09:29:16'),
(86, 'emmymanirafasha1@gmail.com', '2026-03-05 09:29:16'),
(87, 'enockccg28@gmail.com', '2026-03-05 09:29:16'),
(88, 'enockmbagariye@gmail.com', '2026-03-05 09:29:16'),
(89, 'eriabtuyikunde@gmail.com', '2026-03-05 09:29:16'),
(90, 'ericniyobyose2050@gmail.com', '2026-03-05 09:29:16'),
(91, 'ericnkunda95@gmail.com', '2026-03-05 09:29:16'),
(92, 'ernesteuwa723@gmail.com', '2026-03-05 09:29:16'),
(93, 'espharpicket@gmail.com', '2026-03-05 09:29:16'),
(94, 'esront21@gmail.com', '2026-03-05 09:29:16'),
(95, 'eugeniehabimana@gmail.com', '2026-03-05 09:29:16'),
(96, 'euladorudahunga@gmail.com', '2026-03-05 09:29:16'),
(97, 'evertishimwe@gmail.com', '2026-03-05 09:29:16'),
(98, 'evodemrtn@gmail.com', '2026-03-05 09:29:16'),
(99, 'fiacrekim68@gmail.com', '2026-03-05 09:29:16'),
(100, 'francoisniyomugabo043@gmail.com', '2026-03-05 09:29:16'),
(101, 'francoisregis003@gmail.com', '2026-03-05 09:29:16'),
(102, 'frankkatu88@gmail.com', '2026-03-05 09:29:16'),
(103, 'furahauwiringiyimana47@gmail.com', '2026-03-05 09:29:16'),
(104, 'gabrieltuyishimire35@gmail.com', '2026-03-05 09:29:16'),
(105, 'gadnsengiyumva11@gmail.com', '2026-03-05 09:29:16'),
(106, 'gashemavalens1@gmail.com', '2026-03-05 09:29:16'),
(107, 'gatetehodali2@gmail.com', '2026-03-05 09:29:16'),
(108, 'gaylordgondopd@gmail.com', '2026-03-05 09:29:16'),
(109, 'gaziz01@gmail.com', '2026-03-05 09:29:16'),
(110, 'genadeimanishimwe150@gmail.com', '2026-03-05 09:29:16'),
(111, 'geoffreydanielhabimana@gmail.com', '2026-03-05 09:29:16'),
(112, 'giftdamas350@gmail.com', '2026-03-05 09:29:16'),
(113, 'gilbertmuramira@gmail.com', '2026-03-05 09:29:16'),
(114, 'gira14phoc@gmail.com', '2026-03-05 09:29:16'),
(115, 'gislainemahorogislaine12@gmail.com', '2026-03-05 09:29:16'),
(116, 'gislmusabyemariya@gmail.com', '2026-03-05 09:29:16'),
(117, 'gisubizocedrick720@gmail.com', '2026-03-05 09:29:16'),
(118, 'gisubizosandrine33@gmail.com', '2026-03-05 09:29:16'),
(119, 'gonzalezipierre@gmail.com', '2026-03-05 09:29:16'),
(120, 'gracebitega@gmail.com', '2026-03-05 09:29:16'),
(121, 'graceuwamahirwe0@gmail.com', '2026-03-05 09:29:16'),
(122, 'gracieuxacademic@gmail.com', '2026-03-05 09:29:16'),
(123, 'gwizabruno345@gmail.com', '2026-03-05 09:29:16'),
(124, 'habibsixbert01@gmail.com', '2026-03-05 09:29:16'),
(125, 'hagebobo2024@gmail.com', '2026-03-05 09:29:16'),
(126, 'hagenimanaemmanuel10@gmail.com', '2026-03-05 09:29:16'),
(127, 'hakizimanajanviermanager@gmail.com', '2026-03-05 09:29:16'),
(128, 'hakizimanajean000@gmail.com', '2026-03-05 09:29:16'),
(129, 'hakoragilbert1@gmail.com', '2026-03-05 09:29:16'),
(130, 'hashakimanadavy@gmail.com', '2026-03-05 09:29:16'),
(131, 'hasubizimanajeanpaul@gmail.com', '2026-03-05 09:29:16'),
(132, 'herveishimwe740@gmail.com', '2026-03-05 09:29:16'),
(133, 'higiroemile1@gmail.com', '2026-03-05 09:29:16'),
(134, 'hirwaarsene9@gmail.com', '2026-03-05 09:29:16'),
(135, 'hirwadavid3@gmail.com', '2026-03-05 09:29:16'),
(136, 'hirwamaudrey@gmail.com', '2026-03-05 09:29:16'),
(137, 'hjeandamour2000@gmail.com', '2026-03-05 09:29:16'),
(138, 'honorensabo@gmail.com', '2026-03-05 09:29:16'),
(139, 'hosianamfitumukiza@gmail.com', '2026-03-05 09:29:16'),
(140, 'hstonevich@gmail.com', '2026-03-05 09:29:16'),
(141, 'htuyinama2010@gmail.com', '2026-03-05 09:29:16'),
(142, 'i.muvunyi@alustudent.com', '2026-03-05 09:29:16'),
(143, 'ie.togetha@gmail.com', '2026-03-05 09:29:16'),
(144, 'iformule00@gmail.com', '2026-03-05 09:29:16'),
(145, 'igjonathan211@gmail.com', '2026-03-05 09:29:16'),
(146, 'ihimbazwemugenzi1', '2026-03-05 09:29:16'),
(147, 'ihsanmigambi@gmail.com', '2026-03-05 09:29:16'),
(148, 'ilandry575@gmail.com', '2026-03-05 09:29:16'),
(149, 'imanirakaramajonas@gmail.com', '2026-03-05 09:29:16'),
(150, 'imanirihodarius@gmail.com', '2026-03-05 09:29:16'),
(151, 'imanizabayostiven4@gmail.com', '2026-03-05 09:29:16'),
(152, 'imbahafiobed510@gmail.com', '2026-03-05 09:29:16'),
(153, 'inezagrace19@gmail.com', '2026-03-05 09:29:16'),
(154, 'inezaodence@gmail.com', '2026-03-05 09:29:16'),
(155, 'ingabiremariegrace143@gmail.com', '2026-03-05 09:29:16'),
(156, 'innocentiyabishatse@gmail.com', '2026-03-05 09:29:16'),
(157, 'innocentnkurunziza76@gmail.com', '2026-03-05 09:29:16'),
(158, 'innocentntaho96@gmail.com', '2026-03-05 09:29:16'),
(159, 'iradukundaaphlouis1@gmail.com', '2026-03-05 09:29:16'),
(160, 'iradukundagendrepatient@gmail.com', '2026-03-05 09:29:16'),
(161, 'iradukundahonoreclever18@gmail.com', '2026-03-05 09:29:16'),
(162, 'iradukundapatrick001@gmail.com', '2026-03-05 09:29:16'),
(163, 'iradukundasalima2020@gmail.com', '2026-03-05 09:29:16'),
(164, 'iraduolivier420@gmail.com', '2026-03-05 09:29:16'),
(165, 'irageraka@gmail.com', '2026-03-05 09:29:16'),
(166, 'irahozachantal@gmail.com', '2026-03-05 09:29:16'),
(167, 'irakozedieudonne220@gmail.com', '2026-03-05 09:29:16'),
(168, 'irakozehawamu92@gmail.com', '2026-03-05 09:29:16'),
(169, 'irakozepac101@gmail.com', '2026-03-05 09:29:16'),
(170, 'irankundasam1998@gmail.com', '2026-03-05 09:29:16'),
(171, 'irasubizajeannepo@gmail.com', '2026-03-05 09:29:16'),
(172, 'irizihidajac@gmail.com', '2026-03-05 09:29:16'),
(173, 'irumvasamuel670@gmail.com', '2026-03-05 09:29:16'),
(174, 'ishimirwebertin7@gmail.com', '2026-03-05 09:29:16'),
(175, 'ishimweemmanuel033@gmail.com', '2026-03-05 09:29:16'),
(176, 'ishimwejaphet08@gmail.com', '2026-03-05 09:29:16'),
(177, 'ishimwejeanclaude088@gmail.com', '2026-03-05 09:29:16'),
(178, 'ishimwemoses457@gmail.com', '2026-03-05 09:29:16'),
(179, 'ishimwemoses95@gmail.com', '2026-03-05 09:29:16'),
(180, 'ishimwenancy16@gmail.com', '2026-03-05 09:29:16'),
(181, 'ishimwepatrick774@gmail.com', '2026-03-05 09:29:16'),
(182, 'ishimwepaul@gmail.com', '2026-03-05 09:29:16'),
(183, 'ismaeldukuzumuremyi77@gmail.com', '2026-03-05 09:29:16'),
(184, 'ismaeldukuzumuremyi9@gmail.com', '2026-03-05 09:29:16'),
(185, 'isingizwegisele214@gmail.com', '2026-03-05 09:29:16'),
(186, 'isingizwelvis@gmail.com', '2026-03-05 09:29:16'),
(187, 'isingizwepierregonzalezi@gmail.com', '2026-03-05 09:29:16'),
(188, 'janviermugisha65@gmail.com', '2026-03-05 09:29:16'),
(189, 'janvieru24@gmail.com', '2026-03-05 09:29:16'),
(190, 'japhetnshimiyimana424@gmail.com', '2026-03-05 09:29:16'),
(191, 'jeanbaptistemuhoza73@gmail.com', '2026-03-05 09:29:16'),
(192, 'jeanclaudeishimwe003@gmail.com', '2026-03-05 09:29:16'),
(193, 'jeancloudeniyonkuru07@gmail.com', '2026-03-05 09:29:16'),
(194, 'jeanclovis2000@gmail.com', '2026-03-05 09:29:16'),
(195, 'jeandamourkubwimana0@gmail.com', '2026-03-05 09:29:16'),
(196, 'jeandamourt81@gmail.com', '2026-03-05 09:29:16'),
(197, 'jeandedieihagenimana@gmail.com', '2026-03-05 09:29:16'),
(198, 'jeandedieuuwayezu843@gmail.com', '2026-03-05 09:29:16'),
(199, 'jerivendatimana6@gmail.com', '2026-03-05 09:29:16'),
(200, 'jiyakaremye32@gmail.com', '2026-03-05 09:29:16'),
(201, 'jkanyarwanda1@gmail.com', '2026-03-05 09:29:16'),
(202, 'jmunyaneza789@gmail.com', '2026-03-05 09:29:16'),
(203, 'joelhirwa9@gmail.com', '2026-03-05 09:29:16'),
(204, 'johndeng1844@gmail.com', '2026-03-05 09:29:16'),
(205, 'johnmarc2k18@gmail.com', '2026-03-05 09:29:16'),
(206, 'jomarly21@gmail.com', '2026-03-05 09:29:16'),
(207, 'judithnyiragwaneza@gmail.com', '2026-03-05 09:29:16'),
(208, 'justinniyigena7@gmail.com', '2026-03-05 09:29:16'),
(209, 'kanzayiremariepascale@gmail.com', '2026-03-05 09:29:16'),
(210, 'karorero15@gmail.com', '2026-03-05 09:29:16'),
(211, 'kaygettingrich79@gmail.com', '2026-03-05 09:29:16'),
(212, 'kayirangwa1aline@gmail.com', '2026-03-05 09:29:16'),
(213, 'kazimotoolivier8@gmail.com', '2026-03-05 09:29:16'),
(214, 'keneseclarisse8@gmail.com', '2026-03-05 09:29:16'),
(215, 'kevinngabo06@gmail.com', '2026-03-05 09:29:16'),
(216, 'kghady@gmail.com', '2026-03-05 09:29:16'),
(217, 'kingeric2995@gmail.com', '2026-03-05 09:29:16'),
(218, 'kiranzigilbert12@gmail.com', '2026-03-05 09:29:16'),
(219, 'kubwimanapierre86@gmail.com', '2026-03-05 09:29:16'),
(220, 'kuzopaccy@gmail.com', '2026-03-05 09:29:16'),
(221, 'kwizerafidele2000@gmail.com', '2026-03-05 09:29:16'),
(222, 'kwizerajmv68@gmail.com', '2026-03-05 09:29:16'),
(223, 'kwizeram977@gmail.com', '2026-03-05 09:29:16'),
(224, 'kwizerangogaclement@gmail.com', '2026-03-05 09:29:16'),
(225, 'kwizertheogene94@gmail.com', '2026-03-05 09:29:16'),
(226, 'kwzrthieery@gmail.com', '2026-03-05 09:29:16'),
(227, 'leatitiauwamahoro04@gmail.com', '2026-03-05 09:29:16'),
(228, 'leonakingeneye2002@gmail.com', '2026-03-05 09:29:16'),
(229, 'leonamahoro1@gmail.com', '2026-03-05 09:29:16'),
(230, 'leonardndikumana15@gmail.com', '2026-03-05 09:29:16'),
(231, 'lilioseuwase82@gmail.com', '2026-03-05 09:29:16'),
(232, 'lillianuwase169@gmail.com', '2026-03-05 09:29:16'),
(233, 'linzyalice89@gmail.com', '2026-03-05 09:29:16'),
(234, 'loxhypolite553@gmail.com', '2026-03-05 09:29:16'),
(235, 'luckwizera10@gmail.com', '2026-03-05 09:29:16'),
(236, 'm.kbonheur12@gmail.com', '2026-03-05 09:29:16'),
(237, 'manirakizadi22@gmail.com', '2026-03-05 09:29:16'),
(238, 'manishimwedarius70@gmail.com', '2026-03-05 09:29:16'),
(239, 'manzibahizibertin@gmail.com', '2026-03-05 09:29:16'),
(240, 'manzijean967@gmail.com', '2026-03-05 09:29:16'),
(241, 'manzijustin7@gmail.com', '2026-03-05 09:29:16'),
(242, 'maombedavis123@gmail.com', '2026-03-05 09:29:16'),
(243, 'mapaci25@gmail.com', '2026-03-05 09:29:16'),
(244, 'marieclairemutes@gmail.com', '2026-03-05 09:29:16'),
(245, 'marielleroxane037@gmail.com', '2026-03-05 09:29:16'),
(246, 'mbanatheophil@gmail.com', '2026-03-05 09:29:16'),
(247, 'mbayihaismael@gmail.com', '2026-03-05 09:29:16'),
(248, 'mbuguje6@gmail.com', '2026-03-05 09:29:16'),
(249, 'mdushimimana6@gmail.com', '2026-03-05 09:29:16'),
(250, 'melissauwajeneza@gmail.com', '2026-03-05 09:29:16'),
(251, 'mercimicomyiza@gmail.com', '2026-03-05 09:29:16'),
(252, 'minanieric73@gmail.com', '2026-03-05 09:29:16'),
(253, 'minanisamuel2@gmail.com', '2026-03-05 09:29:16'),
(254, 'mireilleumutesi6@gmail.com', '2026-03-05 09:29:16'),
(255, 'mniyibizieli@gmail.com', '2026-03-05 09:29:16'),
(256, 'moisehakizimana50@gmail.com', '2026-03-05 09:29:16'),
(257, 'moiseiranejeje@gmail.com', '2026-03-05 09:29:16'),
(258, 'mosesuwitonze12345@gmail.com', '2026-03-05 09:29:16'),
(259, 'moussamanager44@gmail.com', '2026-03-05 09:29:16'),
(260, 'mprincehonore@gmail.com', '2026-03-05 09:29:16'),
(261, 'mucuzidavis257@gmail.com', '2026-03-05 09:29:16'),
(262, 'mucyoh32@gmail.com', '2026-03-05 09:29:16'),
(263, 'mucyunejehirwaarsene@gmail.com', '2026-03-05 09:29:16'),
(264, 'mugipatrick60@gmail.com', '2026-03-05 09:29:16'),
(265, 'mugishaarcel@gmail.com', '2026-03-05 09:29:16'),
(266, 'mugishapascal58@gmail.com', '2026-03-05 09:29:16'),
(267, 'muhairwegideon27@gmail.com', '2026-03-05 09:29:16'),
(268, 'muhawenimanakeria@gmail.com', '2026-03-05 09:29:16'),
(269, 'muhireaimedidier@gmail.com', '2026-03-05 09:29:16'),
(270, 'muhirelionel@gmail.com', '2026-03-05 09:29:16'),
(271, 'muhirwajbsc@gmail.com', '2026-03-05 09:29:16'),
(272, 'muhiziamanda08@gmail.com', '2026-03-05 09:29:16'),
(273, 'muhorakeyeakanyanaarielle@gmail.com', '2026-03-05 09:29:16'),
(274, 'mukarusinelilian@gmail.com', '2026-03-05 09:29:16'),
(275, 'mukwiyejules80@gmail.com', '2026-03-05 09:29:16'),
(276, 'mulouis12@gmail.com', '2026-03-05 09:29:16'),
(277, 'munezeroarnoldcharles69@gmail.com', '2026-03-05 09:29:16'),
(278, 'munezeronadine01@gmail.com', '2026-03-05 09:29:16'),
(279, 'munyaneza.jpaul2018@gmail.com', '2026-03-05 09:29:16'),
(280, 'munyawera123456789@gmail.com', '2026-03-05 09:29:16'),
(281, 'munybenjamin078@gmail.com', '2026-03-05 09:29:16'),
(282, 'munyemana770@gmail.com', '2026-03-05 09:29:16'),
(283, 'mupenz250@gmail.com', '2026-03-05 09:29:16'),
(284, 'murenzif01@gmail.com', '2026-03-05 09:29:16'),
(285, 'murenzimo@gmail.com', '2026-03-05 09:29:16'),
(286, 'musabyimanajeanclaude088@gmail.com', '2026-03-05 09:29:16'),
(287, 'mushimiyumukizab@gmail.com', '2026-03-05 09:29:16'),
(288, 'musokeedouard@gmail.com', '2026-03-05 09:29:16'),
(289, 'musonirodriguez@gmail.com', '2026-03-05 09:29:16'),
(290, 'mutoniyvette824@gmail.com', '2026-03-05 09:29:16'),
(291, 'mutuyimanadaddy22@gmail.com', '2026-03-05 09:29:16'),
(292, 'mwambukablando4@gmail.com', '2026-03-05 09:29:16'),
(293, 'nanastase23@gmail.com', '2026-03-05 09:29:16'),
(294, 'naomeimanishimwe7@gmail.com', '2026-03-05 09:29:16'),
(295, 'nayiturikodile48@gmail.com', '2026-03-05 09:29:16'),
(296, 'ndahimanaj54@gmail.com', '2026-03-05 09:29:16'),
(297, 'ndahimanavincent200@gmail.com', '2026-03-05 09:29:16'),
(298, 'ndateimnemmanuel914@gmail.com', '2026-03-05 09:29:16'),
(299, 'ndatimanasamuel1@gmail.com', '2026-03-05 09:29:16'),
(300, 'ndibwirevale@gmail.com', '2026-03-05 09:29:16'),
(301, 'ndungutseeric304@gmail.com', '2026-03-05 09:29:16'),
(302, 'ne.blorin@gmail.com', '2026-03-05 09:29:16'),
(303, 'neemanaomi01@gmail.com', '2026-03-05 09:29:16'),
(304, 'nelgash20@gmail.com', '2026-03-05 09:29:16'),
(305, 'nellyrugaju@gmail.com', '2026-03-05 09:29:16'),
(306, 'ngabof250@gmail.com', '2026-03-05 09:29:16'),
(307, 'ngendahimanajeandedieu43@gmail.com', '2026-03-05 09:29:16'),
(308, 'ngiriyabandijeanpierre@gmail.com', '2026-03-05 09:29:16'),
(309, 'nhonore091@gmail.com', '2026-03-05 09:29:16'),
(310, 'nikorelias20@gmail.com', '2026-03-05 09:29:16'),
(311, 'nishimwedanny6@gmail.com', '2026-03-05 09:29:16'),
(312, 'nisingizweviviane@gmail.com', '2026-03-05 09:29:16'),
(313, 'nitworks44@gmail.com', '2026-03-05 09:29:16'),
(314, 'niweherbert@gmail.com', '2026-03-05 09:29:16'),
(315, 'niyaaugustin002@gmail.com', '2026-03-05 09:29:16'),
(316, 'niyigabatheo10@gmail.com', '2026-03-05 09:29:16'),
(317, 'niyitegekajeanbruno@gmail.com', '2026-03-05 09:29:16'),
(318, 'niyobaggy123@gmail.com', '2026-03-05 09:29:16'),
(319, 'niyobyoseddy03@gmail.com', '2026-03-05 09:29:16'),
(320, 'niyogangelique@gmail.com', '2026-03-05 09:29:16'),
(321, 'niyogisubizoclaude1@gmail.com', '2026-03-05 09:29:16'),
(322, 'niyogisubizofabrice9@gmail.com', '2026-03-05 09:29:16'),
(323, 'niyogushimwapacifique6@gmail.com', '2026-03-05 09:29:16'),
(324, 'niyomahorojeancardin@gmail.com', '2026-03-05 09:29:16'),
(325, 'niyomufasha456@gmail.com', '2026-03-05 09:29:16'),
(326, 'niyomugaboelisa23@gmail.com', '2026-03-05 09:29:16'),
(327, 'niyomugabofelix9@gmail.com', '2026-03-05 09:29:16'),
(328, 'niyomugabog415@gmail.com', '2026-03-05 09:29:16'),
(329, 'niyonizeye85@gmail.com', '2026-03-05 09:29:16'),
(330, 'niyonkuruphil@gmail.com', '2026-03-05 09:29:16'),
(331, 'niyonsengacarine@gmail.com', '2026-03-05 09:29:16'),
(332, 'niyonsengaelie25@gmail.com', '2026-03-05 09:29:16'),
(333, 'niyonsengaemmanuel371@gmail.com', '2026-03-05 09:29:16'),
(334, 'niyonshutuyizereeric@gmail.com', '2026-03-05 09:29:16'),
(335, 'niyonshutiyves2004@gmail.com', '2026-03-05 09:29:16'),
(336, 'niyonshutiyves7@gmail.com', '2026-03-05 09:29:16'),
(337, 'nionzimathero3000@gmail.com', '2026-03-05 09:29:16'),
(338, 'niyoruremapatrick@gmail.com', '2026-03-05 09:29:16'),
(339, 'nizeyimananoa12@gmail.com', '2026-03-05 09:29:16'),
(340, 'nizeyimanasilvan84@gmail.com', '2026-03-05 09:29:16'),
(341, 'nizeyimanayvesarcade@gmail.com', '2026-03-05 09:29:16'),
(342, 'nizkevine55@gmail.com', '2026-03-05 09:29:16'),
(343, 'njsk3x@gmail.com', '2026-03-05 09:29:16'),
(344, 'nkernest666@gmail.com', '2026-03-05 09:29:16'),
(345, 'nkotanyifredson@gmail.com', '2026-03-05 09:29:16'),
(346, 'nkurikiyimanaaimable217@gmail.com', '2026-03-05 09:29:16'),
(347, 'nkusijstn@gmail.com', '2026-03-05 09:29:16'),
(348, 'nrenovatusbruce@gmail.com', '2026-03-05 09:29:16'),
(349, 'nsabimanabrice@gmail.com', '2026-03-05 09:29:16'),
(350, 'nsekohygue@gmail.com', '2026-03-05 09:29:16'),
(351, 'nsengajames22@gmail.com', '2026-03-05 09:29:16'),
(352, 'nshimiyemmy240@gmail.com', '2026-03-05 09:29:16'),
(353, 'nshimiyenepos@gmail.com', '2026-03-05 09:29:16');
INSERT INTO `newsletter_subscribers` (`id`, `email`, `created_at`) VALUES
(354, 'nshimiyimana827@gmail.com', '2026-03-05 09:29:16'),
(355, 'nsoroisrael@gmail.com', '2026-03-05 09:29:16'),
(356, 'ntagisimanaseth1@gmail.com', '2026-03-05 09:29:16'),
(357, 'ntakirutimanab906@gmail.com', '2026-03-05 09:29:16'),
(358, 'ntakirutimanabenjamin2000@gmail.com', '2026-03-05 09:29:16'),
(359, 'ntibibarebabenni@gmail.com', '2026-03-05 09:29:16'),
(360, 'ntwariguyaxel1@gmail.com', '2026-03-05 09:29:16'),
(361, 'nzamurambahoprince84@gmail.com', '2026-03-05 09:29:16'),
(362, 'nzasengimanasamuel1@gmail.com', '2026-03-05 09:29:16'),
(363, 'nzayisengam9@gmail.com', '2026-03-05 09:29:16'),
(364, 'nzizadonatien@gmail.com', '2026-03-05 09:29:16'),
(365, 'ogasominali@gmail.com', '2026-03-05 09:29:16'),
(366, 'oishimwe201@gmail.com', '2026-03-05 09:29:16'),
(367, 'oishteen@gmail.com', '2026-03-05 09:29:16'),
(368, 'onlythenotes@gmail.com', '2026-03-05 09:29:16'),
(369, 'orishabajanice@gmail.com', '2026-03-05 09:29:16'),
(370, 'ospreybruce813@gmail.com', '2026-03-05 09:29:16'),
(371, 'pacifique.irakoze77@gmail.com', '2026-03-05 09:29:16'),
(372, 'pacifiqueniyokwizerwapazzo@gmail.com', '2026-03-05 09:29:16'),
(373, 'pacifiqueumwari20@gmail.com', '2026-03-05 09:29:16'),
(374, 'pacimugwaneza2005@gmail.com', '2026-03-05 09:29:16'),
(375, 'paffdaddy06@gmail.com', '2026-03-05 09:29:16'),
(376, 'pascalopa7s@gmail.com', '2026-03-05 09:29:16'),
(377, 'patiencenambajimana@gmail.com', '2026-03-05 09:29:16'),
(378, 'patiencentagisanimana@gmail.com', '2026-03-05 09:29:16'),
(379, 'patrickkwizera495@gmail.com', '2026-03-05 09:29:16'),
(380, 'patrickpazzo19@gmail.com', '2026-03-05 09:29:16'),
(381, 'paulatwine23@gmail.com', '2026-03-05 09:29:16'),
(382, 'paulnshizirungu250@gmail.com', '2026-03-05 09:29:16'),
(383, 'peterland2002@gmail.com', '2026-03-05 09:29:16'),
(384, 'phensab28@gmail.com', '2026-03-05 09:29:16'),
(385, 'pnsabyamahoro@gmail.com', '2026-03-05 09:29:16'),
(386, 'princeira6@gmail.com', '2026-03-05 09:29:16'),
(387, 'radarcy34@gmail.com', '2026-03-05 09:29:16'),
(388, 'ramyumuremyiraymond@gmail.com', '2026-03-05 09:29:16'),
(389, 'rbonane336@gmail.com', '2026-03-05 09:29:16'),
(390, 'reberojeanpatience@gmail.com', '2026-03-05 09:29:16'),
(391, 'registuyizere783@gmail.com', '2026-03-05 09:29:16'),
(392, 'rehemafisher@gmail.com', '2026-03-05 09:29:16'),
(393, 'remyrakoze@gmail.com', '2026-03-05 09:29:16'),
(394, 'richardrwabukumba@gmail.com', '2026-03-05 09:29:16'),
(395, 'rjeanfelix2@gmail.com', '2026-03-05 09:29:16'),
(396, 'roberthakizimana090@gmail.com', '2026-03-05 09:29:16'),
(397, 'rogerbizi10@gmail.com', '2026-03-05 09:29:16'),
(398, 'romainbaraka25@gmail.com', '2026-03-05 09:29:16'),
(399, 'rugabamarius55@gmail.com', '2026-03-05 09:29:16'),
(400, 'rugambafer18@gmail.com', '2026-03-05 09:29:16'),
(401, 'rukundoalex317@gmail.com', '2026-03-05 09:29:16'),
(402, 'rurakassim2020@gmail.com', '2026-03-05 09:29:16'),
(403, 'rusibaneoscar@gmail.com', '2026-03-05 09:29:16'),
(404, 'ruthkarigirwad@gmail.com', '2026-03-05 09:29:16'),
(405, 'rwemafre123@gmail.com', '2026-03-05 09:29:16'),
(406, 's.abdurahma@alustudent.com', '2026-03-05 09:29:16'),
(407, 'salainiyo2016@gmail.com', '2026-03-05 09:29:16'),
(408, 'saltondeveloper@gmail.com', '2026-03-05 09:29:16'),
(409, 'samlite250@gmail.com', '2026-03-05 09:29:16'),
(410, 'sandrinetoni4@gmail.com', '2026-03-05 09:29:16'),
(411, 'sangwankiranuye@gmail.com', '2026-03-05 09:29:16'),
(412, 'santosmusinga@gmail.com', '2026-03-05 09:29:16'),
(413, 'savagesinger950@gmail.com', '2026-03-05 09:29:16'),
(414, 'sdike5354@gmail.com', '2026-03-05 09:29:16'),
(415, 'sebanania242@gmail.com', '2026-03-05 09:29:16'),
(416, 'sengwamanaemeran@gmail.com', '2026-03-05 09:29:16'),
(417, 'sergekirenga@gmail.com', '2026-03-05 09:29:16'),
(418, 'shadiahrashid@gmail.com', '2026-03-05 09:29:16'),
(419, 'shamiipriince@gmail.com', '2026-03-05 09:29:16'),
(420, 'shemaenock81@gmail.com', '2026-03-05 09:29:16'),
(421, 'shemafrankie@gmail.com', '2026-03-05 09:29:16'),
(422, 'shemapacifique990@gmail.com', '2026-03-05 09:29:16'),
(423, 'shimwaarsene@gmail.com', '2026-03-05 09:29:16'),
(424, 'shimwagodisone@gmail.com', '2026-03-05 09:29:16'),
(425, 'shimwamubyeyifor2003@gmail.com', '2026-03-05 09:29:16'),
(426, 'shyakabrnd@gmail.com', '2026-03-05 09:29:16'),
(427, 'shyakacedric04@gmail.com', '2026-03-05 09:29:16'),
(428, 'sindayigayasamuel38@gmail.com', '2026-03-05 09:29:16'),
(429, 'sindjuvenal@gmail.com', '2026-03-05 09:29:16'),
(430, 'sostheneniyomugaba37@gmail.com', '2026-03-05 09:29:16'),
(431, 'steveandy050@gmail.com', '2026-03-05 09:29:16'),
(432, 'sunstarpotopoti@gmail.com', '2026-03-05 09:29:16'),
(433, 'tasalainsalem1@gmail.com', '2026-03-05 09:29:16'),
(434, 'tdamour001@gmail.com', '2026-03-05 09:29:16'),
(435, 'telesphoren2018@gmail.com', '2026-03-05 09:29:16'),
(436, 'theobardharerimana74@gmail.com', '2026-03-05 09:29:16'),
(437, 'tobi@ictchamber.rw', '2026-03-05 09:29:16'),
(438, 'tuyiringires665@gmail.com', '2026-03-05 09:29:16'),
(439, 'tuyiringirevenuste@gmail.com', '2026-03-05 09:29:16'),
(440, 'tuyisengejeandamascene50@gmail.com', '2026-03-05 09:29:16'),
(441, 'tuyisengevalens250@gmail.com', '2026-03-05 09:29:16'),
(442, 'tuyishimireangelo@gmail.com', '2026-03-05 09:29:16'),
(443, 'tuyishimireelisa607@gmail.com', '2026-03-05 09:29:16'),
(444, 'tuyishimirevedaste022@gmail.com', '2026-03-05 09:29:16'),
(445, 'tuyitheo12@gmail.com', '2026-03-05 09:29:16'),
(446, 'tuzawillyjackson@gmail.com', '2026-03-05 09:29:16'),
(447, 'twagirimanainezza@gmail.com', '2026-03-05 09:29:16'),
(448, 'twambazimanairenee@gmail.com', '2026-03-05 09:29:16'),
(449, 'ud26500@gmail.com', '2026-03-05 09:29:16'),
(450, 'uhiriweelisa@gmail.com', '2026-03-05 09:29:16'),
(451, 'umignonne80@gmail.com', '2026-03-05 09:29:16'),
(452, 'umugababinprince@gmail.com', '2026-03-05 09:29:16'),
(453, 'umuhiredivine234@gmail.com', '2026-03-05 09:29:16'),
(454, 'umuhozadivine013@gmail.com', '2026-03-05 09:29:16'),
(455, 'umuhozasifasandrine@gmail.com', '2026-03-05 09:29:16'),
(456, 'umulisagermaine55@gmail.com', '2026-03-05 09:29:16'),
(457, 'umuragwa001@gmail.com', '2026-03-05 09:29:16'),
(458, 'umutonixxx@gmail.com', '2026-03-05 09:29:16'),
(459, 'uwaclara295@gmail.com', '2026-03-05 09:29:16'),
(460, 'uwajoy112@gmail.com', '2026-03-05 09:29:16'),
(461, 'uwimanaelisabeth123@gmail.com', '2026-03-05 09:29:16'),
(462, 'uwinezagideon@gmail.com', '2026-03-05 09:29:16'),
(463, 'uwingeliromeo@gmail.com', '2026-03-05 09:29:16'),
(464, 'uwituzepriscillah@gmail.com', '2026-03-05 09:29:16'),
(465, 'uwoyezantijee@gmail.com', '2026-03-05 09:29:16'),
(466, 'valentinemukundufite@gmail.com', '2026-03-05 09:29:16'),
(467, 'viateurirasubiza@gmail.com', '2026-03-05 09:29:16'),
(468, 'vincentniyikorabyose@gmail.com', '2026-03-05 09:29:16'),
(469, 'wisemanbrian373@gmail.com', '2026-03-05 09:29:16'),
(470, 'yankurijecyprien76@gmail.com', '2026-03-05 09:29:16'),
(471, 'yorandan13@gmail.com', '2026-03-05 09:29:16'),
(472, 'yvesgeno@outlook.com', '2026-03-05 09:29:16'),
(473, 'zrichmondsaye@gmail.com', '2026-03-05 09:29:16'),
(474, 'ollyviex@gmail.com', '2026-03-09 02:07:16'),
(475, 'dusabimanadative12@gmail.com', '2026-04-19 18:03:42'),
(476, 'imanzilabs@gmail.com', '2026-07-16 02:39:41');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `type` enum('order','system','message') DEFAULT 'system',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL DEFAULT 'momo',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `delivery_status` enum('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
  `delivery_address` text DEFAULT NULL,
  `delivery_phone` varchar(20) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `payment_method`, `payment_status`, `delivery_status`, `delivery_address`, `delivery_phone`, `transaction_id`, `created_at`) VALUES
(21, 17, '30000.00', 'whatsapp', 'paid', 'delivered', 'North,Gicumbi,Gaaseke', '0729786730', 'KURA_865A90EB', '2026-03-13 12:59:21'),
(22, 34, '400000.00', 'whatsapp', 'paid', 'confirmed', 'Rwanda/West/Rutsiro', '+1 (785) 914-2059', 'KURA_463C5D32', '2026-03-14 14:04:48'),
(23, 34, '145000.00', 'whatsapp', 'pending', 'delivered', 'Musanze', '+1 (785) 914-2059', 'KURA_971EBAD6', '2026-03-14 14:28:34'),
(24, 35, '36000.00', 'whatsapp', 'pending', 'confirmed', 'Musanze', '0787777777', 'KURA_4E43CDD7', '2026-03-18 09:39:16'),
(25, 36, '12000.00', 'whatsapp', 'pending', 'cancelled', 'Byumba', '0783116696', 'KURA_631C227C', '2026-04-19 23:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_purchase` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `price_unit` varchar(20) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `is_fresh_produce` tinyint(1) DEFAULT 0,
  `harvest_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `batch_number` varchar(50) DEFAULT NULL,
  `is_visible` tinyint(1) DEFAULT 1,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_flash_deal` tinyint(1) DEFAULT 0,
  `discount_percent` int(11) DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `views_count` int(11) DEFAULT 0,
  `promo_status` enum('none','pending','active','rejected') DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `category_id`, `category`, `title`, `description`, `price`, `price_unit`, `image_url`, `stock_quantity`, `is_fresh_produce`, `harvest_date`, `expiry_date`, `batch_number`, `is_visible`, `views`, `created_at`, `is_flash_deal`, `discount_percent`, `updated_at`, `views_count`, `promo_status`) VALUES
(210, 16, 15726, 'real-estate', 'Laborum Professional Real Estate', 'Iure numquam eius placeat aut iusto illum voluptas corrupti. Est nostrum excepturi praesentium. Molestiae error ducimus soluta dolor fugiat. Assumenda facere adipisci qui ut velit expedita consequatur.', '190303.00', '', 'storage/products/real-estate-6a581cc9ef143.jpg', 49, 0, NULL, NULL, NULL, 1, 0, '2026-07-15 23:50:34', 1, 0, '2026-07-15 23:50:34', 448, 'none'),
(211, 16, 15726, 'real-estate', 'Pariatur Professional Real Estate', 'Quam accusamus atque ducimus modi qui. Voluptate voluptates illum molestias est nemo. Qui debitis veniam consectetur et. Dolorum sunt ipsa quam sunt non laboriosam.', '308800.00', '', 'storage/products/real-estate-6a581cca04f9d.jpg', 78, 0, NULL, NULL, NULL, 1, 0, '2026-07-15 23:50:34', 1, 0, '2026-07-15 23:50:34', 72, 'none'),
(212, 16, 15726, 'real-estate', 'Magni Professional Real Estate', 'Quisquam eaque dolorem dolorem sed similique. Aut est quod rerum sed dignissimos corrupti ducimus officia. Ad facilis culpa labore. Quia debitis voluptatem aliquam dicta aut.', '1225422.00', '', 'storage/products/real-estate-6a581cca08e60.jpg', 82, 0, NULL, NULL, NULL, 1, 0, '2026-07-15 23:50:34', 1, 0, '2026-07-15 23:50:34', 294, 'none'),
(213, 16, 15726, 'real-estate', 'Corporis Professional Real Estate', 'Quidem iusto reiciendis est ut. Necessitatibus rerum cumque et nemo laborum. Error numquam nesciunt tempora laboriosam distinctio.', '1541444.00', '', 'storage/products/real-estate-6a581cca0d4c5.jpg', 25, 0, NULL, NULL, NULL, 1, 0, '2026-07-15 23:50:34', 0, 0, '2026-07-15 23:50:34', 95, 'none');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL COMMENT 'FK to users.id with real_estate_owner role',
  `property_type` enum('house','apartment','land','commercial') NOT NULL DEFAULT 'house',
  `listing_type` enum('rent','sale') NOT NULL DEFAULT 'sale',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `price_period` enum('once','monthly','yearly') NOT NULL DEFAULT 'once' COMMENT 'Payment frequency (for rent)',
  `address` varchar(255) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `sector` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `status` enum('available','sold','rented','pending') NOT NULL DEFAULT 'pending' COMMENT 'Approval status',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Admin verified listing',
  `youtube_video_id` varchar(20) DEFAULT NULL COMMENT 'Stores only the YouTube video ID',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `owner_id`, `property_type`, `listing_type`, `title`, `description`, `price`, `price_period`, `address`, `district`, `sector`, `latitude`, `longitude`, `status`, `is_verified`, `youtube_video_id`, `created_at`, `updated_at`) VALUES
(1, 101, 'house', 'rent', 'Inzu nziza ikodeshwa i Nyarutarama', 'Inzu nshya ifite ibyumba 4, uruganiriro runini, n’ubusitani bwiza kwaya.', '1500000.00', 'monthly', 'KG 270 St', 'Gasabo', 'Nyarutarama', '-1.93540000', '30.09940000', 'available', 1, 'https://assets.mixki', '2026-07-01 09:56:01', '2026-07-01 11:31:38'),
(2, 102, 'apartment', 'rent', 'Apartama yoroshye i Kiyovu', 'Apartama y’icyumba kimwe n’uruganiriro, ifite ibikoresho byose byo mu nzu. Itekanye cyane.', '600000.00', 'monthly', 'KN 50 St', 'Nyarugenge', 'Kiyovu', '-1.95360000', '30.06040000', 'available', 1, '', '2026-07-01 09:56:01', '2026-07-01 11:31:38'),
(3, 103, 'land', 'sale', 'Ikibanza kiza kigurishwa i Kabeza', 'Ikibanza kinini cyo kubakamo inzu yo guturamo, kiri hafi y’umuhanda wa kaburimbo.', '25000000.00', '', 'KK 18 Ave', 'Kicukiro', 'Kanombe', '-1.97120000', '30.13450000', 'available', 0, '', '2026-07-01 09:56:01', '2026-07-01 11:31:38'),
(4, 101, 'house', 'sale', 'Inzu igurishwa i Gacuriro', 'Inzu igezweho ifite ibyumba 5, n’icyumba cy’abashyitsi, n’igaraje ry’imidoka ziri mu mbuga.', '180000000.00', '', 'KG 402 St', 'Gasabo', 'Kinyinya', '-1.92150000', '30.08820000', 'available', 1, 'https://assets.mixki', '2026-07-01 09:56:01', '2026-07-01 11:31:38'),
(5, 104, 'commercial', 'rent', 'Ibiro bikodeshwa mu Mujyi rwagati', 'Ahantu heza ho gukorera ibiro, hafi y’amabanki n’amaduka. Harimo amazi n’amashanyarazi ahoraho.', '1200000.00', 'monthly', 'KN 4 Ave', 'Nyarugenge', 'Nyarugenge', '-1.94420000', '30.06170000', 'available', 1, '', '2026-07-01 09:56:01', '2026-07-01 11:31:38'),
(6, 105, 'house', 'rent', 'Inzu ihendutse i Kimironko', 'Inzu ifite ibyumba 3 n’ubwogero bubiri, iri hafi y’isoko rya Kimironko.', '350000.00', 'monthly', 'KG 11 Ave', 'Gasabo', 'Kimironko', '-1.93670000', '30.12340000', 'available', 0, '', '2026-07-01 09:56:01', '2026-07-01 11:31:38'),
(7, 102, 'apartment', 'rent', 'Apartama nshya i Kicukiro Sonatubes', 'Apartama zigezweho zifite ibyumba 2, harimo n’umurinzi ku muryango amanywa n’ijoro.', '800000.00', 'monthly', 'KK 15 Rd', 'Kicukiro', 'Kagarama', '-1.96310000', '30.09870000', 'available', 1, 'https://assets.mixki', '2026-07-01 09:56:01', '2026-07-01 11:31:38'),
(8, 106, 'land', 'sale', 'Ikibanza cy’ubuhinzi i Musanze', 'Ikibanza cyiza cy’ubuhinzi kiri hafi y’ikirunga, ubutaka bwera cyane.', '8000000.00', '', 'Muhanda wa Kinigi', 'Musanze', 'Kinigi', '-1.43220000', '29.58940000', 'available', 1, '', '2026-07-01 09:56:01', '2026-07-01 11:31:38'),
(9, 107, 'house', 'sale', 'Inzu itararangira i Rebero', 'Inzu igeze ku gubakwa igisenge, ifite ishusho nziza cyane ireba umujyi wa Kigali.', '45000000.00', '', 'KK 312 St', 'Kicukiro', 'Gatenga', '-1.98920000', '30.07110000', 'available', 0, '', '2026-07-01 09:56:01', '2026-07-01 11:31:38'),
(10, 103, 'apartment', 'sale', 'Apartama yo kugura i Kibagabaga', 'Apartama ishyitse ifite ibyumba 3, igikoni kigezweho, n’aho guparka imodoka.', '95000000.00', '', 'KG 313 St', 'Gasabo', 'Kimihurura', '-1.93040000', '30.11190000', 'available', 1, 'https://assets.mixki', '2026-07-01 09:56:01', '2026-07-01 11:31:38'),
(11, 16, 'house', 'sale', 'Beautiful House For Sale in Kigali', 'Ratione quis amet iste ducimus ullam. Culpa temporibus fugiat exercitationem. Dolores vel voluptatibus rerum numquam. Velit alias sint accusantium assumenda unde sapiente quibusdam provident.', '138825138.00', 'once', '8074 Barrows Locks', 'Kicukiro', 'Remera', '-1.95310000', '30.05350000', 'available', 1, NULL, '2026-07-15 23:50:33', '2026-07-15 23:50:33'),
(12, 16, 'house', 'rent', 'Luxury House For Rent in Kigali', 'Illo modi et cumque aut accusamus. Aut perspiciatis ex ut officia aut est. Amet nisi rerum consequatur nam totam tempora voluptatum repellat. Dolor sunt commodi quia est non facere recusandae.', '1339706.00', 'monthly', '50679 Izaiah Canyon', 'Kicukiro', 'Remera', '-1.94160000', '30.06400000', 'available', 1, NULL, '2026-07-15 23:50:37', '2026-07-15 23:50:37'),
(13, 16, 'house', 'rent', 'Modern House For Rent in Kigali', 'Et aliquam ut nisi soluta suscipit rerum. Ipsam est ullam id et. Necessitatibus minus nemo eum.', '1080109.00', 'monthly', '3391 Marlee Plaza', 'Nyarugenge', 'Remera', '-1.94190000', '30.06090000', 'available', 1, NULL, '2026-07-15 23:50:41', '2026-07-15 23:50:41'),
(14, 16, 'house', 'sale', 'Beautiful House For Sale in Kigali', 'Aut amet porro at odio labore consequatur quidem. Minima ut facere maxime non culpa veritatis voluptas nostrum. Consequatur nostrum vero omnis ipsa exercitationem aliquam.', '94380583.00', 'once', '3588 Fisher Burgs Suite 757', 'Nyarugenge', 'Remera', '-1.94210000', '30.05500000', 'available', 1, NULL, '2026-07-15 23:50:44', '2026-07-15 23:50:44'),
(15, 16, 'apartment', 'sale', 'Spacious Apartment For Sale in Kigali', 'Modi aut sint asperiores voluptas sequi quia. Est tempore ipsam nam voluptatibus dolores ipsa placeat. Voluptas voluptatibus consequatur quisquam et repudiandae commodi.', '187613722.00', 'once', '776 Lexus Fields', 'Gasabo', 'Remera', '-1.93690000', '30.07170000', 'available', 1, NULL, '2026-07-15 23:50:48', '2026-07-15 23:50:48'),
(16, 16, 'apartment', 'rent', 'Spacious Apartment For Rent in Kigali', 'Voluptatem consequuntur et error aliquam voluptatibus asperiores ipsum ea. Hic ut omnis totam quas deserunt debitis. Quis nihil ea inventore suscipit. Non ea commodi quis accusantium est non doloremque. Dolorem et quas tempore autem voluptate rerum veritatis itaque.', '453103.00', 'monthly', '80074 Colleen Trafficway Apt. 713', 'Kicukiro', 'Remera', '-1.93600000', '30.06660000', 'available', 1, NULL, '2026-07-15 23:50:52', '2026-07-15 23:50:52'),
(17, 16, 'apartment', 'sale', 'Modern Apartment For Sale in Kigali', 'Laboriosam dolores molestias nesciunt accusamus molestiae est veritatis. Quas rerum fugit distinctio est commodi. Illum deserunt laudantium at aliquid qui quia velit. Ratione suscipit accusamus non error nisi consequatur repellendus. Aperiam quo totam non voluptate necessitatibus dignissimos aut.', '95475592.00', 'once', '502 Collins Parks', 'Kicukiro', 'Remera', '-1.94480000', '30.06590000', 'available', 1, NULL, '2026-07-15 23:50:57', '2026-07-15 23:50:57'),
(18, 16, 'apartment', 'rent', 'Modern Apartment For Rent in Kigali', 'Perspiciatis maiores nisi aliquam omnis. Omnis tempora quia quaerat aut ex nihil ut non. Veritatis odio quam soluta et.', '249581.00', 'monthly', '554 Bryana Burg Apt. 307', 'Gasabo', 'Remera', '-1.95090000', '30.06390000', 'available', 1, NULL, '2026-07-15 23:51:00', '2026-07-15 23:51:00'),
(19, 16, 'land', 'rent', 'Spacious Land For Rent in Kigali', 'Aliquam incidunt atque aut tenetur voluptas. Magni doloribus qui voluptas autem tempore possimus pariatur. Tenetur odio sit quo rerum. Sequi ut nihil repellat fugit. Sunt corrupti odio quos dolores et odio eius doloremque.', '613344.00', 'monthly', '6009 Jerrell Motorway Apt. 694', 'Gasabo', 'Remera', '-1.95310000', '30.05800000', 'available', 1, NULL, '2026-07-15 23:51:04', '2026-07-15 23:51:04'),
(20, 16, 'land', 'rent', 'Spacious Land For Rent in Kigali', 'Eum autem numquam modi deleniti. Quaerat assumenda distinctio enim tenetur ut. Ad sint non fugiat ab. Numquam vitae omnis adipisci quaerat labore architecto labore.', '322920.00', 'monthly', '106 Murazik Causeway', 'Gasabo', 'Remera', '-1.94050000', '30.06600000', 'available', 1, NULL, '2026-07-15 23:51:07', '2026-07-15 23:51:07'),
(21, 16, 'land', 'rent', 'Modern Land For Rent in Kigali', 'Repellat ad in enim eius cum. Omnis enim quas consequatur omnis. Perspiciatis suscipit dolore optio est est. Doloremque qui adipisci id cumque. Reprehenderit nulla quia et. Consequatur quia fugiat beatae tempora dolore delectus.', '208837.00', 'monthly', '8844 Stella Ridge', 'Nyarugenge', 'Remera', '-1.94230000', '30.06940000', 'available', 1, NULL, '2026-07-15 23:51:11', '2026-07-15 23:51:11'),
(22, 16, 'land', 'sale', 'Luxury Land For Sale in Kigali', 'Consequuntur nihil odit repellat ut aspernatur quod. Odit quas praesentium cum molestiae. Voluptatem maxime autem minima in odit. Nostrum aut in voluptatem voluptatem beatae reprehenderit doloribus.', '62636673.00', 'once', '26091 Wilkinson Meadow Suite 061', 'Kicukiro', 'Remera', '-1.94890000', '30.06950000', 'available', 1, NULL, '2026-07-15 23:51:14', '2026-07-15 23:51:14'),
(23, 16, 'commercial', 'sale', 'Prime Commercial For Sale in Kigali', 'Dicta libero eaque ipsa. Tempore ipsum facere rem. Culpa doloribus odio rem et non deleniti fuga. Molestiae autem ut molestiae est possimus eum. Tempora quibusdam cumque nam dolorem dolor ut saepe rerum.', '104953409.00', 'once', '89146 Estefania Ways', 'Gasabo', 'Remera', '-1.94020000', '30.06780000', 'available', 1, NULL, '2026-07-15 23:51:18', '2026-07-15 23:51:18'),
(24, 16, 'commercial', 'sale', 'Prime Commercial For Sale in Kigali', 'Quo quaerat quis facilis excepturi quidem aliquam possimus. Enim eos aut autem repellat repudiandae voluptatem. Enim vitae ullam consequatur quae excepturi exercitationem non unde. Tenetur blanditiis quod molestiae in omnis libero commodi.', '294269365.00', 'once', '25445 Alva Cliffs Suite 568', 'Gasabo', 'Remera', '-1.94900000', '30.06660000', 'available', 1, NULL, '2026-07-15 23:51:25', '2026-07-15 23:51:25'),
(25, 16, 'commercial', 'rent', 'Spacious Commercial For Rent in Kigali', 'Quo consectetur aut placeat doloremque. Consequatur necessitatibus enim ut in non. Nulla quam ut ut saepe est quam dolor. Facere voluptas et quo. Consequuntur velit et enim consequatur sit.', '1150082.00', 'monthly', '50392 Rohan Turnpike Apt. 676', 'Nyarugenge', 'Remera', '-1.95000000', '30.06570000', 'available', 1, NULL, '2026-07-15 23:51:28', '2026-07-15 23:51:28'),
(26, 16, 'commercial', 'rent', 'Luxury Commercial For Rent in Kigali', 'Voluptatem aut sint quisquam iure. Ut dolor ea veniam itaque eos. Dignissimos ut earum nihil et expedita.', '1408996.00', 'monthly', '2989 Jeramie Knoll', 'Kicukiro', 'Remera', '-1.93480000', '30.06440000', 'available', 1, NULL, '2026-07-15 23:51:31', '2026-07-15 23:51:31');

-- --------------------------------------------------------

--
-- Table structure for table `property_features`
--

CREATE TABLE `property_features` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `feature_name` varchar(100) NOT NULL COMMENT 'e.g., Bedrooms, Bathrooms, Plot Size (sqm)',
  `feature_value` varchar(255) NOT NULL COMMENT 'e.g., 4, 3, 500'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_features`
--

INSERT INTO `property_features` (`id`, `property_id`, `feature_name`, `feature_value`) VALUES
(1, 1, 'Bedrooms', '4'),
(2, 1, 'Bathrooms', '3'),
(3, 1, 'Size (sqm)', '350'),
(4, 1, 'Parking Spaces', '3'),
(5, 1, 'Security', '24/7 Guarded'),
(6, 1, 'Water Tank', 'Yes'),
(7, 1, 'Generator', 'Yes'),
(8, 2, 'Bedrooms', '1'),
(9, 2, 'Bathrooms', '1'),
(10, 2, 'Size (sqm)', '60'),
(11, 2, 'Furnished', 'Yes'),
(12, 2, 'Cleaning Service', 'Yes'),
(13, 2, 'High Speed Internet', 'Yes'),
(14, 3, 'Bedrooms', '0'),
(15, 3, 'Bathrooms', '0'),
(16, 3, 'Size (sqm)', '800'),
(17, 3, 'Fenced', 'Yes'),
(18, 3, 'Electricity Connection', 'Yes'),
(19, 3, 'Water Connection', 'Yes'),
(20, 4, 'Bedrooms', '5'),
(21, 4, 'Bathrooms', '4'),
(22, 4, 'Size (sqm)', '450'),
(23, 4, 'Swimming Pool', 'Yes'),
(24, 4, 'Modern Kitchen', 'Yes'),
(25, 4, 'Maid Quarters', 'Yes'),
(26, 5, 'Bedrooms', '0'),
(27, 5, 'Bathrooms', '2'),
(28, 5, 'Size (sqm)', '180'),
(29, 5, 'Fiber Internet', 'Yes'),
(30, 5, 'Elevator Access', 'Yes'),
(31, 5, 'Air Conditioning', 'Yes'),
(32, 6, 'Bedrooms', '3'),
(33, 6, 'Bathrooms', '2'),
(34, 6, 'Size (sqm)', '160'),
(35, 6, 'Water Tank', 'Yes'),
(36, 6, 'Parking Space', '2 Cars'),
(37, 6, 'Near Market', 'Yes'),
(38, 7, 'Bedrooms', '2'),
(39, 7, 'Bathrooms', '2'),
(40, 7, 'Size (sqm)', '110'),
(41, 7, 'Balcony', 'Yes'),
(42, 7, 'Paved Road Access', 'Yes'),
(43, 7, '24/7 Security', 'Yes'),
(44, 8, 'Bedrooms', '0'),
(45, 8, 'Bathrooms', '0'),
(46, 8, 'Size (sqm)', '5000'),
(47, 8, 'Fertile Soil', 'Yes'),
(48, 8, 'Volcano View', 'Yes'),
(49, 8, 'Road Access', 'Yes'),
(50, 9, 'Bedrooms', '4'),
(51, 9, 'Bathrooms', '3'),
(52, 9, 'Size (sqm)', '300'),
(53, 9, 'Mountain View', 'Yes'),
(54, 9, 'Double Story', 'Yes'),
(55, 9, 'Under Construction', 'Yes'),
(56, 10, 'Bedrooms', '3'),
(57, 10, 'Bathrooms', '3'),
(58, 10, 'Size (sqm)', '200'),
(59, 10, 'Shared Pool', 'Yes'),
(60, 10, 'Modern Kitchen', 'Yes'),
(61, 10, 'Basement Parking', 'Yes'),
(62, 11, 'Bedrooms', '6'),
(63, 11, 'Bathrooms', '1'),
(64, 11, 'Area Size (sqm)', '125'),
(65, 12, 'Bedrooms', '2'),
(66, 12, 'Bathrooms', '1'),
(67, 12, 'Area Size (sqm)', '98'),
(68, 13, 'Bedrooms', '5'),
(69, 13, 'Bathrooms', '4'),
(70, 13, 'Area Size (sqm)', '241'),
(71, 14, 'Bedrooms', '3'),
(72, 14, 'Bathrooms', '2'),
(73, 14, 'Area Size (sqm)', '118'),
(74, 15, 'Bedrooms', '4'),
(75, 15, 'Bathrooms', '4'),
(76, 15, 'Area Size (sqm)', '131'),
(77, 16, 'Bedrooms', '5'),
(78, 16, 'Bathrooms', '2'),
(79, 16, 'Area Size (sqm)', '206'),
(80, 17, 'Bedrooms', '2'),
(81, 17, 'Bathrooms', '3'),
(82, 17, 'Area Size (sqm)', '183'),
(83, 18, 'Bedrooms', '5'),
(84, 18, 'Bathrooms', '4'),
(85, 18, 'Area Size (sqm)', '225'),
(86, 23, 'Bedrooms', '3'),
(87, 23, 'Bathrooms', '1'),
(88, 23, 'Area Size (sqm)', '230'),
(89, 24, 'Bedrooms', '2'),
(90, 24, 'Bathrooms', '3'),
(91, 24, 'Area Size (sqm)', '275'),
(92, 25, 'Bedrooms', '3'),
(93, 25, 'Bathrooms', '1'),
(94, 25, 'Area Size (sqm)', '356'),
(95, 26, 'Bedrooms', '2'),
(96, 26, 'Bathrooms', '2'),
(97, 26, 'Area Size (sqm)', '278');

-- --------------------------------------------------------

--
-- Table structure for table `property_images`
--

CREATE TABLE `property_images` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `alt_text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_images`
--

INSERT INTO `property_images` (`id`, `property_id`, `image_url`, `sort_order`, `alt_text`) VALUES
(1, 1, 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=600&auto=format&fit=crop', 0, 'Property Image'),
(2, 1, 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop', 1, 'Property Image'),
(3, 1, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=600&auto=format&fit=crop', 2, 'Property Image'),
(4, 1, 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=600&auto=format&fit=crop', 3, 'Property Image'),
(5, 1, 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?q=80&w=600&auto=format&fit=crop', 4, 'Property Image'),
(6, 1, 'https://images.unsplash.com/photo-1600573472591-ee6b68d14c68?q=80&w=600&auto=format&fit=crop', 5, 'Property Image'),
(7, 1, 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=600&auto=format&fit=crop', 6, 'Property Image'),
(8, 1, 'https://images.unsplash.com/photo-1600566752355-35792bedcfea?q=80&w=600&auto=format&fit=crop', 7, 'Property Image'),
(9, 1, 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?q=80&w=600&auto=format&fit=crop', 8, 'Property Image'),
(10, 1, 'https://images.unsplash.com/photo-1600121848594-d8644e57abab?q=80&w=600&auto=format&fit=crop', 9, 'Property Image'),
(11, 2, 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=600&auto=format&fit=crop', 0, 'Property Image'),
(12, 2, 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=600&auto=format&fit=crop', 1, 'Property Image'),
(13, 2, 'https://images.unsplash.com/photo-1484154218962-a197022b5858?q=80&w=600&auto=format&fit=crop', 2, 'Property Image'),
(14, 2, 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=600&auto=format&fit=crop', 3, 'Property Image'),
(15, 2, 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=600&auto=format&fit=crop', 4, 'Property Image'),
(16, 2, 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=600&auto=format&fit=crop', 5, 'Property Image'),
(17, 2, 'https://images.unsplash.com/photo-1502672090827-8355fa285494?q=80&w=600&auto=format&fit=crop', 6, 'Property Image'),
(18, 2, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=600&auto=format&fit=crop', 7, 'Property Image'),
(19, 2, 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=600&auto=format&fit=crop', 8, 'Property Image'),
(20, 2, 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?q=80&w=600&auto=format&fit=crop', 9, 'Property Image'),
(21, 3, 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=600&auto=format&fit=crop', 0, 'Property Image'),
(22, 3, 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=600&auto=format&fit=crop', 1, 'Property Image'),
(23, 3, 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=600&auto=format&fit=crop', 2, 'Property Image'),
(24, 3, 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?q=80&w=600&auto=format&fit=crop', 3, 'Property Image'),
(25, 4, 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=600&auto=format&fit=crop', 0, 'Property Image'),
(26, 4, 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop', 1, 'Property Image'),
(27, 4, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=600&auto=format&fit=crop', 2, 'Property Image'),
(28, 4, 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=600&auto=format&fit=crop', 3, 'Property Image'),
(29, 4, 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?q=80&w=600&auto=format&fit=crop', 4, 'Property Image'),
(30, 4, 'https://images.unsplash.com/photo-1600573472591-ee6b68d14c68?q=80&w=600&auto=format&fit=crop', 5, 'Property Image'),
(31, 4, 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=600&auto=format&fit=crop', 6, 'Property Image'),
(32, 4, 'https://images.unsplash.com/photo-1600566752355-35792bedcfea?q=80&w=600&auto=format&fit=crop', 7, 'Property Image'),
(33, 4, 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?q=80&w=600&auto=format&fit=crop', 8, 'Property Image'),
(34, 4, 'https://images.unsplash.com/photo-1600121848594-d8644e57abab?q=80&w=600&auto=format&fit=crop', 9, 'Property Image'),
(35, 5, 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=600&auto=format&fit=crop', 0, 'Property Image'),
(36, 5, 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=600&auto=format&fit=crop', 1, 'Property Image'),
(37, 5, 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=600&auto=format&fit=crop', 2, 'Property Image'),
(38, 5, 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?q=80&w=600&auto=format&fit=crop', 3, 'Property Image'),
(39, 6, 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=600&auto=format&fit=crop', 0, 'Property Image'),
(40, 6, 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop', 1, 'Property Image'),
(41, 6, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=600&auto=format&fit=crop', 2, 'Property Image'),
(42, 6, 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=600&auto=format&fit=crop', 3, 'Property Image'),
(43, 6, 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?q=80&w=600&auto=format&fit=crop', 4, 'Property Image'),
(44, 6, 'https://images.unsplash.com/photo-1600573472591-ee6b68d14c68?q=80&w=600&auto=format&fit=crop', 5, 'Property Image'),
(45, 6, 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=600&auto=format&fit=crop', 6, 'Property Image'),
(46, 6, 'https://images.unsplash.com/photo-1600566752355-35792bedcfea?q=80&w=600&auto=format&fit=crop', 7, 'Property Image'),
(47, 6, 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?q=80&w=600&auto=format&fit=crop', 8, 'Property Image'),
(48, 6, 'https://images.unsplash.com/photo-1600121848594-d8644e57abab?q=80&w=600&auto=format&fit=crop', 9, 'Property Image'),
(49, 7, 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=600&auto=format&fit=crop', 0, 'Property Image'),
(50, 7, 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=600&auto=format&fit=crop', 1, 'Property Image'),
(51, 7, 'https://images.unsplash.com/photo-1484154218962-a197022b5858?q=80&w=600&auto=format&fit=crop', 2, 'Property Image'),
(52, 7, 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=600&auto=format&fit=crop', 3, 'Property Image'),
(53, 7, 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=600&auto=format&fit=crop', 4, 'Property Image'),
(54, 7, 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=600&auto=format&fit=crop', 5, 'Property Image'),
(55, 7, 'https://images.unsplash.com/photo-1502672090827-8355fa285494?q=80&w=600&auto=format&fit=crop', 6, 'Property Image'),
(56, 7, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=600&auto=format&fit=crop', 7, 'Property Image'),
(57, 7, 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=600&auto=format&fit=crop', 8, 'Property Image'),
(58, 7, 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?q=80&w=600&auto=format&fit=crop', 9, 'Property Image'),
(59, 8, 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=600&auto=format&fit=crop', 0, 'Property Image'),
(60, 8, 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=600&auto=format&fit=crop', 1, 'Property Image'),
(61, 8, 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=600&auto=format&fit=crop', 2, 'Property Image'),
(62, 8, 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?q=80&w=600&auto=format&fit=crop', 3, 'Property Image'),
(63, 9, 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=600&auto=format&fit=crop', 0, 'Property Image'),
(64, 9, 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop', 1, 'Property Image'),
(65, 9, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=600&auto=format&fit=crop', 2, 'Property Image'),
(66, 9, 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=600&auto=format&fit=crop', 3, 'Property Image'),
(67, 9, 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?q=80&w=600&auto=format&fit=crop', 4, 'Property Image'),
(68, 9, 'https://images.unsplash.com/photo-1600573472591-ee6b68d14c68?q=80&w=600&auto=format&fit=crop', 5, 'Property Image'),
(69, 9, 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=600&auto=format&fit=crop', 6, 'Property Image'),
(70, 9, 'https://images.unsplash.com/photo-1600566752355-35792bedcfea?q=80&w=600&auto=format&fit=crop', 7, 'Property Image'),
(71, 9, 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?q=80&w=600&auto=format&fit=crop', 8, 'Property Image'),
(72, 9, 'https://images.unsplash.com/photo-1600121848594-d8644e57abab?q=80&w=600&auto=format&fit=crop', 9, 'Property Image'),
(73, 10, 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=600&auto=format&fit=crop', 0, 'Property Image'),
(74, 10, 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=600&auto=format&fit=crop', 1, 'Property Image'),
(75, 10, 'https://images.unsplash.com/photo-1484154218962-a197022b5858?q=80&w=600&auto=format&fit=crop', 2, 'Property Image'),
(76, 10, 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=600&auto=format&fit=crop', 3, 'Property Image'),
(77, 10, 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=600&auto=format&fit=crop', 4, 'Property Image'),
(78, 10, 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=600&auto=format&fit=crop', 5, 'Property Image'),
(79, 10, 'https://images.unsplash.com/photo-1502672090827-8355fa285494?q=80&w=600&auto=format&fit=crop', 6, 'Property Image'),
(80, 10, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=600&auto=format&fit=crop', 7, 'Property Image'),
(81, 10, 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=600&auto=format&fit=crop', 8, 'Property Image'),
(82, 10, 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?q=80&w=600&auto=format&fit=crop', 9, 'Property Image'),
(83, 11, 'storage/properties/house-6a581cc9bbda9.jpg', 1, NULL),
(84, 12, 'storage/properties/house-6a581ccd91018.jpg', 1, NULL),
(85, 13, 'storage/properties/house-6a581cd1310b3.jpg', 1, NULL),
(86, 14, 'storage/properties/house-6a581cd496ebf.jpg', 1, NULL),
(87, 15, 'storage/properties/apartment-6a581cd846197.jpg', 1, NULL),
(88, 16, 'storage/properties/apartment-6a581cdcd6aaf.jpg', 1, NULL),
(89, 17, 'storage/properties/apartment-6a581ce11a79e.jpg', 1, NULL),
(90, 18, 'storage/properties/apartment-6a581ce45ef01.jpg', 1, NULL),
(91, 19, 'storage/properties/land-6a581ce81f183.jpg', 1, NULL),
(92, 20, 'storage/properties/land-6a581ceb7d82f.jpg', 1, NULL),
(93, 21, 'storage/properties/land-6a581cef3598a.jpg', 1, NULL),
(94, 22, 'storage/properties/land-6a581cf26f1cb.jpg', 1, NULL),
(95, 23, 'storage/properties/commercial-6a581cf625889.jpg', 1, NULL),
(96, 24, 'storage/properties/commercial-6a581cfd01d2d.jpg', 1, NULL),
(97, 25, 'storage/properties/commercial-6a581d0065df7.jpg', 1, NULL),
(98, 26, 'storage/properties/commercial-6a581d03dbda4.jpg', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `push_notifications`
--

CREATE TABLE `push_notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `action_url` text DEFAULT NULL,
  `target_users` enum('all','vendors','customers','guests') DEFAULT 'all',
  `status` enum('draft','scheduled','sent') DEFAULT 'draft',
  `scheduled_at` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `recipient_count` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `recipient` varchar(20) NOT NULL,
  `message_body` text NOT NULL,
  `gateway_response` text DEFAULT NULL,
  `status` enum('sent','failed') DEFAULT 'sent',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sms_logs`
--

INSERT INTO `sms_logs` (`id`, `order_id`, `vendor_id`, `recipient`, `message_body`, `gateway_response`, `status`, `created_at`) VALUES
(8, 21, 16, '+250796194401', 'KURA PRO: New Order #21. Total: 30,000 RWF. Please log in to your dashboard to process.', '{\"SMSMessageData\":{\"Message\":\"Sent to 1/1 Total Cost: RWF 14.0000 Message parts: 1\",\"Recipients\":[{\"cost\":\"RWF 14.0000\",\"messageId\":\"ATXid_5a8680b74eb831445cee173e3eddc699\",\"number\":\"+250796194401\",\"status\":\"Success\",\"statusCode\":101}]}}', 'sent', '2026-03-13 05:59:21'),
(9, 22, 16, '+250796194401', 'KURA PRO: New Order #22. Total: 400,000 RWF. Please log in to your dashboard to process.', '{\"SMSMessageData\":{\"Message\":\"Sent to 1/1 Total Cost: RWF 14.0000 Message parts: 1\",\"Recipients\":[{\"cost\":\"RWF 14.0000\",\"messageId\":\"ATXid_e85a2309cbd6c153c0db010c474c9b5c\",\"number\":\"+250796194401\",\"status\":\"Success\",\"statusCode\":101}]}}', 'sent', '2026-03-14 07:04:49'),
(10, 23, 16, '+250796194401', 'KURA PRO: New Order #23. Total: 145,000 RWF. Please log in to your dashboard to process.', '{\"SMSMessageData\":{\"Message\":\"Sent to 1/1 Total Cost: RWF 14.0000 Message parts: 1\",\"Recipients\":[{\"cost\":\"RWF 14.0000\",\"messageId\":\"ATXid_dfc6a55c5116ccb800777dca90c3020a\",\"number\":\"+250796194401\",\"status\":\"Success\",\"statusCode\":101}]}}', 'sent', '2026-03-14 07:28:34'),
(11, 24, 16, '+250796194401', 'KURA PRO: New Order #24. Total: 36,000 RWF. Please log in to your dashboard to process.', '{\"SMSMessageData\":{\"Message\":\"Sent to 1/1 Total Cost: RWF 14.0000 Message parts: 1\",\"Recipients\":[{\"cost\":\"RWF 14.0000\",\"messageId\":\"ATXid_3f21ee9a8074306f7c8efa3c17619ba1\",\"number\":\"+250796194401\",\"status\":\"Success\",\"statusCode\":101}]}}', 'sent', '2026-03-18 02:39:17'),
(12, 25, 36, '+250783116696', 'KURA PRO: New Order #25. Total: 12,000 RWF. Please log in to your dashboard to process.', '{\"SMSMessageData\":{\"Message\":\"Sent to 1/1 Total Cost: RWF 14.0000 Message parts: 1\",\"Recipients\":[{\"cost\":\"RWF 14.0000\",\"messageId\":\"ATXid_b6757cd12bf113880624b16830f259a0\",\"number\":\"+250783116696\",\"status\":\"Success\",\"statusCode\":101}]}}', 'sent', '2026-04-19 16:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `plan_name` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('active','cancelled','expired') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `plan_name`, `amount`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 16, 'Enterprise', '120000.00', '2026-03-05 10:29:46', '2027-03-05 10:29:46', 'active', '2026-03-05 15:29:47'),
(2, 28, 'None', NULL, '2026-03-04 13:22:38', '2026-03-04 13:22:38', 'expired', '2026-03-05 18:22:38'),
(3, 31, 'None', NULL, '2026-03-08 05:29:09', '2026-03-08 05:29:09', 'expired', '2026-03-09 09:29:09'),
(4, 31, 'Standard', '24000.00', '2026-03-09 06:42:22', '2027-03-09 06:42:22', 'active', '2026-03-09 10:42:22'),
(5, 31, 'Standard', '24000.00', '2026-03-09 06:42:27', '2027-03-09 06:42:27', 'active', '2026-03-09 10:42:27'),
(6, 32, 'None', NULL, '2026-03-08 08:29:28', '2026-03-08 08:29:28', 'cancelled', '2026-03-09 12:29:27'),
(7, 32, 'Professional', '30000.00', '2026-03-09 08:42:12', '2026-09-09 08:42:12', 'active', '2026-03-09 12:42:11'),
(8, 36, 'None', NULL, '2026-03-30 11:41:45', '2026-03-30 11:41:45', 'expired', '2026-03-31 15:41:45');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`, `updated_at`) VALUES
('site_name', 'Trust Rwanda', '2026-06-07 01:58:14'),
('support_email', '', '2026-06-07 01:58:14'),
('support_phone', '', '2026-06-07 01:58:14'),
('currency_code', 'RWF', '2026-06-07 01:58:14'),
('commission_percent', '0', '2026-06-07 01:58:14'),
('min_order_amount', '0', '2026-06-07 01:58:14'),
('vendor_auto_approval', '0', '2026-06-07 01:58:14'),
('site_logo', 'site_logo_1780797534.png', '2026-06-07 01:58:55'),
('site_favicon', 'site_favicon_1780797535.png', '2026-06-07 01:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('credit','debit') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','vendor','customer','real_estate_owner') NOT NULL DEFAULT 'customer',
  `shop_name` varchar(100) DEFAULT NULL,
  `shop_logo` varchar(255) DEFAULT NULL,
  `shop_description` text DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `subscription_status` enum('active','expired','pending') DEFAULT 'pending',
  `subscription_expires_at` datetime DEFAULT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `address`, `password`, `role`, `shop_name`, `shop_logo`, `shop_description`, `logo_url`, `latitude`, `longitude`, `is_verified`, `created_at`, `updated_at`, `reset_token`, `token_expiry`, `subscription_status`, `subscription_expires_at`, `otp_code`, `otp_expiry`) VALUES
(16, 'IMANZI Store', 'imanzistore@gmail.com', '0796194401', '', '$2y$10$HI3TNzDSexxkXojK7h25ruMjZZOGCcPU14MMh.r2mr7rYuXtlwIUO', 'vendor', 'IMANZI Store', 'logo_16_1773508252.jpg', '', 'https://kuraspace.kesug.com//assets/uploads/logos/logo_16_1773508252.jpg', '-1.56206446', '29.63810457', 1, '2026-02-27 23:22:18', '2026-04-19 18:28:49', NULL, NULL, 'active', '2027-03-05 10:29:46', NULL, '2026-04-19 14:38:07'),
(17, 'Steven IMANZI', 'stivenimanzi1@gmail.com', '0729786730', NULL, '$2y$10$jKNAcj/HgEwb5ugMEV1kzuiQXgHh9bTbVLMJbSDUXxMYFVBdT.EM.', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-28 23:16:31', '2026-03-05 17:58:15', NULL, NULL, 'pending', NULL, NULL, NULL),
(20, 'Dodos', 'jmvnsanzimfura48@gmail.com', '0791278218', NULL, '$2y$10$hV9.btevIfcE/Resl4Dazue4WQru.xA7S05/Wwvd4ShXu.wHjTb2K', 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-01 13:32:05', '2026-03-01 10:32:05', NULL, NULL, 'pending', NULL, NULL, NULL),
(21, 'Godwin Mumbere', 'godwin@julybrands.co.ug', '0785407551', NULL, '$2y$10$5Ira5mUrLyzyC.9WHUbHVeXinT6G4u7gGjNhHSeiG8lItDwJfgmhG', 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-02 13:03:40', '2026-03-02 10:03:41', NULL, NULL, 'pending', NULL, NULL, NULL),
(23, 'Niyonsenga', 'uzayisengaemmanuel288@gmail.com', '0795115806', NULL, '$2y$10$g9Q6.6S4wT/KDz.o2ggfAeoJR4IASENT0masjfimKeQ79M5dzzLtq', 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-02 23:42:54', '2026-03-02 20:42:53', NULL, NULL, 'pending', NULL, NULL, NULL),
(27, 'GANZA Edimond', 'ganza@gmail.com', '07961555', NULL, '$2y$10$sEhz.8hUPz.uOJkzT4yOjOKM7ShE90l.ZHd7kSbSBLT0vX34Gs91y', 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-05 20:18:21', '2026-03-05 17:18:20', NULL, NULL, 'pending', NULL, NULL, NULL),
(29, 'GUTETA Time Tresor', 'gtimetresor@gmail.com', '0795916277', NULL, '$2y$10$SQ5lOAX4xvfGC.FkX4S4HOQ3AW5tNYbapldZ1/F4..F5u2jarRDtS', 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-05 23:13:46', '2026-03-05 20:13:46', NULL, NULL, 'pending', NULL, NULL, NULL),
(30, 'Jamaeditor', 'jamaeditort@gmail.com', '0799341168', NULL, '$2y$10$zkBxiDbG1I1VsuHAHNc3.uGH61/wONBbvfTVkv1Ou87jwToiFC8re', 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-06 21:01:54', '2026-03-06 18:01:54', NULL, NULL, 'pending', NULL, NULL, NULL),
(31, 'Dieudonne Murekabanze', 'jmvnsanzimfura47@gmail.com', '0788678139', 'KN2rd', '$2y$10$55s6DE6OpaD/5Zesqs81Vuc6AxRb4Jmo.EW9AzdgoQxqn6/I.dRui', 'vendor', 'Dodos lighting hardware Ltd', 'logo_31_1773048845.jpeg', 'Dodos lighting hardware offer best quality products  and unique customer services both with humanity and faster services', 'https://kuraspace.kesug.com//assets/uploads/logos/logo_31_1773048845.jpeg', '-1.94410000', '30.06190000', 1, '2026-03-09 12:29:09', '2026-03-09 10:42:27', NULL, NULL, 'active', '2027-03-09 06:42:27', NULL, NULL),
(32, 'Ibraah walker', 'Ibrahwolker@gmail.com', '0793068480', 'Musanze S', '$2y$10$oZMQYrFOwpYp204vPUy35eRP.j8Ur107R8o5kdL5KyxCuklgk8QVa', 'vendor', 'Ibraah shop', NULL, NULL, NULL, '-1.94410000', '30.06190000', 1, '2026-03-09 15:29:28', '2026-03-13 08:46:17', NULL, NULL, 'expired', '2026-09-09 08:42:12', NULL, NULL),
(33, 'Gahunde', 'gahundedan123@gmail.com', '0791011035', NULL, '$2y$10$YXGtsL1Uqq1aZ2GhJsn0J.naxknDFMGV3CE5D9hWTzBGuZuVEbV9y', 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-09 19:55:53', '2026-03-09 16:55:53', NULL, NULL, 'pending', NULL, NULL, NULL),
(34, 'Robert Humphrey', 'simonpierreturamyumukiza96@gmail.com', '+1 (785) 914-2059', NULL, '$2y$10$XdWhpf6k0zJ216oTf3bdcOsb/pPJKhAGO6ji1fMCjGfcQaIZKk4SS', 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-14 14:04:17', '2026-03-14 11:48:43', NULL, NULL, 'pending', NULL, NULL, NULL),
(35, 'Admin vendor', 'admin@mail.com', '0787777777', NULL, '$2y$10$wiLqVucHvoWP7/0HLrrq1u9MXItN0ePs.Osc20hLk5QLhWvIScKuS', 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-18 09:38:38', '2026-03-18 06:46:28', 'b98c9f0a6d2787a262fd9590089df2efb43d050c305012740a6966ebeef89ca2', '2026-03-18 09:46:28', 'pending', NULL, NULL, NULL),
(36, 'NYIRIMANZI Philbert', 'imanzilylics@gmail.com', '0783116696', 'Byumba', '$2y$10$awhktzS3WmV1mgMhkLO6gONoPsR6TRq3O2VLX6MLw3xJNCpQx.2dC', 'vendor', 'OPPOSITE PUB KWA COACH', 'logo_36_1774972700.jpg', '', 'https://kuraspace.kesug.com//assets/uploads/logos/logo_36_1774972700.jpg', '-1.57970319', '30.06804856', 0, '2026-03-31 18:41:45', '2026-04-19 19:28:54', NULL, NULL, 'pending', NULL, NULL, '2026-04-19 15:38:18'),
(37, 'Steven IMANZI', 'stevenimanzi@gmail.com', '0729786731', 'Kigali', '$2y$10$YK5Sy0VQfqEII1HXC7pUB.JRJRkqziImUfcSKd74JP2TOlBBOmuM2', 'real_estate_owner', 'IMANZI  Real Estate', NULL, NULL, NULL, '-1.58046600', '30.06920500', 0, '2026-06-19 05:56:45', '2026-06-18 21:14:26', NULL, NULL, 'pending', NULL, NULL, NULL),
(101, 'Kamanzi Jean Pierre', 'kamanzi@example.com', '+250788123456', 'Kigali, Gasabo', 'hashed_password_1', 'real_estate_owner', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-07-01 09:55:42', '2026-07-01 09:55:42', NULL, NULL, 'active', NULL, NULL, NULL),
(102, 'Uwase Marie Claire', 'uwase@example.com', '+250788234567', 'Kigali, Nyarugenge', 'hashed_password_2', 'real_estate_owner', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-07-01 09:55:42', '2026-07-01 09:55:42', NULL, NULL, 'active', NULL, NULL, NULL),
(103, 'Nkurunziza Alphonse', 'nkurunziza@example.com', '+250788345678', 'Kigali, Kicukiro', 'hashed_password_3', 'real_estate_owner', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-07-01 09:55:42', '2026-07-01 09:55:42', NULL, NULL, 'active', NULL, NULL, NULL),
(104, 'Mugisha Christian', 'mugisha@example.com', '+250788456789', 'Kigali, Nyarugenge', 'hashed_password_4', 'real_estate_owner', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-07-01 09:55:42', '2026-07-01 09:55:42', NULL, NULL, 'active', NULL, NULL, NULL),
(105, 'Gasana Eric', 'gasana@example.com', '+250788567890', 'Kigali, Gasabo', 'hashed_password_5', 'real_estate_owner', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-07-01 09:55:42', '2026-07-01 09:55:42', NULL, NULL, '', NULL, NULL, NULL),
(106, 'Umuhoza Solange', 'umuhoza@example.com', '+250788678901', 'Musanze, Kinigi', 'hashed_password_6', 'real_estate_owner', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-07-01 09:55:42', '2026-07-01 09:55:42', NULL, NULL, 'active', NULL, NULL, NULL),
(107, 'Bizimana Emmanuel', 'bizimana@example.com', '+250788789012', 'Kigali, Kicukiro', 'hashed_password_7', 'real_estate_owner', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-07-01 09:55:42', '2026-07-01 09:55:42', NULL, NULL, '', NULL, NULL, NULL),
(108, 'Steven IMANZI', 'steven@gmail.com', '0798058706', NULL, '$2y$10$oJmo9I7ZEOYrIEWvvqNpNeBJM74zbV7aTLXp3LkztyhGX.EVOXtgi', 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-07-01 20:34:14', '2026-07-01 11:34:14', NULL, NULL, 'pending', NULL, NULL, NULL),
(109, 'Mr Steven', 'imanzi@gmail.com', '0729786734', 'Bk, Byumba', '$2y$12$bzGKBENz911WBPCPc7kzhO8diwL4pxLNJN5Io5JZoyS427ENMSbwK', 'real_estate_owner', 'IMANZI Labs', NULL, NULL, NULL, '-1.94410000', '30.06190000', 0, '2026-07-15 22:25:45', '2026-07-15 22:25:45', NULL, NULL, 'pending', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_ads`
--
ALTER TABLE `active_ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ad_analytics`
--
ALTER TABLE `ad_analytics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ad_inquiries`
--
ALTER TABLE `ad_inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `affiliate_commissions`
--
ALTER TABLE `affiliate_commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referrer_id` (`referrer_id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_code` (`code`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `property_features`
--
ALTER TABLE `property_features`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `property_feature` (`property_id`,`feature_name`);

--
-- Indexes for table `property_images`
--
ALTER TABLE `property_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `push_notifications`
--
ALTER TABLE `push_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wish` (`user_id`,`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `active_ads`
--
ALTER TABLE `active_ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ad_analytics`
--
ALTER TABLE `ad_analytics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ad_inquiries`
--
ALTER TABLE `ad_inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `affiliate_commissions`
--
ALTER TABLE `affiliate_commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43178;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=477;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=362;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `property_features`
--
ALTER TABLE `property_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `property_images`
--
ALTER TABLE `property_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `push_notifications`
--
ALTER TABLE `push_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `fk_property_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_features`
--
ALTER TABLE `property_features`
  ADD CONSTRAINT `fk_property_features` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_images`
--
ALTER TABLE `property_images`
  ADD CONSTRAINT `fk_property_images` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
