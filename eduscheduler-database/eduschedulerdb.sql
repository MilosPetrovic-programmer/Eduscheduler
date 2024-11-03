-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2024 at 12:07 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eduschedulerdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_panel`
--

CREATE TABLE `admin_panel` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_panel`
--

INSERT INTO `admin_panel` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$1Ac9XfEVSNhDYO/ONwHX2eUVSUlExphChdYKy04t.jhXM60EmMxkq'),
(2, 'pera', '$2y$10$PnB.V1i65bvOYOaAxmq9vebUG5cNRj44owEy/JJG4fT8TBHHVwuqq'),
(3, 'perica', '$2y$10$hb8SZh/E5R70oOvIfSCEM.Yc7HgBZMa3yIyw6zbVOkRUvKEp9pnmK'),
(4, 'mika', '$2y$10$9nr9LL4pap9LiSVUQ60Qle5Vm6ubyaGghe8ghVu9ym4wiHKFzNGne');

-- --------------------------------------------------------

--
-- Table structure for table `busy_classrooms`
--

CREATE TABLE `busy_classrooms` (
  `id` int(11) NOT NULL,
  `amphitheater` varchar(255) NOT NULL,
  `startTime` int(11) NOT NULL,
  `endTime` int(11) NOT NULL,
  `professor` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `calendar` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

CREATE TABLE `classrooms` (
  `id` int(11) NOT NULL,
  `classroom` varchar(255) NOT NULL,
  `occupied` int(11) NOT NULL,
  `features` varchar(255) NOT NULL,
  `floor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classrooms`
--

INSERT INTO `classrooms` (`id`, `classroom`, `occupied`, `features`, `floor`) VALUES
(1, 'U1', 0, 'Projector, TV', 1),
(2, 'U2', 0, 'Projector, Computer Lab', 1),
(3, 'U3', 0, 'Lab Equipment, Projector', 1),
(4, 'U4', 0, 'Projector, Whiteboard', 1),
(5, 'U5', 0, 'Seminar Room, Projector', 1),
(6, 'U6', 0, 'Computer Lab, Projector', 1),
(7, 'U7', 0, 'Whiteboard, Seminar Room', 1),
(8, 'U8', 0, 'Projector, Whiteboard', 1),
(9, 'U9', 0, 'Projector, Computer Lab', 1),
(10, 'A1', 0, 'Lab Equipment, Projector', 1),
(11, 'A2', 0, 'Projector, Whiteboard', 2),
(12, 'A3', 0, 'Projector, Computer Lab', 2),
(13, 'U106', 0, 'Lab Equipment, Projector', 2),
(14, 'U107', 0, 'Projector, Whiteboard', 2),
(15, 'U203', 0, 'Seminar Room, Projector', 3),
(16, 'U206', 0, 'Computer Lab, Projector', 3),
(17, 'U207', 0, 'Whiteboard, Seminar Room', 3),
(18, 'U208', 0, 'Projector, Whiteboard', 3),
(19, 'U211', 0, 'Projector, Whiteboard', 3);

-- --------------------------------------------------------

--
-- Table structure for table `professors`
--

CREATE TABLE `professors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professors`
--

INSERT INTO `professors` (`id`, `name`, `surname`, `email`, `password`, `reset_token`, `token_expires`) VALUES
(1, 'Aleksandra', 'Boričić', 'aleksandra.boricic@akademijanis.edu.rs', '$2y$10$4jbgA54LMhS8vAvZMaN3tOu46RPKIIo8oLHTlfqU6Kp.xLmsng6cC', NULL, NULL),
(2, 'Aleksandra ', 'Marinković', 'aleksandra.marinkovic@akademijanis.edu.rs', '$2y$10$17nd9.HbEiShBAuVfNuRuORohLjvm4bSp6nQJmzwJFu9dodljtP4O', NULL, NULL),
(3, 'Anica ', 'Milošević', 'anica.milosevic@akademijanis.edu.rs', '$2y$10$7l0gNWkS.tTASh9T4kBD8.9LNj6OaTNay55.YSgJ.DHEaM8RmQdnO', NULL, NULL),
(4, 'Biljana ', 'Milutinović', 'biljana.milutinovic@akademijanis.edu.rs', '$2y$10$5EadOwKtFfSs1l3ULAUYkuFRiQKFCyvgLC0VHpYjHw6/CPrwGnctC', NULL, NULL),
(5, 'Boban ', 'Cvetanović', 'boban.cvetanovic@akademijanis.edu.rs', '$2y$10$03sjhFmQ3tvX6IRnEKsxcOaxim0QcNNgiewLmIh.VU56rIxeB0zXu', NULL, NULL),
(6, 'Danijela ', 'Zlatković', 'danijela.zlatkovic@akademijanis.edu.rs', '$2y$10$dRJg9XqLnKN4sh/g1FylQ.EGfhn7iF5qlmgTAvXBlT7WJklurn0GG', NULL, NULL),
(7, 'Dejan ', 'Blagojević', 'dejan.blagojevic@akademijanis.edu.rs', '$2y$10$Y6hTxKUDRcFm/OFONtkzjuEJWfiMXUpPeQ5sU99lhjehH5WgdUaS6', NULL, NULL),
(8, 'Dejan ', 'Bogićević', 'dejan.bogicevic@akademijanis.edu.rs', '$2y$10$mm.GcF/UHW.sNx/ZaRL1HedQukIN5EIZwc.348RwCJwrJNGa0hB5i', NULL, NULL),
(9, 'Dušan ', 'Radosavljević', 'dusan.radosavljevic@akademijanis.edu.rs', '$2y$10$oe7DPQ4BslB.rk1q5J3uKes2iV/2sCQXFMbbmh2qk7cc3zrwtF456', NULL, NULL),
(10, 'Dušan ', 'Stefanović', 'dusan.stefanovic@akademijanis.edu.rs', '$2y$10$tLJAR1nKkPpq2Xs.4xxlheer5LTyj1IoP.53oHegtX4nD/pMhVO9e', NULL, NULL),
(11, 'Zoran ', 'Veličković', 'zoran.velickovic@akademijanis.edu.rs', '$2y$10$XwBSv4grbOfl0QqCwvOZje4DkiH8HqNgQDkK4hel8VzLylBFSGkde', NULL, NULL),
(12, 'Marija ', 'Boranijašević', 'marija.boranijasevic@akademijanis.edu.rs', '$2y$10$ujmvzdXkksDWR5dFRu48W.JIu9whNZD6fAuA0cKgdrLmJnVBSzK5C', NULL, NULL),
(13, 'Milica ', 'Cvetković', 'milica.cvetkovic@akademijanis.edu.rs', '$2y$10$2N5ZwuzNW7T6DfyQ1SpCyujFG6g3oEdtwg1Lb6KpB1.quYjsAO5q6', NULL, NULL),
(14, 'Miloš ', 'Ristić', 'milos.ristic@akademijanis.edu.rs', '$2y$10$Bm3E.FtKBpNn3UOeceJjmuuWN5s7W0ztoirVWYR5B6.26dhUL9kYC', NULL, NULL),
(15, 'Miloš ', 'Stojanović', 'milos.stojanovic@akademijanis.edu.rs', '$2y$10$LjgEYOY2cXSZx.6hpRAoSeQbn3bXDWhrP.cFc6JQ4eSORrvBP2.W2', NULL, NULL),
(16, 'Nataša ', 'Bogdanović', 'natasa.bogdanovic@akademijanis.edu.rs', '$2y$10$St9IWKtSfGu70xnYD7O0GuF/UOgY5FshqzAXR9F6.hLhZ8JnW.uJ2', NULL, NULL),
(17, 'Nataša ', 'Savić', 'natasa.savic@akademijanis.edu.rs', '$2y$10$aYGDNbDnqqxi2C.QRoRcvOBF5O6dOTGW5HVWF9VNDWKpCDj5ZNt7u', NULL, NULL),
(18, 'Nikola ', 'Sekulović', 'nikola.sekulovic@akademijanis.edu.rs', '$2y$10$XOAAqo9vxmUnbNH/qMj91O21r8OMt5MtagPkY5jcOdZWCw521hMsq', NULL, NULL),
(19, 'Petar ', 'Đekić', 'petar.djekic@akademijanis.edu.rs', '$2y$10$B0vmnnmlRYyRZ4zrjVyPHO1FFnzd7Dr2XfbzyckuRkDctb7x9npI.', NULL, NULL),
(20, 'Slađana ', 'Živković Todorov', 'sladjana.zivkovic@akademijanis.edu.rs', '$2y$10$jFgiEuvBAiy4CeeY4NEjjesDKTZV53Qusfht9q0JLi3ntlgV5GH0y', NULL, NULL),
(21, 'Slavimir ', 'Stošović', 'slavimir.stosovic@akademijanis.edu.rs', '$2y$10$mQ7fEO5S8ZtEdNTavoCp8ek8TXCPFvf06EcgXSGkJfqrRqwB3t5Qm', NULL, NULL),
(22, 'Srđan ', 'Jovković', 'srdjan.jovkovic@akademijanis.edu.rs', '$2y$10$6Hq5o9frfNdmmSLi0B1Jh.3URWyK2.DLtJIsbjxXdSstGpz9vQgty', NULL, NULL),
(23, 'Jelena ', 'Bijeljić', 'jelena.bijeljic@akademijanis.edu.rs', '$2y$10$646roL1CBV0Y/BJDuk63TuFMkqyZkkFM8qi01YsYJDaY1xla7SDJe', NULL, NULL),
(24, 'Vladimir ', 'Popović', 'vladimir.popovic@akademijanis.edu.rs', '$2y$10$MqugFwelAVTikTB.SQcz3.yGnYxs1QuOEIVuedTVTq3LRwRBNaBUm', NULL, NULL),
(25, 'Danica ', 'Milošević', 'danica.milosevic@akademijanis.edu.rs', '$2y$10$3nA7c0wkKeGQt44N2w78he2zCNSIhPIrseMuK8qLDi7j/pnw0HDuC', NULL, NULL),
(26, 'Milan ', 'Pavlović', 'milan.pavlovic@akademijanis.edu.rs', '$2y$10$JSeqXr0HUOYKuwQqvmhKlOX0EULcoPp.W6M170ANMSN8aLtj0RgNW', NULL, NULL),
(27, 'Milan ', 'Stanković', 'milan.stankovic@akademijanis.edu.rs', '$2y$10$XmMCPYsCVyX0vBW3zGFSbeNQkMTzq6GTeExYcCAC7qOoghpwi12vG', NULL, NULL),
(28, 'Marjan ', 'Petrović', 'marjan.petrovic@akademijanis.edu.rs', '$2y$10$NCn.OiYfTKM4WuoxHSfGSuU0W82b/33ieSm.znz02LupF66OIJ/4W', NULL, NULL),
(29, 'Violeta ', 'Stojanović', 'violeta.stojanovic@akademijanis.edu.rs', '$2y$10$jatDbNp6YUzTnH4W69wRwu1aUUfTlZQpliCZzTx.hTaBoiCH.L8IW', NULL, NULL),
(30, 'Danijela ', 'Aleksić', 'danijela.aleksic@akademijanis.edu.rs', '$2y$10$P2XYk6Ty0jnWQvi7Yq74Ku0qSlvkdb26Crlq7KvHngVZAMGyILPZq', NULL, NULL),
(31, 'Slađana ', 'Nedeljković', 'sladjana.nedeljkovic@akademijanis.edu.rs', '$2y$10$F0TceZk7C6GZH85aj6rwJOGvgzJwh.cKs.sxwqv5MzNVXvE9nRn/y', NULL, NULL),
(32, 'Milan ', 'Protić', 'milan.protic@akademijanis.edu.rs', '$2y$10$4Z0LaYEDVVUo.d1hrXsQOuDGn0YxPbU5QlTP41lkClOdgGJUJilYq', NULL, NULL),
(33, 'Jovan ', 'Mišić', 'jovan.misic@akademijanis.edu.rs', '$2y$10$p/8wQI4wGgEwNoUe9e8elOlCqDedlPdjVXkEmwTxdHODaAM2fb/0y', NULL, NULL),
(34, 'Gordana ', 'Jović', 'gordana.jovic@akademijanis.edu.rs', '$2y$10$j/CMJji1kqWXLCpXU4PXCeREKSlE.RR73YknWDkADRvE.5gxh1IH.', NULL, NULL),
(35, 'Dunja ', 'Stojanović', 'dunja.stojanovic@akademijanis.edu.rs', '$2y$10$4B.pgKBZzsL6or6JzC9.hOx8mbhUyj9kKBIyvljpIGU6E8UeC1i/e', NULL, NULL),
(36, 'Dušan ', 'Kocić', 'dusan.kocic@akademijanis.edu.rs', '$2y$10$v/fff4g46sgDrv5i39UkyOCgqqTCuvMFT4qvq21diOalZC4t93eDe', NULL, NULL),
(37, 'Matija ', 'Milošević', 'matija.milosevic@akademijanis.edu.rs', '$2y$10$JuXTAuc5OO09/UzIpn8WGuLJiIHkgcmFx5I41TIsfO1nLnGrW2jL6', NULL, NULL),
(38, 'Milan ', 'Savić', 'milan.savic@akademijanis.edu.rs', '$2y$10$rpnTNrqB2aO0JBqWY7uSAeX3vLuhxjuDmpzsrj4/QvCVbqB3Wptda', NULL, NULL),
(39, 'Milena ', 'Nikolić', 'milena.nikolic@akademijanis.edu.rs', '$2y$10$3GRxzQbIxq7jEl.5EJVAP.tJeSN9WcDcwP8lNyOhxjiXJcEYHOBwm', NULL, NULL),
(40, 'Natalija ', 'Petrović', 'natalija.tosic@akademijanis.edu.rs', '$2y$10$LfQva22h/JGcAPTMLJ5mxuQnOCohQw2bY.WaCksTqBfJCPYWklRIy', NULL, NULL),
(41, 'Nikola ', 'Vukotić', 'nikola.vukotic@akademijanis.edu.rs', '$2y$10$D2fpakEIhRjfQzSL.wTcxeildrKHnEOkEIPqoil5Jl5F/P1bZC7k2', NULL, NULL),
(42, 'Nikola ', 'Milutinović', 'nikola.milutinovic@akademijanis.edu.rs', '$2y$10$lS.AjxVzmDXt4o9uGlNo5eFzwFlsmEvyPrY44wrmmOrWIVgv5e64O', NULL, NULL),
(43, 'Sandra ', 'Stanković', 'sandra.stankovic@akademijanis.edu.rs', '$2y$10$MJUIo/j47eoRANKMDthRfuYtk2kEEulHLWnegFTJiaCi9siKlc/hW', NULL, NULL),
(44, 'Stefan ', 'Mihajlović', 'stefan.mihajlovic@akademijanis.edu.rs', '$2y$10$5XUQBT/Mwy06tgucNzMQpek0qZ0OX7ZLvCqEnD9A0n/V05Myu8X2W', NULL, NULL),
(50, 'Milos', 'Petrović', 'mimiizoki@gmail.com', '$2y$10$t02Qlyk/9sLCFAmt9ulCg.JGgJd4tr6kzRxZ.QL36VcsZ5w77meLy', 'dccd5ae801169e30f9b348524cfcfb301518f4d1188b7149b8499763f527b075', '2024-09-14 21:16:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_panel`
--
ALTER TABLE `admin_panel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `busy_classrooms`
--
ALTER TABLE `busy_classrooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_panel`
--
ALTER TABLE `admin_panel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `busy_classrooms`
--
ALTER TABLE `busy_classrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `professors`
--
ALTER TABLE `professors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
