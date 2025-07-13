-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2025 at 05:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `university_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advisors`
--

CREATE TABLE `advisors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisors`
--

INSERT INTO `advisors` (`id`, `name`, `email`, `password`, `department`) VALUES
(1, 'Dr. Ahsan Ali', 'ahsan.ali@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Mathematics'),
(2, 'Dr. Saira Khan', 'saira.khan@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Chemistry'),
(3, 'Dr. Imran Akhtar', 'imran.akhtar@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Computer Science'),
(4, 'Dr. Nadia Farooq', 'nadia.farooq@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Botany'),
(5, 'Dr. Yasir Mehmood', 'yasir.mehmood@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Zoology'),
(6, 'Dr. Saima Tariq', 'saima.tariq@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Agriculture'),
(7, 'Dr. Adnan Riaz', 'adnan.riaz@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Food Sciences');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `serial_number` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT NULL,
  `result_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `student_id`, `serial_number`, `date`, `status`, `result_image`) VALUES
(1, 1, '1', NULL, 'Approved', 'uploads/67e5a0d2c7488.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `application_subjects`
--

CREATE TABLE `application_subjects` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `semester` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_subjects`
--

INSERT INTO `application_subjects` (`id`, `application_id`, `student_id`, `subject_name`, `semester`) VALUES
(1, 1, 1, 'Computer Architecture', 4),
(2, 1, 1, 'Algorithms II', 4);

-- --------------------------------------------------------

--
-- Table structure for table `selected_subjects`
--

