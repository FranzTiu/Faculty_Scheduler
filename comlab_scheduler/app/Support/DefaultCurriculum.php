<?php

namespace App\Support;

class DefaultCurriculum
{
    /**
     * Rooms list used when "Use Default Curriculum" is enabled.
     *
     * @return array<int, array{room_name:string, campus:string}>
     */
    public static function rooms(): array
    {
        // Deduped from the provided list (COMLAB3 was duplicated).
        return [
            ['room_name' => 'CHS', 'campus' => 'Main Campus'],
            ['room_name' => 'CISCO (CON 102)', 'campus' => 'Main Campus'],
            ['room_name' => 'COMLAB1', 'campus' => 'Main Campus'],
            ['room_name' => 'COMLAB2', 'campus' => 'Main Campus'],
            ['room_name' => 'COMLAB3', 'campus' => 'Main Campus'],
            ['room_name' => 'COMLAB4', 'campus' => 'Main Campus'],
            ['room_name' => 'COMLAB5 (CON 103)', 'campus' => 'Young Field'],
            ['room_name' => 'COMLAB6 (CON 104)', 'campus' => 'Young Field'],
            ['room_name' => 'COMLAB7 (CON 105)', 'campus' => 'Young Field'],
        ];
    }

    /**
     * Default subjects by term (1st / 2nd).
     *
     * @return array<int, array{subject_code:string, subject_name:string, year_level:int}>
     */
    public static function subjectsForTerm(string $term): array
    {
        $term = trim($term);

        if ($term === '2nd') {
            return self::secondSemesterSubjects();
        }

        if ($term === 'Summer') {
            return [];
        }

        return self::firstSemesterSubjects();
    }

    private static function firstSemesterSubjects(): array
    {
        return [
            // First Year - 1st Sem
            ['subject_code' => 'IT-101', 'subject_name' => 'Information Technology Fundamentals with Software Application', 'year_level' => 1],
            ['subject_code' => 'IT-101L', 'subject_name' => 'Information Technology Fundamentals with Software Application (Laboratory)', 'year_level' => 1],
            ['subject_code' => 'IT-102', 'subject_name' => 'Accounting Principle', 'year_level' => 1],
            ['subject_code' => 'IT-103', 'subject_name' => 'Computer Programming I - Java', 'year_level' => 1],
            ['subject_code' => 'IT-103L', 'subject_name' => 'Computer Programming I - Java (Laboratory)', 'year_level' => 1],
            ['subject_code' => 'IT-106', 'subject_name' => 'Introduction to Computing', 'year_level' => 1],

            // Second Year - 1st Sem
            ['subject_code' => 'IT-109', 'subject_name' => 'IT Elective I - Platform Technologies', 'year_level' => 2],
            ['subject_code' => 'IT-109L', 'subject_name' => 'IT Elective I - Platform Technologies (Laboratory)', 'year_level' => 2],
            ['subject_code' => 'IT-110', 'subject_name' => 'Data Structure and Algorithms', 'year_level' => 2],
            ['subject_code' => 'IT-110L', 'subject_name' => 'Data Structure and Algorithms (Laboratory)', 'year_level' => 2],
            ['subject_code' => 'IT-111', 'subject_name' => 'Fundamentals of Database System', 'year_level' => 2],
            ['subject_code' => 'IT-111L', 'subject_name' => 'Fundamentals of Database System (Laboratory)', 'year_level' => 2],
            ['subject_code' => 'IT-113', 'subject_name' => 'CISCO I - Networking Fundamentals', 'year_level' => 2],
            ['subject_code' => 'IT-113L', 'subject_name' => 'CISCO I - Networking Fundamentals (Laboratory)', 'year_level' => 2],
            ['subject_code' => 'IT-114', 'subject_name' => 'IT ELECTIVE II - Objective Oriented Programming', 'year_level' => 2],
            ['subject_code' => 'IT-114L', 'subject_name' => 'IT ELECTIVE II - Objective Oriented Programming (Laboratory)', 'year_level' => 2],

            // Third Year - 1st Sem
            ['subject_code' => 'IT-117', 'subject_name' => 'System Integration and Architecture I', 'year_level' => 3],
            ['subject_code' => 'IT-117L', 'subject_name' => 'System Integration and Architecture I (Laboratory)', 'year_level' => 3],
            ['subject_code' => 'IT-119', 'subject_name' => 'IT Elective IV - Web Systems and Technologies', 'year_level' => 3],
            ['subject_code' => 'IT-119L', 'subject_name' => 'IT Elective IV - Web Systems and Technologies (Laboratory)', 'year_level' => 3],
            ['subject_code' => 'IT-121', 'subject_name' => 'Information Management I', 'year_level' => 3],
            ['subject_code' => 'IT-121L', 'subject_name' => 'Information Management I (Laboratory)', 'year_level' => 3],
            ['subject_code' => 'IT-122', 'subject_name' => 'System Analysis and Design', 'year_level' => 3],
            ['subject_code' => 'IT-122L', 'subject_name' => 'System Analysis and Design (Laboratory)', 'year_level' => 3],
            ['subject_code' => 'IT-125', 'subject_name' => 'Information Assurance & Security I', 'year_level' => 3],
            ['subject_code' => 'IT-125L', 'subject_name' => 'Information Assurance & Security I (Laboratory)', 'year_level' => 3],

            // Fourth Year - 1st Sem
            ['subject_code' => 'IT-123', 'subject_name' => 'IT Elective V - System Integration and Architecture II', 'year_level' => 4],
            ['subject_code' => 'IT-123L', 'subject_name' => 'IT Elective V - System Integration and Architecture II (Laboratory)', 'year_level' => 4],
            ['subject_code' => 'IT-131', 'subject_name' => 'Seminars and Fieldtrip', 'year_level' => 4],
            ['subject_code' => 'IT-132', 'subject_name' => 'Information Assurance and Security II', 'year_level' => 4],
            ['subject_code' => 'IT-132L', 'subject_name' => 'Information Assurance and Security II (Laboratory)', 'year_level' => 4],
            ['subject_code' => 'IT-133', 'subject_name' => 'Capstone Project 2', 'year_level' => 4],
            ['subject_code' => 'IT-133L', 'subject_name' => 'Capstone Project 2 (Laboratory)', 'year_level' => 4],
        ];
    }

