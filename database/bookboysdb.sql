-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2019 at 05:28 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookboysdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `author_id` int(11) NOT NULL,
  `author_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`author_id`, `author_name`) VALUES
(1, 'Gary Paulsen'),
(2, 'Anthony Burgess'),
(3, 'George Orwell'),
(4, 'William Golding'),
(5, 'Stephen Green'),
(6, 'J. K. Rowling'),
(7, 'Stephen Greenius'),
(8, 'Dean Koontz'),
(9, 'Ray Bradbury'),
(10, 'Patrick Rothfuss'),
(11, 'Stephen King');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `isbn` text NOT NULL,
  `book_name` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `edition` int(11) NOT NULL,
  `book_thumbnail` text NOT NULL,
  `book_price` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `isbn`, `book_name`, `author_id`, `publisher_id`, `edition`, `book_thumbnail`, `book_price`) VALUES
(1, '9781416936473', 'Hatchet', 1, 1, 1, 'https://images-na.ssl-images-amazon.com/images/I/61tkn0Wgc-L._SX333_BO1,204,203,200_.jpg', '9.99'),
(2, '2015393312836', 'A Clockwork Orange', 2, 2, 1, 'https://cdn-images-1.medium.com/max/1200/1*e5uI0VXHWD3Y3nWy9GzTwQ.jpeg', '10.00'),
(3, '8601409685823', 'Animal Farm', 3, 3, 1, 'https://images-na.ssl-images-amazon.com/images/I/41bgiGplMlL._SX301_BO1,204,203,200_.jpg', '10.00'),
(4, '9789381529614', 'Lord of the Flies', 4, 4, 1, 'https://images-na.ssl-images-amazon.com/images/I/81UVwYPBtrL.jpg', '9.99'),
(5, '12345678901', 'Destroy All Markers', 5, 5, 1, 'http://images.paperbackswap.com/l/86/5286/9781930355286.jpg', '99.99'),
(6, '1', 'Harry Potter and the Sorcerer\'s Stone', 6, 6, 1, 'https://images-na.ssl-images-amazon.com/images/I/51HSkTKlauL._SX346_BO1,204,203,200_.jpg', '9.99'),
(7, '9780141036144', 'Nineteen Eighty-Four', 3, 8, 1, 'https://pmchollywoodlife.files.wordpress.com/2017/01/why-george-orwell-1984-back-on-best-seller-ftr.jpg', '10.00'),
(8, '9780345533388', 'Life Expectancy', 8, 9, 1, 'https://www.vjbooks.com/v/vspfiles/photos/KOOLIEX01-2.jpg', '9.99'),
(9, '9780553802498', 'Odd Thomas', 8, 10, 0, 'https://upload.wikimedia.org/wikipedia/en/thumb/7/72/Odd_Thomas.jpg/220px-Odd_Thomas.jpg', '10.00'),
(10, '9781451673319', 'Fahrenheit 451', 9, 11, 1, 'https://images-na.ssl-images-amazon.com/images/I/41WDBsL9cDL._SX323_BO1,204,203,200_.jpg', '10.00'),
(11, '978', 'The Name of the Wind', 10, 12, 1, 'https://images-na.ssl-images-amazon.com/images/I/51MUF7bj-lL.jpg', '10.00'),
(12, '8601200570441', 'It', 11, 13, 1, 'https://kbimages1-a.akamaihd.net/a4dd62ec-5ba6-4a65-a6d9-a6651c48758b/353/569/90/False/it-22.jpg', '10.00'),
(13, '9781982103521', 'The Mist', 11, 13, 1, 'https://images-na.ssl-images-amazon.com/images/I/61u25vsCxJL.jpg', '10.00'),
(14, '9780307743657', 'The Shining', 11, 13, 1, 'https://images-na.ssl-images-amazon.com/images/I/81ipXKw8rjL.jpg', '10.00'),
(15, '9781501144509', 'The Dead Zone', 11, 13, 1, 'https://images-na.ssl-images-amazon.com/images/I/51WHTh6XB5L._SX331_BO1,204,203,200_.jpg', '10.00');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`id`, `username`, `password`) VALUES
(1, 'WormyGels', '741daee09fadea442299912f656137b17ed7c367'),
(2, 'AustinKirby2', '84a1dbfe8f818ecdd4746813eecc3488e6b9360a'),
(3, 'TestManager1', '741daee09fadea442299912f656137b17ed7c367'),
(4, 'user1', '58b1ae639de919406a25f7cf0b25738580d08de4');

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

CREATE TABLE `publishers` (
  `publisher_id` int(11) NOT NULL,
  `publisher_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `publishers`
--

INSERT INTO `publishers` (`publisher_id`, `publisher_name`) VALUES
(1, 'Simon & Schuster'),
(2, 'W. W. Norton & Company'),
(3, 'Self Published'),
(4, 'Faber and Faber'),
(5, 'Make It Bounce Publishing'),
(6, 'Bloomsbury'),
(7, 'Make It Bounce Pb'),
(8, 'Harvill Secker'),
(9, 'Bantam Books'),
(10, 'Bantam'),
(11, 'Ballantine Books'),
(12, 'DAW Books'),
(13, 'Viking Press');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount_sold` int(11) NOT NULL,
  `threshold` int(11) NOT NULL,
  `sale_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock_id`, `book_id`, `quantity`, `amount_sold`, `threshold`, `sale_price`) VALUES
(1, 1, 21, 0, 6, '9.99'),
(2, 4, 9004, 0, 10, '9.99'),
(3, 5, 105, 0, 50, '99.99'),
(4, 8, 16, 0, 5, '9.99'),
(5, 6, 40, 0, 5, '9.99'),
(6, 3, 20, 0, 5, '10.00'),
(7, 10, 50, 0, 5, '10.00'),
(8, 7, 50, 0, 5, '10.00'),
(9, 13, 50, 0, 5, '10.00'),
(10, 14, 59, 0, 5, '10.00'),
(11, 12, 50, 0, 5, '10.00'),
(12, 15, 50, 0, 5, '10.00');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_state` text NOT NULL,
  `shipping_method` text NOT NULL,
  `transaction_date` text NOT NULL,
  `tracking_number` int(11) NOT NULL,
  `total` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `user_id`, `shipping_address`, `shipping_state`, `shipping_method`, `transaction_date`, `tracking_number`, `total`) VALUES
(1, 1, '596 Jefferson School Rd, Scottsville', 'KY', 'ground', '2018-04-27', 2147483647, '19.98'),
(2, 1, '596 Jefferson School Rd, Scottsville', 'KY', 'ground', '2018-04-27', 2147483647, '19.98'),
(3, 1, '1504 Barley Way, Bowling Green', 'KY', 'ground', '2018-04-30', 2147483647, '759.24'),
(4, 1, ', ', 'AL', 'ground', '2018-04-30', 2147483647, '9999.99');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'TestCustomer1', '741daee09fadea442299912f656137b17ed7c367'),
(2, 'TestCustomer2', '741daee09fadea442299912f656137b17ed7c367'),
(3, 'TestCustomer3', '741daee09fadea442299912f656137b17ed7c367'),
(4, 'customer', '16d3b53d916b019311c1368708e70f99980c923c');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `publishers`
--
ALTER TABLE `publishers`
  ADD PRIMARY KEY (`publisher_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `publishers`
--
ALTER TABLE `publishers`
  MODIFY `publisher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
