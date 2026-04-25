-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 24 avr. 2026 à 01:13
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `usthb_app`
--

-- --------------------------------------------------------

--
-- Structure de la table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `grade` float NOT NULL CHECK (`grade` >= 0 and `grade` <= 20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `module_id`, `grade`) VALUES
(5, 14, 4, 9),
(6, 3, 8, 13);

-- --------------------------------------------------------

--
-- Structure de la table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `coefficient` int(11) NOT NULL DEFAULT 1,
  `teacher_id` int(11) DEFAULT NULL,
  `lvl` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `modules`
--

INSERT INTO `modules` (`id`, `code`, `name`, `coefficient`, `teacher_id`, `lvl`) VALUES
(4, 'pweb', 'programmation web', 3, 2, 2),
(5, 'BD', 'Base de données', 3, 3, 2),
(6, 'THG', 'Théorie des graphes', 2, 4, 2),
(7, 'GL', 'Génie logiciel', 3, 5, 2),
(8, 'ARCHI', 'Architecture des ordinateurs', 3, 6, 2),
(9, 'ANG', 'Anglais', 1, 7, 2),
(10, 'SE', 'Système d\'exploitation', 3, 8, 2);

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `matricule` int(11) DEFAULT NULL,
  `surname` varchar(255) NOT NULL,
  `family_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `lvl` int(11) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `students`
--

INSERT INTO `students` (`id`, `matricule`, `surname`, `family_name`, `email`, `lvl`, `birth_date`, `user_id`) VALUES
(1, 31581410, 'Ouail', 'Akouiradjemou', 'wailw5380@gmail.com', 2, '2006-04-09', 3),
(2, 31581409, 'moh', 'mohamed', 'moh@gmail.com', 2, '2006-04-04', 5),
(3, 2147483647, 'LYNA-MELISSA', 'ABAOUI', 'lynamelissa.abaoui@usthb.dz', 2, '2003-03-04', 6),
(4, 31546203, 'MAYA MYRIAM', 'ABBAS', 'maya.abbas@usthb.dz', 2, '2003-12-05', 7),
(5, 31599204, 'AKRAM', 'ABDELHAMID', 'akram.abdelhamid@usthb.dz', 2, '2007-02-15', 8),
(6, 31609707, 'YOUCEF', 'ABDELLAOUI', 'youcef.abdellaoui@usthb.dz', 2, '2005-11-02', 9),
(7, 31676416, 'SARA', 'ABDELLATIF', 'sara.abdellatif@usthb.dz', 2, '2004-10-25', 10),
(8, 31500107, 'NIHAD', 'AISSA', 'nihad.aissa@usthb.dz', 2, '2003-07-28', 11),
(9, 31370909, 'IMAD EDDINE', 'AISSAOUI', 'imad.aissaoui@usthb.dz', 2, '2005-05-28', 12),
(10, 31413601, 'YOUSRA', 'AISSAOUI', 'yousra.aissaoui@usthb.dz', 2, '2003-04-25', 13),
(11, 31368913, 'ABDELMALEK', 'AIT KACI', 'abdelmalek.ait@usthb.dz', 2, '2007-05-08', 14),
(12, 31413217, 'FAROUK IMED', 'AIT MEHDI', 'farouk.ait@usthb.dz', 2, '2003-10-24', 15),
(13, 31438719, 'AYA', 'AIT OUAMAR', 'aya.ait@usthb.dz', 2, '2004-01-05', 16),
(14, 31577510, 'ABDENOUR', 'AKACEM', 'abdenour.akacem@usthb.dz', 2, '2005-08-16', 17),
(15, 31461716, 'IKRAM', 'AKTOUF', 'ikram.aktouf@usthb.dz', 2, '2003-02-02', 18),
(16, 33374911, 'ABDERRAOUF AMINE', 'ALI MOHAMMED', 'abderraouf.ali@usthb.dz', 2, '2005-07-25', 19),
(17, 31453208, 'KAMEL', 'ALIM', 'kamel.alim@usthb.dz', 2, '2005-07-22', 20),
(18, 31438707, 'ASMAA', 'AMDIDOUCHE', 'asmaa.amdidouche@usthb.dz', 2, '2003-02-04', 21),
(19, 32170007, 'NOUR ELHOUDA', 'AMMICHE', 'nour.ammiche@usthb.dz', 2, '2005-10-18', 22),
(20, 31519001, 'YOUNES', 'AMOR', 'younes.amor@usthb.dz', 2, '2006-09-14', 23),
(21, 31499219, 'ABDELMALEK', 'ASSABAT', 'abdelmalek.assabat@usthb.dz', 2, '2003-02-16', 24),
(22, 33087110, 'MELISSA', 'AZZOUG', 'melissa.azzoug@usthb.dz', 2, '2007-07-10', 25),
(23, 31738702, 'ABDENNOUR', 'AZZOUZ', 'abdennour.azzouz@usthb.dz', 2, '2005-03-27', 26),
(24, 31730502, 'MEHDI', 'AZZOUZI', 'mehdi.azzouzi@usthb.dz', 2, '2005-08-12', 27),
(25, 33370909, 'NESRINE', 'BAHA', 'nesrine.baha@usthb.dz', 2, '2004-05-09', 28),
(26, 31620609, 'DOUAA', 'BAOUZ', 'douaa.baouz@usthb.dz', 2, '2006-12-04', 29),
(27, 31388007, 'IMADEDDINE', 'BARA', 'imadeddine.bara@usthb.dz', 2, '2003-07-29', 30),
(28, 31412506, 'ISSAM EDDINE', 'BEARCIA', 'issam.bearcia@usthb.dz', 2, '2004-02-03', 31),
(29, 31740006, 'YAKOUB MOUSSA', 'BEDDIAF', 'yakoub.beddiaf@usthb.dz', 2, '2006-09-26', 32),
(30, 31597817, 'IMENE ZOHRA', 'BELABED', 'imene.belabed@usthb.dz', 2, '2003-05-28', 33),
(31, 31667419, 'YASMINE FATMA ZOHRA', 'BELABRIK', 'yasmine.belabrik@usthb.dz', 2, '2003-10-25', 34),
(32, 31441703, 'ABDELKARIM REDA', 'BELARBI', 'abdelkarim.belarbi@usthb.dz', 2, '2005-11-08', 35),
(33, 31715109, 'ABDELHAKIM', 'BELHADJ', 'abdelhakim.belhadj@usthb.dz', 2, '2004-11-02', 36),
(34, 31715620, 'MOHAMED', 'BELKHIR', 'mohamed.belkhir@usthb.dz', 2, '2003-08-16', 37),
(35, 31461920, 'OMAR', 'BENABDELLATIF', 'omar.benabdellatif@usthb.dz', 2, '2005-08-06', 38),
(36, 31786010, 'BOUCHRA', 'BENAISSA', 'bouchra.benaissa@usthb.dz', 2, '2004-02-13', 39),
(37, 31345706, 'AYA', 'BEN AISSA CHRIF', 'aya.ben@usthb.dz', 2, '2005-10-19', 40),
(38, 31692611, 'RANIA', 'BENAMARA', 'rania.benamara@usthb.dz', 2, '2003-08-22', 41),
(39, 31460816, 'MOHAMED LOKMAN', 'BEN BACHIR', 'mohamed.ben@usthb.dz', 2, '2007-10-21', 42),
(40, 31596411, 'NADA', 'BENCHEIKH', 'nada.bencheikh@usthb.dz', 2, '2005-02-07', 43),
(41, 31656304, 'FARIDA FARAH', 'BENGUESMIA', 'farida.benguesmia@usthb.dz', 2, '2004-02-10', 44),
(42, 31680418, 'YASSER', 'BENMOKHTAR', 'yasser.benmokhtar@usthb.dz', 2, '2007-03-26', 45),
(43, 31675005, 'ISSAM WALID EL', 'BENYAHIA', 'issam.benyahia@usthb.dz', 2, '2005-10-30', 46),
(44, 31652101, 'MOHAMED AMINE', 'BESSAA', 'mohamed.bessaa@usthb.dz', 2, '2004-06-15', 47),
(45, 31622804, 'SID ALI', 'BETTAYEB', 'sid.bettayeb@usthb.dz', 2, '2006-10-14', 48),
(46, 31424405, 'ZINEB AZHAR', 'BOUALI', 'zineb.bouali@usthb.dz', 2, '2007-07-27', 49),
(47, 31440109, 'FATMA ZOHRA', 'BOUDANI', 'fatma.boudani@usthb.dz', 2, '2004-06-23', 50),
(48, 31499415, 'FAIROUZ', 'BOUDAOUD', 'fairouz.boudaoud@usthb.dz', 2, '2006-09-07', 51),
(49, 35477206, 'MAROUA', 'BOUDERRAZ', 'maroua.bouderraz@usthb.dz', 2, '2006-12-27', 52),
(50, 31546202, 'MALIK', 'BOUDINE', 'malik.boudine@usthb.dz', 2, '2006-11-20', 53),
(51, 31843605, 'RADJAA', 'BOUDJANA', 'radjaa.boudjana@usthb.dz', 2, '2005-06-23', 54),
(52, 31698617, 'IBRAHIM MOUHYEDDINE', 'BOUDRAF', 'ibrahim.boudraf@usthb.dz', 2, '2003-09-20', 55),
(53, 31740411, 'HAOUA', 'BOUHADDA', 'haoua.bouhadda@usthb.dz', 2, '2004-03-02', 56),
(54, 31424613, 'NOURELHOUDA', 'BOUHADJA', 'nourelhouda.bouhadja@usthb.dz', 2, '2006-01-06', 57),
(55, 31900101, 'AHMED', 'BENALI', 'ahmed.benali2@usthb.dz', 2, '2005-03-12', 77),
(56, 31900102, 'SARAH', 'BOUZID', 'sarah.bouzid@usthb.dz', 2, '2004-08-21', 78),
(57, 31900103, 'YACINE', 'HAMDI', 'yacine.hamdi@usthb.dz', 2, '2006-01-15', 79),
(58, 31900104, 'NADIA', 'CHERIF', 'nadia.cherif@usthb.dz', 2, '2003-06-07', 80),
(59, 31900105, 'KARIM', 'BELKACEM', 'karim.belkacem@usthb.dz', 2, '2005-09-29', 81),
(60, 31900106, 'AMIRA', 'DJOUDI', 'amira.djoudi@usthb.dz', 2, '2004-03-18', 82),
(61, 31900107, 'SOFIANE', 'MEDDAH', 'sofiane.meddah@usthb.dz', 2, '2006-07-24', 83),
(62, 31900108, 'LINA', 'BOUKHELIFA', 'lina.boukhelifa@usthb.dz', 2, '2003-02-09', 84),
(63, 31900109, 'RIAD', 'SAIDANI', 'riad.saidani@usthb.dz', 2, '2005-11-03', 85),
(64, 31900110, 'YASMINE', 'HADJADJ', 'yasmine.hadjadj@usthb.dz', 2, '2004-04-17', 86),
(65, 31900111, 'BILAL', 'RAHMANI', 'bilal.rahmani@usthb.dz', 2, '2006-08-30', 87),
(66, 31900112, 'MERIEM', 'BENSEGHIR', 'meriem.benseghir@usthb.dz', 2, '2003-05-12', 88),
(67, 31900113, 'OMAR', 'TLEMCANI', 'omar.tlemcani@usthb.dz', 2, '2005-02-25', 89),
(68, 31900114, 'DALILA', 'MESSAOUDI', 'dalila.messaoudi@usthb.dz', 2, '2004-09-16', 90),
(69, 31900115, 'NASSIM', 'BOUDIAF', 'nassim.boudiaf@usthb.dz', 2, '2006-04-08', 91),
(70, 31900116, 'RANIA', 'KHELIL', 'rania.khelil@usthb.dz', 2, '2003-11-27', 92),
(71, 31900117, 'WALID', 'FERHAT', 'walid.ferhat@usthb.dz', 2, '2005-06-14', 93),
(72, 31900118, 'SABRINA', 'MEBARKI', 'sabrina.mebarki@usthb.dz', 2, '2004-01-03', 94),
(73, 31900119, 'HICHEM', 'LAIB', 'hichem.laib@usthb.dz', 2, '2006-09-19', 95),
(74, 31900120, 'FAIZA', 'TOUATI', 'faiza.touati@usthb.dz', 2, '2003-07-24', 96),
(75, 31900121, 'ANIS', 'ZIANI', 'anis.ziani@usthb.dz', 2, '2005-04-11', 97),
(76, 31900122, 'HADJER', 'BELOUIZDAD', 'hadjer.belouizdad@usthb.dz', 2, '2004-08-22', 98),
(77, 31900123, 'TAREK', 'GHOMARI', 'tarek.ghomari@usthb.dz', 2, '2006-02-17', 99),
(78, 31900124, 'SORAYA', 'KACI', 'soraya.kaci@usthb.dz', 2, '2003-10-05', 100),
(79, 31900125, 'MEHDI', 'BOUDISSA', 'mehdi.boudissa@usthb.dz', 2, '2005-07-28', 101),
(80, 31900126, 'NOUR', 'AMRANI', 'nour.amrani@usthb.dz', 2, '2004-03-16', 102),
(81, 31900127, 'ILYES', 'BENAMARA', 'ilyes.benamara2@usthb.dz', 2, '2006-12-11', 103),
(82, 31900128, 'LYDIA', 'HAMITOUCHE', 'lydia.hamitouche@usthb.dz', 2, '2003-04-29', 104),
(83, 31900129, 'ZAKARIA', 'BENLARIA', 'zakaria.benlaria@usthb.dz', 2, '2005-09-03', 105),
(84, 31900130, 'ASMA', 'FERGANI', 'asma.fergani@usthb.dz', 2, '2004-06-18', 106),
(85, 31900131, 'RYAD', 'BOUKERMA', 'ryad.boukerma@usthb.dz', 2, '2006-01-24', 107),
(86, 31900132, 'ZINEB', 'ACHOUR', 'zineb.achour@usthb.dz', 2, '2003-12-16', 108),
(87, 31900133, 'MOURAD', 'BENAISSA', 'mourad.benaissa2@usthb.dz', 2, '2005-05-07', 109),
(88, 31900134, 'HAFSA', 'BOUCHERIT', 'hafsa.boucherit@usthb.dz', 2, '2004-09-13', 110),
(89, 31900135, 'ABDELHAK', 'GRIOU', 'abdelhak.griou@usthb.dz', 2, '2006-03-26', 111),
(90, 31900136, 'LOUBNA', 'DERBAL', 'loubna.derbal@usthb.dz', 2, '2003-08-11', 112),
(91, 31900137, 'SAMIR', 'KHALDI', 'samir.khaldi@usthb.dz', 2, '2005-02-02', 113),
(92, 31900138, 'IKRAM', 'BENDJAMA', 'ikram.bendjama@usthb.dz', 2, '2004-10-19', 114),
(93, 31900139, 'YOUSSEF', 'BRAHIM', 'youssef.brahim@usthb.dz', 2, '2006-07-15', 115),
(94, 31900140, 'MARWA', 'DJEBBAR', 'marwa.djebbar@usthb.dz', 2, '2003-03-24', 116),
(95, 31900141, 'ADEL', 'BOUAFIA', 'adel.bouafia@usthb.dz', 2, '2005-08-16', 117),
(96, 31900142, 'HOUDA', 'KHELIFA', 'houda.khelifa@usthb.dz', 2, '2004-02-07', 118),
(97, 31900143, 'AMINE', 'TERKI', 'amine.terki@usthb.dz', 2, '2006-11-22', 119),
(98, 31900144, 'SANA', 'BOUDERBALA', 'sana.bouderbala@usthb.dz', 2, '2003-06-15', 120),
(99, 31900145, 'FARES', 'HAMOUDI', 'fares.hamoudi@usthb.dz', 2, '2005-04-28', 121),
(100, 31900146, 'NADIA', 'ZERMANE', 'nadia.zermane@usthb.dz', 2, '2004-09-09', 122),
(101, 31900147, 'LOTFI', 'BEKHOUCHE', 'lotfi.bekhouche@usthb.dz', 2, '2006-02-13', 123),
(102, 31900148, 'MAYA', 'LAHLOU', 'maya.lahlou@usthb.dz', 2, '2003-11-28', 124),
(103, 31900149, 'REDA', 'BENSALEM', 'reda.bensalem@usthb.dz', 2, '2005-06-21', 125),
(104, 31900150, 'CHAIMA', 'MOUSSAOUI', 'chaima.moussaoui@usthb.dz', 2, '2004-04-14', 126);

-- --------------------------------------------------------

--
-- Structure de la table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `teachers`
--

INSERT INTO `teachers` (`id`, `first_name`, `last_name`, `email`, `user_id`) VALUES
(2, 'Mohamed', 'Laachemi', 'laachemi@gmail.com', 75),
(3, 'Ahmed', 'Benali', 'ahmed.benali@usthb.dz', 69),
(4, 'Fatima', 'Cherif', 'fatima.cherif@usthb.dz', 70),
(5, 'Karim', 'Mansouri', 'karim.mansouri@usthb.dz', 71),
(6, 'Sara', 'Boudali', 'sara.boudali@usthb.dz', 72),
(7, 'Youcef', 'Hamdi', 'youcef.hamdi@usthb.dz', 73),
(8, 'Nadia', 'Aouadi', 'nadia.aouadi@usthb.dz', 74);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass_word` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `pass_word`, `role`) VALUES
(0, 'admin@example.com', '12345', 'admin'),
(3, 'Ouail.Akouiradjemou@usthb.dz', '20060409', 'student'),
(5, 'moh.mohamed@usthb.dz', '20060404', 'student'),
(6, 'lynamelissa.abaoui@usthb.dz', '20030304', 'student'),
(7, 'maya.abbas@usthb.dz', '20031205', 'student'),
(8, 'akram.abdelhamid@usthb.dz', '20070215', 'student'),
(9, 'youcef.abdellaoui@usthb.dz', '20051102', 'student'),
(10, 'sara.abdellatif@usthb.dz', '20041025', 'student'),
(11, 'nihad.aissa@usthb.dz', '20030728', 'student'),
(12, 'imad.aissaoui@usthb.dz', 'imad', 'student'),
(13, 'yousra.aissaoui@usthb.dz', '20030425', 'student'),
(14, 'abdelmalek.ait@usthb.dz', '20070508', 'student'),
(15, 'farouk.ait@usthb.dz', '20031024', 'student'),
(16, 'aya.ait@usthb.dz', '20040105', 'student'),
(17, 'abdenour.akacem@usthb.dz', '20050816', 'student'),
(18, 'ikram.aktouf@usthb.dz', '20030202', 'student'),
(19, 'abderraouf.ali@usthb.dz', '20050725', 'student'),
(20, 'kamel.alim@usthb.dz', '20050722', 'student'),
(21, 'asmaa.amdidouche@usthb.dz', '20030204', 'student'),
(22, 'nour.ammiche@usthb.dz', '20051018', 'student'),
(23, 'younes.amor@usthb.dz', '20060914', 'student'),
(24, 'abdelmalek.assabat@usthb.dz', '20030216', 'student'),
(25, 'melissa.azzoug@usthb.dz', '20070710', 'student'),
(26, 'abdennour.azzouz@usthb.dz', '20050327', 'student'),
(27, 'mehdi.azzouzi@usthb.dz', '20050812', 'student'),
(28, 'nesrine.baha@usthb.dz', '20040509', 'student'),
(29, 'douaa.baouz@usthb.dz', '20061204', 'student'),
(30, 'imadeddine.bara@usthb.dz', '20030729', 'student'),
(31, 'issam.bearcia@usthb.dz', '20040203', 'student'),
(32, 'yakoub.beddiaf@usthb.dz', '20060926', 'student'),
(33, 'imene.belabed@usthb.dz', '20030528', 'student'),
(34, 'yasmine.belabrik@usthb.dz', '20031025', 'student'),
(35, 'abdelkarim.belarbi@usthb.dz', '20051108', 'student'),
(36, 'abdelhakim.belhadj@usthb.dz', '20041102', 'student'),
(37, 'mohamed.belkhir@usthb.dz', '20030816', 'student'),
(38, 'omar.benabdellatif@usthb.dz', '20050806', 'student'),
(39, 'bouchra.benaissa@usthb.dz', '20040213', 'student'),
(40, 'aya.ben@usthb.dz', '20051019', 'student'),
(41, 'rania.benamara@usthb.dz', '20030822', 'student'),
(42, 'mohamed.ben@usthb.dz', '20071021', 'student'),
(43, 'nada.bencheikh@usthb.dz', '20050207', 'student'),
(44, 'farida.benguesmia@usthb.dz', '20040210', 'student'),
(45, 'yasser.benmokhtar@usthb.dz', '20070326', 'student'),
(46, 'issam.benyahia@usthb.dz', '20051030', 'student'),
(47, 'mohamed.bessaa@usthb.dz', '20040615', 'student'),
(48, 'sid.bettayeb@usthb.dz', '20061014', 'student'),
(49, 'zineb.bouali@usthb.dz', '20070727', 'student'),
(50, 'fatma.boudani@usthb.dz', '20040623', 'student'),
(51, 'fairouz.boudaoud@usthb.dz', '20060907', 'student'),
(52, 'maroua.bouderraz@usthb.dz', '20061227', 'student'),
(53, 'malik.boudine@usthb.dz', '20061120', 'student'),
(54, 'radjaa.boudjana@usthb.dz', '20050623', 'student'),
(55, 'ibrahim.boudraf@usthb.dz', '20030920', 'student'),
(56, 'haoua.bouhadda@usthb.dz', '20040302', 'student'),
(57, 'nourelhouda.bouhadja@usthb.dz', '20060106', 'student'),
(69, 'ahmed.benali@usthb.dz', 'Xt7#mK2p', 'teacher'),
(70, 'fatima.cherif@usthb.dz', 'Rn9@wQ4j', 'teacher'),
(71, 'karim.mansouri@usthb.dz', 'Lp3$vB8s', 'teacher'),
(72, 'sara.boudali@usthb.dz', 'Zq6!nD1f', 'teacher'),
(73, 'youcef.hamdi@usthb.dz', 'Wm5%cH7t', 'teacher'),
(74, 'nadia.aouadi@usthb.dz', 'Yk2&eG9r', 'teacher'),
(75, 'laachemi@gmail.com', 'laachemi2024', 'teacher'),
(77, 'ahmed.benali2@usthb.dz', '20050312', 'student'),
(78, 'sarah.bouzid@usthb.dz', '20040821', 'student'),
(79, 'yacine.hamdi@usthb.dz', '20060115', 'student'),
(80, 'nadia.cherif@usthb.dz', '20030607', 'student'),
(81, 'karim.belkacem@usthb.dz', '20050929', 'student'),
(82, 'amira.djoudi@usthb.dz', '20040318', 'student'),
(83, 'sofiane.meddah@usthb.dz', '20060724', 'student'),
(84, 'lina.boukhelifa@usthb.dz', '20030209', 'student'),
(85, 'riad.saidani@usthb.dz', '20051103', 'student'),
(86, 'yasmine.hadjadj@usthb.dz', '20040417', 'student'),
(87, 'bilal.rahmani@usthb.dz', '20060830', 'student'),
(88, 'meriem.benseghir@usthb.dz', '20030512', 'student'),
(89, 'omar.tlemcani@usthb.dz', '20050225', 'student'),
(90, 'dalila.messaoudi@usthb.dz', '20040916', 'student'),
(91, 'nassim.boudiaf@usthb.dz', '20060408', 'student'),
(92, 'rania.khelil@usthb.dz', '20031127', 'student'),
(93, 'walid.ferhat@usthb.dz', '20050614', 'student'),
(94, 'sabrina.mebarki@usthb.dz', '20040103', 'student'),
(95, 'hichem.laib@usthb.dz', '20060919', 'student'),
(96, 'faiza.touati@usthb.dz', '20030724', 'student'),
(97, 'anis.ziani@usthb.dz', '20050411', 'student'),
(98, 'hadjer.belouizdad@usthb.dz', '20040822', 'student'),
(99, 'tarek.ghomari@usthb.dz', '20060217', 'student'),
(100, 'soraya.kaci@usthb.dz', '20031005', 'student'),
(101, 'mehdi.boudissa@usthb.dz', '20050728', 'student'),
(102, 'nour.amrani@usthb.dz', '20040316', 'student'),
(103, 'ilyes.benamara2@usthb.dz', '20061211', 'student'),
(104, 'lydia.hamitouche@usthb.dz', '20030429', 'student'),
(105, 'zakaria.benlaria@usthb.dz', '20050903', 'student'),
(106, 'asma.fergani@usthb.dz', '20040618', 'student'),
(107, 'ryad.boukerma@usthb.dz', '20060124', 'student'),
(108, 'zineb.achour@usthb.dz', '20031216', 'student'),
(109, 'mourad.benaissa2@usthb.dz', '20050507', 'student'),
(110, 'hafsa.boucherit@usthb.dz', '20040913', 'student'),
(111, 'abdelhak.griou@usthb.dz', '20060326', 'student'),
(112, 'loubna.derbal@usthb.dz', '20030811', 'student'),
(113, 'samir.khaldi@usthb.dz', '20050202', 'student'),
(114, 'ikram.bendjama@usthb.dz', '20041019', 'student'),
(115, 'youssef.brahim@usthb.dz', '20060715', 'student'),
(116, 'marwa.djebbar@usthb.dz', '20030324', 'student'),
(117, 'adel.bouafia@usthb.dz', '20050816', 'student'),
(118, 'houda.khelifa@usthb.dz', '20040207', 'student'),
(119, 'amine.terki@usthb.dz', '20061122', 'student'),
(120, 'sana.bouderbala@usthb.dz', '20030615', 'student'),
(121, 'fares.hamoudi@usthb.dz', '20050428', 'student'),
(122, 'nadia.zermane@usthb.dz', '20040909', 'student'),
(123, 'lotfi.bekhouche@usthb.dz', '20060213', 'student'),
(124, 'maya.lahlou@usthb.dz', '20031128', 'student'),
(125, 'reda.bensalem@usthb.dz', '20050621', 'student'),
(126, 'chaima.moussaoui@usthb.dz', '20040414', 'student');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`module_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Index pour la table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Index pour la table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `matricule` (`matricule`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT pour la table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