    private static function secondSemesterSubjects(): array
    {
        return [
            // First Year - 2nd Sem
            ['subject_code' => 'IT-104', 'subject_name' => 'Discrete Mathematics', 'year_level' => 1],
            ['subject_code' => 'IT-107', 'subject_name' => 'Multimedia System', 'year_level' => 1],
            ['subject_code' => 'IT-107L', 'subject_name' => 'Multimedia System (Laboratory)', 'year_level' => 1],
            ['subject_code' => 'IT-108', 'subject_name' => 'Programming II - Python', 'year_level' => 1],
            ['subject_code' => 'IT-108L', 'subject_name' => 'Programming II - Python (Laboratory)', 'year_level' => 1],
            ['subject_code' => 'IT-130', 'subject_name' => 'Computer Hardware Repair and Maintenance', 'year_level' => 1],
            ['subject_code' => 'IT-130L', 'subject_name' => 'Computer Hardware Repair and Maintenance (Laboratory)', 'year_level' => 1],

            // Second Year - 2nd Sem
            ['subject_code' => 'IT-112', 'subject_name' => 'Integrative Programming and Technologies', 'year_level' => 2],
            ['subject_code' => 'IT-112L', 'subject_name' => 'Integrative Programming and Technologies (Laboratory)', 'year_level' => 2],
            ['subject_code' => 'IT-115', 'subject_name' => 'Introduction to Human Computer Interaction', 'year_level' => 2],
            ['subject_code' => 'IT-115L', 'subject_name' => 'Introduction to Human Computer Interaction (Laboratory)', 'year_level' => 2],
            ['subject_code' => 'IT-116', 'subject_name' => 'CISCO II - Routing and Switching Essential', 'year_level' => 2],
            ['subject_code' => 'IT-116L', 'subject_name' => 'CISCO II - Routing and Switching Essential (Laboratory)', 'year_level' => 2],
            ['subject_code' => 'IT-120', 'subject_name' => 'Geographic Information Systems', 'year_level' => 2],
            ['subject_code' => 'IT-120L', 'subject_name' => 'Geographic Information Systems (Laboratory)', 'year_level' => 2],

            // Third Year - 2nd Sem
            ['subject_code' => 'IT-105', 'subject_name' => 'Mobile Development', 'year_level' => 3],
            ['subject_code' => 'IT-105L', 'subject_name' => 'Mobile Development (Laboratory)', 'year_level' => 3],
            ['subject_code' => 'IT-124', 'subject_name' => 'Quantitative Methods with Simulations and Modeling', 'year_level' => 3],
            ['subject_code' => 'IT-126', 'subject_name' => 'Social and Professional Issues', 'year_level' => 3],
            ['subject_code' => 'IT-127', 'subject_name' => 'Application Development and Emerging Technologies', 'year_level' => 3],
            ['subject_code' => 'IT-127L', 'subject_name' => 'Application Development and Emerging Technologies (Laboratory)', 'year_level' => 3],
            ['subject_code' => 'IT-128', 'subject_name' => 'Capstone Project I', 'year_level' => 3],
            ['subject_code' => 'IT-128L', 'subject_name' => 'Capstone Project I (Laboratory)', 'year_level' => 3],
            ['subject_code' => 'IT-129', 'subject_name' => 'System Administration and Maintenance', 'year_level' => 3],
            ['subject_code' => 'IT-129L', 'subject_name' => 'System Administration and Maintenance (Laboratory)', 'year_level' => 3],

            // Fourth Year - 2nd Sem
            ['subject_code' => 'IT-134', 'subject_name' => 'Practicum – 486 hours', 'year_level' => 4],
        ];
    }
}