CREATE TABLE `selected_subjects` (
  `subject_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `semester` int(11) NOT NULL,
  `result_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `father_name` varchar(255) NOT NULL,
  `ag_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `email`, `password`, `name`, `semester`, `department`, `father_name`, `ag_number`) VALUES
(1, 'hch705148@gmail.com', '$2y$10$6Odwiv7lEkrwqwepgo.CCu.Ee.mr/WbS2NzkMJeiOMNr3FFiLiylu', '12', '8', 'Computer Science', '44', '23');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `department` varchar(50) DEFAULT NULL,
  `subject_name` varchar(100) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`department`, `subject_name`, `semester`, `id`) VALUES
('math', 'Calculus I', 1, 1),
('math', 'Algebra I', 1, 2),
('math', 'Geometry', 1, 3),
('math', 'Trigonometry', 1, 4),
('math', 'Mathematical Reasoning', 1, 5),
('math', 'Statistics I', 1, 6),
('math', 'Calculus II', 2, 7),
('math', 'Linear Algebra I', 2, 8),
('math', 'Differential Equations I', 2, 9),
('math', 'Number Theory', 2, 10),
('math', 'Probability', 2, 11),
('math', 'Discrete Mathematics', 2, 12),
('math', 'Calculus III', 3, 13),
('math', 'Linear Algebra II', 3, 14),
('math', 'Real Analysis I', 3, 15),
('math', 'Complex Numbers', 3, 16),
('math', 'Mathematical Modeling', 3, 17),
('math', 'Numerical Methods I', 3, 18),
('math', 'Abstract Algebra I', 4, 19),
('math', 'Topology I', 4, 20),
('math', 'Differential Equations II', 4, 21),
('math', 'Statistics II', 4, 22),
('math', 'Graph Theory', 4, 23),
('math', 'Set Theory', 4, 24),
('math', 'Real Analysis II', 5, 25),
('math', 'Complex Analysis', 5, 26),
('math', 'Numerical Methods II', 5, 27),
('math', 'Mathematical Statistics', 5, 28),
('math', 'Optimization Theory', 5, 29),
('math', 'Abstract Algebra II', 5, 30),
('math', 'Topology II', 6, 31),
('math', 'Functional Analysis', 6, 32),
('math', 'Partial Differential Equations', 6, 33),
('math', 'Combinatorics', 6, 34),
('math', 'Cryptography Basics', 6, 35),
('math', 'Dynamical Systems', 6, 36),
('math', 'Advanced Probability', 7, 37),
('math', 'Stochastic Processes', 7, 38),
('math', 'Mathematical Physics', 7, 39),
('math', 'Algebraic Geometry', 7, 40),
('math', 'Measure Theory', 7, 41),
('math', 'Research Methods in Math', 7, 42),
('math', 'Advanced Numerical Analysis', 8, 43),
('math', 'Mathematical Logic', 8, 44),
('math', 'Project in Mathematics', 8, 45),
('math', 'Coding Theory', 8, 46),
('math', 'History of Mathematics', 8, 47),
('math', 'Seminar in Mathematics', 8, 48),
('chemistry', 'General Chemistry I', 1, 49),
('chemistry', 'Inorganic Chemistry I', 1, 50),
('chemistry', 'Chemical Bonding', 1, 51),
('chemistry', 'Laboratory Safety', 1, 52),
('chemistry', 'Stoichiometry', 1, 53),
('chemistry', 'Atomic Structure', 1, 54),
('chemistry', 'Organic Chemistry I', 2, 55),
('chemistry', 'General Chemistry II', 2, 56),
('chemistry', 'Thermodynamics I', 2, 57),
('chemistry', 'Chemical Kinetics', 2, 58),
('chemistry', 'Acid-Base Chemistry', 2, 59),
('chemistry', 'Spectroscopy Basics', 2, 60),
('chemistry', 'Physical Chemistry I', 3, 61),
('chemistry', 'Organic Chemistry II', 3, 62),
('chemistry', 'Inorganic Chemistry II', 3, 63),
('chemistry', 'Electrochemistry', 3, 64),
('chemistry', 'Analytical Chemistry I', 3, 65),
('chemistry', 'Chemical Equilibrium', 3, 66),
('chemistry', 'Physical Chemistry II', 4, 67),
('chemistry', 'Polymer Chemistry', 4, 68),
('chemistry', 'Biochemistry I', 4, 69),
('chemistry', 'Thermodynamics II', 4, 70),
('chemistry', 'Quantum Chemistry', 4, 71),
('chemistry', 'Lab Techniques', 4, 72),
('chemistry', 'Analytical Chemistry II', 5, 73),
('chemistry', 'Environmental Chemistry', 5, 74),
('chemistry', 'Biochemistry II', 5, 75),
('chemistry', 'Surface Chemistry', 5, 76),
('chemistry', 'Organic Synthesis', 5, 77),
('chemistry', 'Chemical Instrumentation', 5, 78),
('chemistry', 'Computational Chemistry', 6, 79),
('chemistry', 'Medicinal Chemistry', 6, 80),
('chemistry', 'Photochemistry', 6, 81),
('chemistry', 'Advanced Inorganic Chemistry', 6, 82),
('chemistry', 'Catalysis', 6, 83),
('chemistry', 'Materials Chemistry', 6, 84),
('chemistry', 'Advanced Organic Chemistry', 7, 85),
('chemistry', 'Chemical Engineering Basics', 7, 86),
('chemistry', 'Nuclear Chemistry', 7, 87),
('chemistry', 'Spectroscopy Advanced', 7, 88),
('chemistry', 'Research Methods in Chemistry', 7, 89),
('chemistry', 'Green Chemistry', 7, 90),
('chemistry', 'Project in Chemistry', 8, 91),
('chemistry', 'Industrial Chemistry', 8, 92),
('chemistry', 'Forensic Chemistry', 8, 93),
('chemistry', 'Nanochemistry', 8, 94),
('chemistry', 'Seminar in Chemistry', 8, 95),
('chemistry', 'Chemical Toxicology', 8, 96),
('botany', 'Plant Biology I', 1, 97),
('botany', 'Cell Biology', 1, 98),
('botany', 'Botanical Techniques', 1, 99),
('botany', 'Plant Anatomy', 1, 100),
('botany', 'Photosynthesis', 1, 101),
('botany', 'Ecology I', 1, 102),
('botany', 'Plant Physiology I', 2, 103),
('botany', 'Plant Diversity', 2, 104),
('botany', 'Mycology', 2, 105),
('botany', 'Plant Taxonomy I', 2, 106),
('botany', 'Genetics I', 2, 107),
('botany', 'Bryophytes', 2, 108),
('botany', 'Plant Physiology II', 3, 109),
('botany', 'Plant Pathology', 3, 110),
('botany', 'Pteridophytes', 3, 111),
('botany', 'Ecology II', 3, 112),
('botany', 'Plant Taxonomy II', 3, 113),
('botany', 'Algae Studies', 3, 114),
('botany', 'Plant Biochemistry', 4, 115),
('botany', 'Gymnosperms', 4, 116),
('botany', 'Plant Breeding', 4, 117),
('botany', 'Ethnobotany', 4, 118),
('botany', 'Genetics II', 4, 119),
('botany', 'Soil-Plant Interactions', 4, 120),
('botany', 'Plant Biotechnology', 5, 121),
('botany', 'Plant Molecular Biology', 5, 122),
('botany', 'Horticulture I', 5, 123),
('botany', 'Plant Ecology', 5, 124),
('botany', 'Phycology', 5, 125),
('botany', 'Plant Morphology', 5, 126),
('botany', 'Advanced Plant Physiology', 6, 127),
('botany', 'Plant Evolution', 6, 128),
('botany', 'Medicinal Plants', 6, 129),
('botany', 'Plant Genomics', 6, 130),
('botany', 'Environmental Botany', 6, 131),
('botany', 'Horticulture II', 6, 132),
('botany', 'Plant Conservation', 7, 133),
('botany', 'Seed Science', 7, 134),
('botany', 'Plant Pathology Advanced', 7, 135),
('botany', 'Research Methods in Botany', 7, 136),
('botany', 'Plant Systematics', 7, 137),
('botany', 'Fungal Biotechnology', 7, 138),
('botany', 'Project in Botany', 8, 139),
('botany', 'Economic Botany', 8, 140),
('botany', 'Plant Tissue Culture', 8, 141),
('botany', 'Botanical Illustration', 8, 142),
('botany', 'Seminar in Botany', 8, 143),
('botany', 'Urban Forestry', 8, 144),
('computerscience', 'Introduction to Computing', 1, 145),
('computerscience', 'Programming Fundamentals', 1, 146),
('computerscience', 'Digital Logic Design', 1, 147),
('computerscience', 'Calculus for CS', 1, 148),
('computerscience', 'Computer Skills', 1, 149),
('computerscience', 'Discrete Structures', 1, 150),
('computerscience', 'Object-Oriented Programming', 2, 151),
('computerscience', 'Data Structures', 2, 152),
('computerscience', 'Computer Organization', 2, 153),
('computerscience', 'Linear Algebra for CS', 2, 154),
('computerscience', 'Probability for CS', 2, 155),
('computerscience', 'Operating Systems Basics', 2, 156),
('computerscience', 'Database Systems', 3, 157),
('computerscience', 'Software Engineering I', 3, 158),
('computerscience', 'Computer Networks I', 3, 159),
('computerscience', 'Algorithms I', 3, 160),
('computerscience', 'Theory of Automata', 3, 161),
('computerscience', 'Web Development I', 3, 162),
('computerscience', 'Operating Systems', 4, 163),
('computerscience', 'Computer Architecture', 4, 164),
('computerscience', 'Algorithms II', 4, 165),
('computerscience', 'Database Design', 4, 166),
('computerscience', 'Computer Networks II', 4, 167),
('computerscience', 'Software Engineering II', 4, 168),
('computerscience', 'Artificial Intelligence', 5, 169),
('computerscience', 'Machine Learning I', 5, 170),
('computerscience', 'Web Development II', 5, 171),
('computerscience', 'Cybersecurity Basics', 5, 172),
('computerscience', 'Computer Graphics', 5, 173),
('computerscience', 'Parallel Computing I', 5, 174),
('computerscience', 'Cloud Computing', 6, 175),
('computerscience', 'Mobile App Development', 6, 176),
('computerscience', 'Data Science', 6, 177),
('computerscience', 'Cryptography', 6, 178),
('computerscience', 'Embedded Systems', 6, 179),
('computerscience', 'Internet of Things', 6, 180),
('computerscience', 'Machine Learning II', 7, 181),
('computerscience', 'Advanced Software Engineering', 7, 182),
('computerscience', 'Distributed Systems', 7, 183),
('computerscience', 'Blockchain Technology', 7, 184),
('computerscience', 'Human-Computer Interaction', 7, 185),
('computerscience', 'Research Methods in CS', 7, 186),
('computerscience', 'Final Year Project I', 8, 187),
('computerscience', 'Big Data Analytics', 8, 188),
('computerscience', 'Parallel Computing II', 8, 189),
('computerscience', 'Cybersecurity Advanced', 8, 190),
('computerscience', 'Final Year Project II', 8, 191),
('computerscience', 'Seminar in Computer Science', 8, 192),
('zoology', 'Animal Biology I', 1, 193),
('zoology', 'Cell Biology', 1, 194),
('zoology', 'Zoological Techniques', 1, 195),
('zoology', 'Animal Diversity I', 1, 196),
('zoology', 'Ecology I', 1, 197),
('zoology', 'Genetics I', 1, 198),
('zoology', 'Animal Physiology I', 2, 199),
('zoology', 'Animal Diversity II', 2, 200),
('zoology', 'Invertebrate Zoology', 2, 201),
('zoology', 'Developmental Biology I', 2, 202),
('zoology', 'Animal Behavior I', 2, 203),
('zoology', 'Biochemistry I', 2, 204),
('zoology', 'Animal Physiology II', 3, 205),
('zoology', 'Vertebrate Zoology', 3, 206),
('zoology', 'Ecology II', 3, 207),
('zoology', 'Entomology I', 3, 208),
('zoology', 'Genetics II', 3, 209),
('zoology', 'Histology', 3, 210),
('zoology', 'Comparative Anatomy', 4, 211),
('zoology', 'Evolution I', 4, 212),
('zoology', 'Parasitology', 4, 213),
('zoology', 'Animal Behavior II', 4, 214),
('zoology', 'Biochemistry II', 4, 215),
('zoology', 'Embryology', 4, 216),
('zoology', 'Molecular Biology', 5, 217),
('zoology', 'Endocrinology', 5, 218),
('zoology', 'Immunology', 5, 219),
('zoology', 'Entomology II', 5, 220),
('zoology', 'Wildlife Biology', 5, 221),
('zoology', 'Physiological Ecology', 5, 222),
('zoology', 'Zoogeography', 6, 223),
('zoology', 'Evolution II', 6, 224),
('zoology', 'Marine Biology', 6, 225),
('zoology', 'Animal Biotechnology', 6, 226),
('zoology', 'Conservation Biology', 6, 227),
('zoology', 'Ornithology', 6, 228),
('zoology', 'Mammalogy', 7, 229),
('zoology', 'Herpetology', 7, 230),
('zoology', 'Ichthyology', 7, 231),
('zoology', 'Research Methods in Zoology', 7, 232),
('zoology', 'Advanced Genetics', 7, 233),
('zoology', 'Animal Ecology', 7, 234),
('zoology', 'Project in Zoology', 8, 235),
('zoology', 'Behavioral Ecology', 8, 236),
('zoology', 'Zoological Taxonomy', 8, 237),
('zoology', 'Applied Zoology', 8, 238),
('zoology', 'Seminar in Zoology', 8, 239),
('zoology', 'Wildlife Management', 8, 240),
('agriculture', 'Introduction to Agriculture', 1, 241),
('agriculture', 'Soil Science I', 1, 242),
('agriculture', 'Plant Biology', 1, 243),
('agriculture', 'Agricultural Chemistry', 1, 244),
('agriculture', 'Farm Machinery I', 1, 245),
('agriculture', 'Agronomy I', 1, 246),
('agriculture', 'Crop Production', 2, 247),
('agriculture', 'Soil Science II', 2, 248),
('agriculture', 'Plant Pathology I', 2, 249),
('agriculture', 'Irrigation Basics', 2, 250),
('agriculture', 'Agricultural Economics I', 2, 251),
('agriculture', 'Horticulture I', 2, 252),
('agriculture', 'Entomology I', 3, 253),
('agriculture', 'Plant Breeding I', 3, 254),
('agriculture', 'Agronomy II', 3, 255),
('agriculture', 'Soil Fertility', 3, 256),
('agriculture', 'Weed Science', 3, 257),
('agriculture', 'Agricultural Microbiology', 3, 258),
('agriculture', 'Plant Pathology II', 4, 259),
('agriculture', 'Irrigation Engineering', 4, 260),
('agriculture', 'Farm Machinery II', 4, 261),
('agriculture', 'Animal Husbandry', 4, 262),
('agriculture', 'Agricultural Economics II', 4, 263),
('agriculture', 'Horticulture II', 4, 264),
('agriculture', 'Plant Biotechnology', 5, 265),
('agriculture', 'Entomology II', 5, 266),
('agriculture', 'Crop Physiology', 5, 267),
('agriculture', 'Precision Agriculture', 5, 268),
('agriculture', 'Seed Technology', 5, 269),
('agriculture', 'Organic Farming', 5, 270),
('agriculture', 'Soil Conservation', 6, 271),
('agriculture', 'Plant Breeding II', 6, 272),
('agriculture', 'Agricultural Extension', 6, 273),
('agriculture', 'Pest Management', 6, 274),
('agriculture', 'Agribusiness Management', 6, 275),
('agriculture', 'Post-Harvest Technology', 6, 276),
('agriculture', 'Climate-Smart Agriculture', 7, 277),
('agriculture', 'Livestock Management', 7, 278),
('agriculture', 'Agricultural Policy', 7, 279),
('agriculture', 'Research Methods in Agriculture', 7, 280),
('agriculture', 'Water Management', 7, 281),
('agriculture', 'Sustainable Agriculture', 7, 282),
('agriculture', 'Project in Agriculture', 8, 283),
('agriculture', 'Farm Planning', 8, 284),
('agriculture', 'Agricultural Marketing', 8, 285),
('agriculture', 'Food Security', 8, 286),
('agriculture', 'Seminar in Agriculture', 8, 287),
('agriculture', 'Advanced Agronomy', 8, 288),
('foodscience', 'Introduction to Food Science', 1, 289),
('foodscience', 'Food Chemistry I', 1, 290),
('foodscience', 'Food Microbiology I', 1, 291),
('foodscience', 'Nutrition Basics', 1, 292),
('foodscience', 'Food Processing I', 1, 293),
('foodscience', 'Sensory Evaluation', 1, 294),
('foodscience', 'Food Chemistry II', 2, 295),
('foodscience', 'Food Microbiology II', 2, 296),
('foodscience', 'Food Analysis', 2, 297),
('foodscience', 'Food Safety I', 2, 298),
('foodscience', 'Food Engineering I', 2, 299),
('foodscience', 'Dietary Guidelines', 2, 300),
('foodscience', 'Food Preservation', 3, 301),
('foodscience', 'Food Biotechnology', 3, 302),
('foodscience', 'Nutritional Biochemistry', 3, 303),
('foodscience', 'Food Quality Control', 3, 304),
('foodscience', 'Food Packaging I', 3, 305),
('foodscience', 'Food Processing II', 3, 306),
('foodscience', 'Food Safety II', 4, 307),
('foodscience', 'Food Engineering II', 4, 308),
('foodscience', 'Food Toxicology', 4, 309),
('foodscience', 'Functional Foods', 4, 310),
('foodscience', 'Food Regulations', 4, 311),
('foodscience', 'Food Additives', 4, 312),
('foodscience', 'Advanced Food Chemistry', 5, 313),
('foodscience', 'Food Microbiology III', 5, 314),
('foodscience', 'Nutrition and Health', 5, 315),
('foodscience', 'Food Product Development', 5, 316),
('foodscience', 'Food Packaging II', 5, 317),
('foodscience', 'Food Fermentation', 5, 318),
('foodscience', 'Food Sensory Science', 6, 319),
('foodscience', 'Food Nanotechnology', 6, 320),
('foodscience', 'Food Processing III', 6, 321),
('foodscience', 'Food Safety Management', 6, 322),
('foodscience', 'Nutraceuticals', 6, 323),
('foodscience', 'Food Supply Chain', 6, 324),
('foodscience', 'Advanced Nutrition', 7, 325),
('foodscience', 'Food Biotechnology II', 7, 326),
('foodscience', 'Food Quality Assurance', 7, 327),
('foodscience', 'Research Methods in Food Science', 7, 328),
('foodscience', 'Food Waste Management', 7, 329),
('foodscience', 'Clinical Nutrition', 7, 330),
('foodscience', 'Project in Food Science', 8, 331),
('foodscience', 'Food Industry Trends', 8, 332),
('foodscience', 'Food Innovation', 8, 333),
('foodscience', 'Global Food Systems', 8, 334),
('foodscience', 'Seminar in Food Science', 8, 335),
('foodscience', 'Sustainable Food Production', 8, 336);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `email`, `password`, `department`) VALUES
(1, 'Dr. Hassan Raza', 'hassan.raza@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Mathematics'),
(2, 'Dr. Sana Qureshi', 'sana.qureshi@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Chemistry'),
(3, 'Dr. Fahad Iqbal', 'fahad.iqbal@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Computer Science'),
(4, 'Dr. Rabia Akram', 'rabia.akram@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Botany'),
(5, 'Dr. Kamran Javed', 'kamran.javed@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Zoology'),
(6, 'Dr. Hina Tariq', 'hina.tariq@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Agriculture'),
(7, 'Dr. Usman Riaz', 'usman.riaz@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Food Sciences');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `advisors`
--
ALTER TABLE `advisors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `application_subjects`
--
ALTER TABLE `application_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `selected_subjects`
--
ALTER TABLE `selected_subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advisors`
--
ALTER TABLE `advisors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `application_subjects`
--
ALTER TABLE `application_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `selected_subjects`
--
ALTER TABLE `selected_subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=337;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
