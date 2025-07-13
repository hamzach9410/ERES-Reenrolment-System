<?php
session_start();
include 'db.php'; // Updated path to db.php

$subjects = [
    // Mathematics
    ['id' => 1, 'department' => 'math', 'subject_name' => 'Calculus I', 'semester' => 1],
    ['id' => 2, 'department' => 'math', 'subject_name' => 'Algebra I', 'semester' => 1],
    ['id' => 3, 'department' => 'math', 'subject_name' => 'Geometry', 'semester' => 1],
    ['id' => 4, 'department' => 'math', 'subject_name' => 'Trigonometry', 'semester' => 1],
    ['id' => 5, 'department' => 'math', 'subject_name' => 'Mathematical Reasoning', 'semester' => 1],
    ['id' => 6, 'department' => 'math', 'subject_name' => 'Statistics I', 'semester' => 1],
    ['id' => 7, 'department' => 'math', 'subject_name' => 'Calculus II', 'semester' => 2],
    ['id' => 8, 'department' => 'math', 'subject_name' => 'Linear Algebra I', 'semester' => 2],
    ['id' => 9, 'department' => 'math', 'subject_name' => 'Differential Equations I', 'semester' => 2],
    ['id' => 10, 'department' => 'math', 'subject_name' => 'Number Theory', 'semester' => 2],
    ['id' => 11, 'department' => 'math', 'subject_name' => 'Probability', 'semester' => 2],
    ['id' => 12, 'department' => 'math', 'subject_name' => 'Discrete Mathematics', 'semester' => 2],
    ['id' => 13, 'department' => 'math', 'subject_name' => 'Calculus III', 'semester' => 3],
    ['id' => 14, 'department' => 'math', 'subject_name' => 'Linear Algebra II', 'semester' => 3],
    ['id' => 15, 'department' => 'math', 'subject_name' => 'Real Analysis I', 'semester' => 3],
    ['id' => 16, 'department' => 'math', 'subject_name' => 'Complex Numbers', 'semester' => 3],
    ['id' => 17, 'department' => 'math', 'subject_name' => 'Mathematical Modeling', 'semester' => 3],
    ['id' => 18, 'department' => 'math', 'subject_name' => 'Numerical Methods I', 'semester' => 3],
    ['id' => 19, 'department' => 'math', 'subject_name' => 'Abstract Algebra I', 'semester' => 4],
    ['id' => 20, 'department' => 'math', 'subject_name' => 'Topology I', 'semester' => 4],
    ['id' => 21, 'department' => 'math', 'subject_name' => 'Differential Equations II', 'semester' => 4],
    ['id' => 22, 'department' => 'math', 'subject_name' => 'Statistics II', 'semester' => 4],
    ['id' => 23, 'department' => 'math', 'subject_name' => 'Graph Theory', 'semester' => 4],
    ['id' => 24, 'department' => 'math', 'subject_name' => 'Set Theory', 'semester' => 4],
    ['id' => 25, 'department' => 'math', 'subject_name' => 'Real Analysis II', 'semester' => 5],
    ['id' => 26, 'department' => 'math', 'subject_name' => 'Complex Analysis', 'semester' => 5],
    ['id' => 27, 'department' => 'math', 'subject_name' => 'Numerical Methods II', 'semester' => 5],
    ['id' => 28, 'department' => 'math', 'subject_name' => 'Mathematical Statistics', 'semester' => 5],
    ['id' => 29, 'department' => 'math', 'subject_name' => 'Optimization Theory', 'semester' => 5],
    ['id' => 30, 'department' => 'math', 'subject_name' => 'Abstract Algebra II', 'semester' => 5],
    ['id' => 31, 'department' => 'math', 'subject_name' => 'Topology II', 'semester' => 6],
    ['id' => 32, 'department' => 'math', 'subject_name' => 'Functional Analysis', 'semester' => 6],
    ['id' => 33, 'department' => 'math', 'subject_name' => 'Partial Differential Equations', 'semester' => 6],
    ['id' => 34, 'department' => 'math', 'subject_name' => 'Combinatorics', 'semester' => 6],
    ['id' => 35, 'department' => 'math', 'subject_name' => 'Cryptography Basics', 'semester' => 6],
    ['id' => 36, 'department' => 'math', 'subject_name' => 'Dynamical Systems', 'semester' => 6],
    ['id' => 37, 'department' => 'math', 'subject_name' => 'Advanced Probability', 'semester' => 7],
    ['id' => 38, 'department' => 'math', 'subject_name' => 'Stochastic Processes', 'semester' => 7],
    ['id' => 39, 'department' => 'math', 'subject_name' => 'Mathematical Physics', 'semester' => 7],
    ['id' => 40, 'department' => 'math', 'subject_name' => 'Algebraic Geometry', 'semester' => 7],
    ['id' => 41, 'department' => 'math', 'subject_name' => 'Measure Theory', 'semester' => 7],
    ['id' => 42, 'department' => 'math', 'subject_name' => 'Research Methods in Math', 'semester' => 7],
    ['id' => 43, 'department' => 'math', 'subject_name' => 'Advanced Numerical Analysis', 'semester' => 8],
    ['id' => 44, 'department' => 'math', 'subject_name' => 'Mathematical Logic', 'semester' => 8],
    ['id' => 45, 'department' => 'math', 'subject_name' => 'Project in Mathematics', 'semester' => 8],
    ['id' => 46, 'department' => 'math', 'subject_name' => 'Coding Theory', 'semester' => 8],
    ['id' => 47, 'department' => 'math', 'subject_name' => 'History of Mathematics', 'semester' => 8],
    ['id' => 48, 'department' => 'math', 'subject_name' => 'Seminar in Mathematics', 'semester' => 8],
    // Chemistry
    ['id' => 49, 'department' => 'chemistry', 'subject_name' => 'General Chemistry I', 'semester' => 1],
    ['id' => 50, 'department' => 'chemistry', 'subject_name' => 'Inorganic Chemistry I', 'semester' => 1],
    ['id' => 51, 'department' => 'chemistry', 'subject_name' => 'Chemical Bonding', 'semester' => 1],
    ['id' => 52, 'department' => 'chemistry', 'subject_name' => 'Laboratory Safety', 'semester' => 1],
    ['id' => 53, 'department' => 'chemistry', 'subject_name' => 'Stoichiometry', 'semester' => 1],
    ['id' => 54, 'department' => 'chemistry', 'subject_name' => 'Atomic Structure', 'semester' => 1],
    ['id' => 55, 'department' => 'chemistry', 'subject_name' => 'Organic Chemistry I', 'semester' => 2],
    ['id' => 56, 'department' => 'chemistry', 'subject_name' => 'General Chemistry II', 'semester' => 2],
    ['id' => 57, 'department' => 'chemistry', 'subject_name' => 'Thermodynamics I', 'semester' => 2],
    ['id' => 58, 'department' => 'chemistry', 'subject_name' => 'Chemical Kinetics', 'semester' => 2],
    ['id' => 59, 'department' => 'chemistry', 'subject_name' => 'Acid-Base Chemistry', 'semester' => 2],
    ['id' => 60, 'department' => 'chemistry', 'subject_name' => 'Spectroscopy Basics', 'semester' => 2],
    ['id' => 61, 'department' => 'chemistry', 'subject_name' => 'Physical Chemistry I', 'semester' => 3],
    ['id' => 62, 'department' => 'chemistry', 'subject_name' => 'Organic Chemistry II', 'semester' => 3],
    ['id' => 63, 'department' => 'chemistry', 'subject_name' => 'Inorganic Chemistry II', 'semester' => 3],
    ['id' => 64, 'department' => 'chemistry', 'subject_name' => 'Electrochemistry', 'semester' => 3],
    ['id' => 65, 'department' => 'chemistry', 'subject_name' => 'Analytical Chemistry I', 'semester' => 3],
    ['id' => 66, 'department' => 'chemistry', 'subject_name' => 'Chemical Equilibrium', 'semester' => 3],
    ['id' => 67, 'department' => 'chemistry', 'subject_name' => 'Physical Chemistry II', 'semester' => 4],
    ['id' => 68, 'department' => 'chemistry', 'subject_name' => 'Polymer Chemistry', 'semester' => 4],
    ['id' => 69, 'department' => 'chemistry', 'subject_name' => 'Biochemistry I', 'semester' => 4],
    ['id' => 70, 'department' => 'chemistry', 'subject_name' => 'Thermodynamics II', 'semester' => 4],
    ['id' => 71, 'department' => 'chemistry', 'subject_name' => 'Quantum Chemistry', 'semester' => 4],
    ['id' => 72, 'department' => 'chemistry', 'subject_name' => 'Lab Techniques', 'semester' => 4],
    ['id' => 73, 'department' => 'chemistry', 'subject_name' => 'Analytical Chemistry II', 'semester' => 5],
    ['id' => 74, 'department' => 'chemistry', 'subject_name' => 'Environmental Chemistry', 'semester' => 5],
    ['id' => 75, 'department' => 'chemistry', 'subject_name' => 'Biochemistry II', 'semester' => 5],
    ['id' => 76, 'department' => 'chemistry', 'subject_name' => 'Surface Chemistry', 'semester' => 5],
    ['id' => 77, 'department' => 'chemistry', 'subject_name' => 'Organic Synthesis', 'semester' => 5],
    ['id' => 78, 'department' => 'chemistry', 'subject_name' => 'Chemical Instrumentation', 'semester' => 5],
    ['id' => 79, 'department' => 'chemistry', 'subject_name' => 'Computational Chemistry', 'semester' => 6],
    ['id' => 80, 'department' => 'chemistry', 'subject_name' => 'Medicinal Chemistry', 'semester' => 6],
    ['id' => 81, 'department' => 'chemistry', 'subject_name' => 'Photochemistry', 'semester' => 6],
    ['id' => 82, 'department' => 'chemistry', 'subject_name' => 'Advanced Inorganic Chemistry', 'semester' => 6],
    ['id' => 83, 'department' => 'chemistry', 'subject_name' => 'Catalysis', 'semester' => 6],
    ['id' => 84, 'department' => 'chemistry', 'subject_name' => 'Materials Chemistry', 'semester' => 6],
    ['id' => 85, 'department' => 'chemistry', 'subject_name' => 'Advanced Organic Chemistry', 'semester' => 7],
    ['id' => 86, 'department' => 'chemistry', 'subject_name' => 'Chemical Engineering Basics', 'semester' => 7],
    ['id' => 87, 'department' => 'chemistry', 'subject_name' => 'Nuclear Chemistry', 'semester' => 7],
    ['id' => 88, 'department' => 'chemistry', 'subject_name' => 'Spectroscopy Advanced', 'semester' => 7],
    ['id' => 89, 'department' => 'chemistry', 'subject_name' => 'Research Methods in Chemistry', 'semester' => 7],
    ['id' => 90, 'department' => 'chemistry', 'subject_name' => 'Green Chemistry', 'semester' => 7],
    ['id' => 91, 'department' => 'chemistry', 'subject_name' => 'Project in Chemistry', 'semester' => 8],
    ['id' => 92, 'department' => 'chemistry', 'subject_name' => 'Industrial Chemistry', 'semester' => 8],
    ['id' => 93, 'department' => 'chemistry', 'subject_name' => 'Forensic Chemistry', 'semester' => 8],
    ['id' => 94, 'department' => 'chemistry', 'subject_name' => 'Nanochemistry', 'semester' => 8],
    ['id' => 95, 'department' => 'chemistry', 'subject_name' => 'Seminar in Chemistry', 'semester' => 8],
    ['id' => 96, 'department' => 'chemistry', 'subject_name' => 'Chemical Toxicology', 'semester' => 8],
    // Botany
    ['id' => 97, 'department' => 'botany', 'subject_name' => 'Plant Biology I', 'semester' => 1],
    ['id' => 98, 'department' => 'botany', 'subject_name' => 'Cell Biology', 'semester' => 1],
    ['id' => 99, 'department' => 'botany', 'subject_name' => 'Botanical Techniques', 'semester' => 1],
    ['id' => 100, 'department' => 'botany', 'subject_name' => 'Plant Anatomy', 'semester' => 1],
    ['id' => 101, 'department' => 'botany', 'subject_name' => 'Photosynthesis', 'semester' => 1],
    ['id' => 102, 'department' => 'botany', 'subject_name' => 'Ecology I', 'semester' => 1],
    ['id' => 103, 'department' => 'botany', 'subject_name' => 'Plant Physiology I', 'semester' => 2],
    ['id' => 104, 'department' => 'botany', 'subject_name' => 'Plant Diversity', 'semester' => 2],
    ['id' => 105, 'department' => 'botany', 'subject_name' => 'Mycology', 'semester' => 2],
    ['id' => 106, 'department' => 'botany', 'subject_name' => 'Plant Taxonomy I', 'semester' => 2],
    ['id' => 107, 'department' => 'botany', 'subject_name' => 'Genetics I', 'semester' => 2],
    ['id' => 108, 'department' => 'botany', 'subject_name' => 'Bryophytes', 'semester' => 2],
    ['id' => 109, 'department' => 'botany', 'subject_name' => 'Plant Physiology II', 'semester' => 3],
    ['id' => 110, 'department' => 'botany', 'subject_name' => 'Plant Pathology', 'semester' => 3],
    ['id' => 111, 'department' => 'botany', 'subject_name' => 'Pteridophytes', 'semester' => 3],
    ['id' => 112, 'department' => 'botany', 'subject_name' => 'Ecology II', 'semester' => 3],
    ['id' => 113, 'department' => 'botany', 'subject_name' => 'Plant Taxonomy II', 'semester' => 3],
    ['id' => 114, 'department' => 'botany', 'subject_name' => 'Algae Studies', 'semester' => 3],
    ['id' => 115, 'department' => 'botany', 'subject_name' => 'Plant Biochemistry', 'semester' => 4],
    ['id' => 116, 'department' => 'botany', 'subject_name' => 'Gymnosperms', 'semester' => 4],
    ['id' => 117, 'department' => 'botany', 'subject_name' => 'Plant Breeding', 'semester' => 4],
    ['id' => 118, 'department' => 'botany', 'subject_name' => 'Ethnobotany', 'semester' => 4],
    ['id' => 119, 'department' => 'botany', 'subject_name' => 'Genetics II', 'semester' => 4],
    ['id' => 120, 'department' => 'botany', 'subject_name' => 'Soil-Plant Interactions', 'semester' => 4],
    ['id' => 121, 'department' => 'botany', 'subject_name' => 'Plant Biotechnology', 'semester' => 5],
    ['id' => 122, 'department' => 'botany', 'subject_name' => 'Plant Molecular Biology', 'semester' => 5],
    ['id' => 123, 'department' => 'botany', 'subject_name' => 'Horticulture I', 'semester' => 5],
    ['id' => 124, 'department' => 'botany', 'subject_name' => 'Plant Ecology', 'semester' => 5],
    ['id' => 125, 'department' => 'botany', 'subject_name' => 'Phycology', 'semester' => 5],
    ['id' => 126, 'department' => 'botany', 'subject_name' => 'Plant Morphology', 'semester' => 5],
    ['id' => 127, 'department' => 'botany', 'subject_name' => 'Advanced Plant Physiology', 'semester' => 6],
    ['id' => 128, 'department' => 'botany', 'subject_name' => 'Plant Evolution', 'semester' => 6],
    ['id' => 129, 'department' => 'botany', 'subject_name' => 'Medicinal Plants', 'semester' => 6],
    ['id' => 130, 'department' => 'botany', 'subject_name' => 'Plant Genomics', 'semester' => 6],
    ['id' => 131, 'department' => 'botany', 'subject_name' => 'Environmental Botany', 'semester' => 6],
    ['id' => 132, 'department' => 'botany', 'subject_name' => 'Horticulture II', 'semester' => 6],
    ['id' => 133, 'department' => 'botany', 'subject_name' => 'Plant Conservation', 'semester' => 7],
    ['id' => 134, 'department' => 'botany', 'subject_name' => 'Seed Science', 'semester' => 7],
    ['id' => 135, 'department' => 'botany', 'subject_name' => 'Plant Pathology Advanced', 'semester' => 7],
    ['id' => 136, 'department' => 'botany', 'subject_name' => 'Research Methods in Botany', 'semester' => 7],
    ['id' => 137, 'department' => 'botany', 'subject_name' => 'Plant Systematics', 'semester' => 7],
    ['id' => 138, 'department' => 'botany', 'subject_name' => 'Fungal Biotechnology', 'semester' => 7],
    ['id' => 139, 'department' => 'botany', 'subject_name' => 'Project in Botany', 'semester' => 8],
    ['id' => 140, 'department' => 'botany', 'subject_name' => 'Economic Botany', 'semester' => 8],
    ['id' => 141, 'department' => 'botany', 'subject_name' => 'Plant Tissue Culture', 'semester' => 8],
    ['id' => 142, 'department' => 'botany', 'subject_name' => 'Botanical Illustration', 'semester' => 8],
    ['id' => 143, 'department' => 'botany', 'subject_name' => 'Seminar in Botany', 'semester' => 8],
    ['id' => 144, 'department' => 'botany', 'subject_name' => 'Urban Forestry', 'semester' => 8],
    // Computer Science
    ['id' => 145, 'department' => 'computerscience', 'subject_name' => 'Introduction to Computing', 'semester' => 1],
    ['id' => 146, 'department' => 'computerscience', 'subject_name' => 'Programming Fundamentals', 'semester' => 1],
    ['id' => 147, 'department' => 'computerscience', 'subject_name' => 'Digital Logic Design', 'semester' => 1],
    ['id' => 148, 'department' => 'computerscience', 'subject_name' => 'Calculus for CS', 'semester' => 1],
    ['id' => 149, 'department' => 'computerscience', 'subject_name' => 'Computer Skills', 'semester' => 1],
    ['id' => 150, 'department' => 'computerscience', 'subject_name' => 'Discrete Structures', 'semester' => 1],
    ['id' => 151, 'department' => 'computerscience', 'subject_name' => 'Object-Oriented Programming', 'semester' => 2],
    ['id' => 152, 'department' => 'computerscience', 'subject_name' => 'Data Structures', 'semester' => 2],
    ['id' => 153, 'department' => 'computerscience', 'subject_name' => 'Computer Organization', 'semester' => 2],
    ['id' => 154, 'department' => 'computerscience', 'subject_name' => 'Linear Algebra for CS', 'semester' => 2],
    ['id' => 155, 'department' => 'computerscience', 'subject_name' => 'Probability for CS', 'semester' => 2],
    ['id' => 156, 'department' => 'computerscience', 'subject_name' => 'Operating Systems Basics', 'semester' => 2],
    ['id' => 157, 'department' => 'computerscience', 'subject_name' => 'Database Systems', 'semester' => 3],
    ['id' => 158, 'department' => 'computerscience', 'subject_name' => 'Software Engineering I', 'semester' => 3],
    ['id' => 159, 'department' => 'computerscience', 'subject_name' => 'Computer Networks I', 'semester' => 3],
    ['id' => 160, 'department' => 'computerscience', 'subject_name' => 'Algorithms I', 'semester' => 3],
    ['id' => 161, 'department' => 'computerscience', 'subject_name' => 'Theory of Automata', 'semester' => 3],
    ['id' => 162, 'department' => 'computerscience', 'subject_name' => 'Web Development I', 'semester' => 3],
    ['id' => 163, 'department' => 'computerscience', 'subject_name' => 'Operating Systems', 'semester' => 4],
    ['id' => 164, 'department' => 'computerscience', 'subject_name' => 'Computer Architecture', 'semester' => 4],
    ['id' => 165, 'department' => 'computerscience', 'subject_name' => 'Algorithms II', 'semester' => 4],
    ['id' => 166, 'department' => 'computerscience', 'subject_name' => 'Database Design', 'semester' => 4],
    ['id' => 167, 'department' => 'computerscience', 'subject_name' => 'Computer Networks II', 'semester' => 4],
    ['id' => 168, 'department' => 'computerscience', 'subject_name' => 'Software Engineering II', 'semester' => 4],
    ['id' => 169, 'department' => 'computerscience', 'subject_name' => 'Artificial Intelligence', 'semester' => 5],
    ['id' => 170, 'department' => 'computerscience', 'subject_name' => 'Machine Learning I', 'semester' => 5],
    ['id' => 171, 'department' => 'computerscience', 'subject_name' => 'Web Development II', 'semester' => 5],
    ['id' => 172, 'department' => 'computerscience', 'subject_name' => 'Cybersecurity Basics', 'semester' => 5],
    ['id' => 173, 'department' => 'computerscience', 'subject_name' => 'Computer Graphics', 'semester' => 5],
    ['id' => 174, 'department' => 'computerscience', 'subject_name' => 'Parallel Computing I', 'semester' => 5],
    ['id' => 175, 'department' => 'computerscience', 'subject_name' => 'Cloud Computing', 'semester' => 6],
    ['id' => 176, 'department' => 'computerscience', 'subject_name' => 'Mobile App Development', 'semester' => 6],
    ['id' => 177, 'department' => 'computerscience', 'subject_name' => 'Data Science', 'semester' => 6],
    ['id' => 178, 'department' => 'computerscience', 'subject_name' => 'Cryptography', 'semester' => 6],
    ['id' => 179, 'department' => 'computerscience', 'subject_name' => 'Embedded Systems', 'semester' => 6],
    ['id' => 180, 'department' => 'computerscience', 'subject_name' => 'Internet of Things', 'semester' => 6],
    ['id' => 181, 'department' => 'computerscience', 'subject_name' => 'Machine Learning II', 'semester' => 7],
    ['id' => 182, 'department' => 'computerscience', 'subject_name' => 'Advanced Software Engineering', 'semester' => 7],
    ['id' => 183, 'department' => 'computerscience', 'subject_name' => 'Distributed Systems', 'semester' => 7],
    ['id' => 184, 'department' => 'computerscience', 'subject_name' => 'Blockchain Technology', 'semester' => 7],
    ['id' => 185, 'department' => 'computerscience', 'subject_name' => 'Human-Computer Interaction', 'semester' => 7],
    ['id' => 186, 'department' => 'computerscience', 'subject_name' => 'Research Methods in CS', 'semester' => 7],
    ['id' => 187, 'department' => 'computerscience', 'subject_name' => 'Final Year Project I', 'semester' => 8],
    ['id' => 188, 'department' => 'computerscience', 'subject_name' => 'Big Data Analytics', 'semester' => 8],
    ['id' => 189, 'department' => 'computerscience', 'subject_name' => 'Parallel Computing II', 'semester' => 8],
    ['id' => 190, 'department' => 'computerscience', 'subject_name' => 'Cybersecurity Advanced', 'semester' => 8],
    ['id' => 191, 'department' => 'computerscience', 'subject_name' => 'Final Year Project II', 'semester' => 8],
    ['id' => 192, 'department' => 'computerscience', 'subject_name' => 'Seminar in Computer Science', 'semester' => 8],
    // Zoology
    ['id' => 193, 'department' => 'zoology', 'subject_name' => 'Animal Biology I', 'semester' => 1],
    ['id' => 194, 'department' => 'zoology', 'subject_name' => 'Cell Biology', 'semester' => 1],
    ['id' => 195, 'department' => 'zoology', 'subject_name' => 'Zoological Techniques', 'semester' => 1],
    ['id' => 196, 'department' => 'zoology', 'subject_name' => 'Animal Diversity I', 'semester' => 1],
    ['id' => 197, 'department' => 'zoology', 'subject_name' => 'Ecology I', 'semester' => 1],
    ['id' => 198, 'department' => 'zoology', 'subject_name' => 'Genetics I', 'semester' => 1],
    ['id' => 199, 'department' => 'zoology', 'subject_name' => 'Animal Physiology I', 'semester' => 2],
    ['id' => 200, 'department' => 'zoology', 'subject_name' => 'Animal Diversity II', 'semester' => 2],
    ['id' => 201, 'department' => 'zoology', 'subject_name' => 'Invertebrate Zoology', 'semester' => 2],
    ['id' => 202, 'department' => 'zoology', 'subject_name' => 'Developmental Biology I', 'semester' => 2],
    ['id' => 203, 'department' => 'zoology', 'subject_name' => 'Animal Behavior I', 'semester' => 2],
    ['id' => 204, 'department' => 'zoology', 'subject_name' => 'Biochemistry I', 'semester' => 2],
    ['id' => 205, 'department' => 'zoology', 'subject_name' => 'Animal Physiology II', 'semester' => 3],
    ['id' => 206, 'department' => 'zoology', 'subject_name' => 'Vertebrate Zoology', 'semester' => 3],
    ['id' => 207, 'department' => 'zoology', 'subject_name' => 'Ecology II', 'semester' => 3],
    ['id' => 208, 'department' => 'zoology', 'subject_name' => 'Entomology I', 'semester' => 3],
    ['id' => 209, 'department' => 'zoology', 'subject_name' => 'Genetics II', 'semester' => 3],
    ['id' => 210, 'department' => 'zoology', 'subject_name' => 'Histology', 'semester' => 3],
    ['id' => 211, 'department' => 'zoology', 'subject_name' => 'Comparative Anatomy', 'semester' => 4],
    ['id' => 212, 'department' => 'zoology', 'subject_name' => 'Evolution I', 'semester' => 4],
    ['id' => 213, 'department' => 'zoology', 'subject_name' => 'Parasitology', 'semester' => 4],
    ['id' => 214, 'department' => 'zoology', 'subject_name' => 'Animal Behavior II', 'semester' => 4],
    ['id' => 215, 'department' => 'zoology', 'subject_name' => 'Biochemistry II', 'semester' => 4],
    ['id' => 216, 'department' => 'zoology', 'subject_name' => 'Embryology', 'semester' => 4],
    ['id' => 217, 'department' => 'zoology', 'subject_name' => 'Molecular Biology', 'semester' => 5],
    ['id' => 218, 'department' => 'zoology', 'subject_name' => 'Endocrinology', 'semester' => 5],
    ['id' => 219, 'department' => 'zoology', 'subject_name' => 'Immunology', 'semester' => 5],
    ['id' => 220, 'department' => 'zoology', 'subject_name' => 'Entomology II', 'semester' => 5],
    ['id' => 221, 'department' => 'zoology', 'subject_name' => 'Wildlife Biology', 'semester' => 5],
    ['id' => 222, 'department' => 'zoology', 'subject_name' => 'Physiological Ecology', 'semester' => 5],
    ['id' => 223, 'department' => 'zoology', 'subject_name' => 'Zoogeography', 'semester' => 6],
    ['id' => 224, 'department' => 'zoology', 'subject_name' => 'Evolution II', 'semester' => 6],
    ['id' => 225, 'department' => 'zoology', 'subject_name' => 'Marine Biology', 'semester' => 6],
    ['id' => 226, 'department' => 'zoology', 'subject_name' => 'Animal Biotechnology', 'semester' => 6],
    ['id' => 227, 'department' => 'zoology', 'subject_name' => 'Conservation Biology', 'semester' => 6],
    ['id' => 228, 'department' => 'zoology', 'subject_name' => 'Ornithology', 'semester' => 6],
    ['id' => 229, 'department' => 'zoology', 'subject_name' => 'Mammalogy', 'semester' => 7],
    ['id' => 230, 'department' => 'zoology', 'subject_name' => 'Herpetology', 'semester' => 7],
    ['id' => 231, 'department' => 'zoology', 'subject_name' => 'Ichthyology', 'semester' => 7],
    ['id' => 232, 'department' => 'zoology', 'subject_name' => 'Research Methods in Zoology', 'semester' => 7],
    ['id' => 233, 'department' => 'zoology', 'subject_name' => 'Advanced Genetics', 'semester' => 7],
    ['id' => 234, 'department' => 'zoology', 'subject_name' => 'Animal Ecology', 'semester' => 7],
    ['id' => 235, 'department' => 'zoology', 'subject_name' => 'Project in Zoology', 'semester' => 8],
    ['id' => 236, 'department' => 'zoology', 'subject_name' => 'Behavioral Ecology', 'semester' => 8],
    ['id' => 237, 'department' => 'zoology', 'subject_name' => 'Zoological Taxonomy', 'semester' => 8],
    ['id' => 238, 'department' => 'zoology', 'subject_name' => 'Applied Zoology', 'semester' => 8],
    ['id' => 239, 'department' => 'zoology', 'subject_name' => 'Seminar in Zoology', 'semester' => 8],
    ['id' => 240, 'department' => 'zoology', 'subject_name' => 'Wildlife Management', 'semester' => 8],
    // Agriculture
    ['id' => 241, 'department' => 'agriculture', 'subject_name' => 'Introduction to Agriculture', 'semester' => 1],
    ['id' => 242, 'department' => 'agriculture', 'subject_name' => 'Soil Science I', 'semester' => 1],
    ['id' => 243, 'department' => 'agriculture', 'subject_name' => 'Plant Biology', 'semester' => 1],
    ['id' => 244, 'department' => 'agriculture', 'subject_name' => 'Agricultural Chemistry', 'semester' => 1],
    ['id' => 245, 'department' => 'agriculture', 'subject_name' => 'Farm Machinery I', 'semester' => 1],
    ['id' => 246, 'department' => 'agriculture', 'subject_name' => 'Agronomy I', 'semester' => 1],
    ['id' => 247, 'department' => 'agriculture', 'subject_name' => 'Crop Production', 'semester' => 2],
    ['id' => 248, 'department' => 'agriculture', 'subject_name' => 'Soil Science II', 'semester' => 2],
    ['id' => 249, 'department' => 'agriculture', 'subject_name' => 'Plant Pathology I', 'semester' => 2],
    ['id' => 250, 'department' => 'agriculture', 'subject_name' => 'Irrigation Basics', 'semester' => 2],
    ['id' => 251, 'department' => 'agriculture', 'subject_name' => 'Agricultural Economics I', 'semester' => 2],
    ['id' => 252, 'department' => 'agriculture', 'subject_name' => 'Horticulture I', 'semester' => 2],
    ['id' => 253, 'department' => 'agriculture', 'subject_name' => 'Entomology I', 'semester' => 3],
    ['id' => 254, 'department' => 'agriculture', 'subject_name' => 'Plant Breeding I', 'semester' => 3],
    ['id' => 255, 'department' => 'agriculture', 'subject_name' => 'Agronomy II', 'semester' => 3],
    ['id' => 256, 'department' => 'agriculture', 'subject_name' => 'Soil Fertility', 'semester' => 3],
    ['id' => 257, 'department' => 'agriculture', 'subject_name' => 'Weed Science', 'semester' => 3],
    ['id' => 258, 'department' => 'agriculture', 'subject_name' => 'Agricultural Microbiology', 'semester' => 3],
    ['id' => 259, 'department' => 'agriculture', 'subject_name' => 'Plant Pathology II', 'semester' => 4],
    ['id' => 260, 'department' => 'agriculture', 'subject_name' => 'Irrigation Engineering', 'semester' => 4],
    ['id' => 261, 'department' => 'agriculture', 'subject_name' => 'Farm Machinery II', 'semester' => 4],
    ['id' => 262, 'department' => 'agriculture', 'subject_name' => 'Animal Husbandry', 'semester' => 4],
    ['id' => 263, 'department' => 'agriculture', 'subject_name' => 'Agricultural Economics II', 'semester' => 4],
    ['id' => 264, 'department' => 'agriculture', 'subject_name' => 'Horticulture II', 'semester' => 4],
    ['id' => 265, 'department' => 'agriculture', 'subject_name' => 'Plant Biotechnology', 'semester' => 5],
    ['id' => 266, 'department' => 'agriculture', 'subject_name' => 'Entomology II', 'semester' => 5],
    ['id' => 267, 'department' => 'agriculture', 'subject_name' => 'Crop Physiology', 'semester' => 5],
    ['id' => 268, 'department' => 'agriculture', 'subject_name' => 'Precision Agriculture', 'semester' => 5],
    ['id' => 269, 'department' => 'agriculture', 'subject_name' => 'Seed Technology', 'semester' => 5],
    ['id' => 270, 'department' => 'agriculture', 'subject_name' => 'Organic Farming', 'semester' => 5],
    ['id' => 271, 'department' => 'agriculture', 'subject_name' => 'Soil Conservation', 'semester' => 6],
    ['id' => 272, 'department' => 'agriculture', 'subject_name' => 'Plant Breeding II', 'semester' => 6],
    ['id' => 273, 'department' => 'agriculture', 'subject_name' => 'Agricultural Extension', 'semester' => 6],
    ['id' => 274, 'department' => 'agriculture', 'subject_name' => 'Pest Management', 'semester' => 6],
    ['id' => 275, 'department' => 'agriculture', 'subject_name' => 'Agribusiness Management', 'semester' => 6],
    ['id' => 276, 'department' => 'agriculture', 'subject_name' => 'Post-Harvest Technology', 'semester' => 6],
    ['id' => 277, 'department' => 'agriculture', 'subject_name' => 'Climate-Smart Agriculture', 'semester' => 7],
    ['id' => 278, 'department' => 'agriculture', 'subject_name' => 'Livestock Management', 'semester' => 7],
    ['id' => 279, 'department' => 'agriculture', 'subject_name' => 'Agricultural Policy', 'semester' => 7],
    ['id' => 280, 'department' => 'agriculture', 'subject_name' => 'Research Methods in Agriculture', 'semester' => 7],
    ['id' => 281, 'department' => 'agriculture', 'subject_name' => 'Water Management', 'semester' => 7],
    ['id' => 282, 'department' => 'agriculture', 'subject_name' => 'Sustainable Agriculture', 'semester' => 7],
    ['id' => 283, 'department' => 'agriculture', 'subject_name' => 'Project in Agriculture', 'semester' => 8],
    ['id' => 284, 'department' => 'agriculture', 'subject_name' => 'Farm Planning', 'semester' => 8],
    ['id' => 285, 'department' => 'agriculture', 'subject_name' => 'Agricultural Marketing', 'semester' => 8],
    ['id' => 286, 'department' => 'agriculture', 'subject_name' => 'Food Security', 'semester' => 8],
    ['id' => 287, 'department' => 'agriculture', 'subject_name' => 'Seminar in Agriculture', 'semester' => 8],
    ['id' => 288, 'department' => 'agriculture', 'subject_name' => 'Advanced Agronomy', 'semester' => 8],
    // Food Science
    ['id' => 289, 'department' => 'foodscience', 'subject_name' => 'Introduction to Food Science', 'semester' => 1],
    ['id' => 290, 'department' => 'foodscience', 'subject_name' => 'Food Chemistry I', 'semester' => 1],
    ['id' => 291, 'department' => 'foodscience', 'subject_name' => 'Food Microbiology I', 'semester' => 1],
    ['id' => 292, 'department' => 'foodscience', 'subject_name' => 'Nutrition Basics', 'semester' => 1],
    ['id' => 293, 'department' => 'foodscience', 'subject_name' => 'Food Processing I', 'semester' => 1],
    ['id' => 294, 'department' => 'foodscience', 'subject_name' => 'Sensory Evaluation', 'semester' => 1],
    ['id' => 295, 'department' => 'foodscience', 'subject_name' => 'Food Chemistry II', 'semester' => 2],
    ['id' => 296, 'department' => 'foodscience', 'subject_name' => 'Food Microbiology II', 'semester' => 2],
    ['id' => 297, 'department' => 'foodscience', 'subject_name' => 'Food Analysis', 'semester' => 2],
    ['id' => 298, 'department' => 'foodscience', 'subject_name' => 'Food Safety I', 'semester' => 2],
    ['id' => 299, 'department' => 'foodscience', 'subject_name' => 'Food Engineering I', 'semester' => 2],
    ['id' => 300, 'department' => 'foodscience', 'subject_name' => 'Dietary Guidelines', 'semester' => 2],
    ['id' => 301, 'department' => 'foodscience', 'subject_name' => 'Food Preservation', 'semester' => 3],
    ['id' => 302, 'department' => 'foodscience', 'subject_name' => 'Food Biotechnology', 'semester' => 3],
    ['id' => 303, 'department' => 'foodscience', 'subject_name' => 'Nutritional Biochemistry', 'semester' => 3],
    ['id' => 304, 'department' => 'foodscience', 'subject_name' => 'Food Quality Control', 'semester' => 3],
    ['id' => 305, 'department' => 'foodscience', 'subject_name' => 'Food Packaging I', 'semester' => 3],
    ['id' => 306, 'department' => 'foodscience', 'subject_name' => 'Food Processing II', 'semester' => 3],
    ['id' => 307, 'department' => 'foodscience', 'subject_name' => 'Food Safety II', 'semester' => 4],
    ['id' => 308, 'department' => 'foodscience', 'subject_name' => 'Food Engineering II', 'semester' => 4],
    ['id' => 309, 'department' => 'foodscience', 'subject_name' => 'Food Toxicology', 'semester' => 4],
    ['id' => 310, 'department' => 'foodscience', 'subject_name' => 'Functional Foods', 'semester' => 4],
    ['id' => 311, 'department' => 'foodscience', 'subject_name' => 'Food Regulations', 'semester' => 4],
    ['id' => 312, 'department' => 'foodscience', 'subject_name' => 'Food Additives', 'semester' => 4],
    ['id' => 313, 'department' => 'foodscience', 'subject_name' => 'Advanced Food Chemistry', 'semester' => 5],
    ['id' => 314, 'department' => 'foodscience', 'subject_name' => 'Food Microbiology III', 'semester' => 5],
    ['id' => 315, 'department' => 'foodscience', 'subject_name' => 'Nutrition and Health', 'semester' => 5],
    ['id' => 316, 'department' => 'foodscience', 'subject_name' => 'Food Product Development', 'semester' => 5],
    ['id' => 317, 'department' => 'foodscience', 'subject_name' => 'Food Packaging II', 'semester' => 5],
    ['id' => 318, 'department' => 'foodscience', 'subject_name' => 'Food Fermentation', 'semester' => 5],
    ['id' => 319, 'department' => 'foodscience', 'subject_name' => 'Food Sensory Science', 'semester' => 6],
    ['id' => 320, 'department' => 'foodscience', 'subject_name' => 'Food Nanotechnology', 'semester' => 6],
    ['id' => 321, 'department' => 'foodscience', 'subject_name' => 'Food Processing III', 'semester' => 6],
    ['id' => 322, 'department' => 'foodscience', 'subject_name' => 'Food Safety Management', 'semester' => 6],
    ['id' => 323, 'department' => 'foodscience', 'subject_name' => 'Nutraceuticals', 'semester' => 6],
    ['id' => 324, 'department' => 'foodscience', 'subject_name' => 'Food Supply Chain', 'semester' => 6],
    ['id' => 325, 'department' => 'foodscience', 'subject_name' => 'Advanced Nutrition', 'semester' => 7],
    ['id' => 326, 'department' => 'foodscience', 'subject_name' => 'Food Biotechnology II', 'semester' => 7],
    ['id' => 327, 'department' => 'foodscience', 'subject_name' => 'Food Quality Assurance', 'semester' => 7],
    ['id' => 328, 'department' => 'foodscience', 'subject_name' => 'Research Methods in Food Science', 'semester' => 7],
    ['id' => 329, 'department' => 'foodscience', 'subject_name' => 'Food Waste Management', 'semester' => 7],
    ['id' => 330, 'department' => 'foodscience', 'subject_name' => 'Clinical Nutrition', 'semester' => 7],
    ['id' => 331, 'department' => 'foodscience', 'subject_name' => 'Project in Food Science', 'semester' => 8],
    ['id' => 332, 'department' => 'foodscience', 'subject_name' => 'Food Industry Trends', 'semester' => 8],
    ['id' => 333, 'department' => 'foodscience', 'subject_name' => 'Food Innovation', 'semester' => 8],
    ['id' => 334, 'department' => 'foodscience', 'subject_name' => 'Global Food Systems', 'semester' => 8],
    ['id' => 335, 'department' => 'foodscience', 'subject_name' => 'Seminar in Food Science', 'semester' => 8],
    ['id' => 336, 'department' => 'foodscience', 'subject_name' => 'Sustainable Food Production', 'semester' => 8],
];
$departments = array_unique(array_column($subjects, 'department'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disciplines - Re-enrollment System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/discipline.css ?v=<?php echo time(); ?>"> <!-- Adjusted path -->
    <link rel="icon" type="image/gif" href="../images/logo1.gif"> <!-- Adjusted path -->
</head>

<body>
    <!-- Fixed Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark contain">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="http://127.0.0.1:5500/#section1">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/university_db/php/discipline.php">Discipline</a> <!-- Updated path -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/university_db/php/institute.php">Institutes</a> <!-- Updated path -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/university_db/php/faq.php">Faqs</a> <!-- Updated path -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/university_db/php/login.php">Login</a> <!-- Updated path -->
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <img src="../images/favicon.jpg" alt="University Logo" class="logo"> <!-- Adjusted path -->
        <p class="campus-name">University of Agriculture Faisalabad<br>Sub Campus Depalpur Okara</p>
        <h2 class="text-center mb-4">Available Disciplines for Re-enrollment</h2>
        <div class="row">
            <?php foreach ($departments as $dept) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg" data-bs-toggle="modal" data-bs-target="#subjectModal" data-department="<?php echo htmlspecialchars($dept); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($dept))); ?></h5>
                            <button class="btn btn-primary">View Details</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal for Subject Details -->
    <div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subjectModalLabel">Subject Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 id="modalDepartment"></h4>
                    <table class="subject-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject Name</th>
                                <th>Semester</th>
                            </tr>
                        </thead>
                        <tbody id="subjectList">
                            <!-- Subjects will be populated here via JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.subjectData = <?php echo json_encode($subjects); ?>;
    </script>
    <script src="../js/discipline.js"></script> <!-- Adjusted path -->
</body>

</html>