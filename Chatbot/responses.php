<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if ($data['action'] === "getTopQuestions") {
    $role = strtolower($data['role']);
    $topQuestions = [
        "learner" => [
            "What is the school schedule?", "How do I check my grades?", "Where can I find study materials?",
            "What are the exam dates?", "Who is my homeroom teacher?", "How do I access my assignments?",
            "What is the school policy on attendance?", "How do I join after-school programs?",
            "Where can I get counseling support?", "How do I get a library card?"
        ],
        "teacher" => [
            "How do I enter grades?", "How do I access teaching resources?", "What are the term dates?",
            "How do I communicate with parents?", "What is the school's grading policy?",
            "How do I request classroom materials?", "Who do I contact for tech support?",
            "How can I volunteer for extra activities?", "What is the protocol for student behavior?",
            "How do I update my schedule?"
        ],
        "parent" => [
            "How do I check my child's attendance?", "How do I contact a teacher?", "How can I access my child’s grades?",
            "What are the school's policies on behavior?", "How do I schedule a parent-teacher meeting?",
            "What support services are available for students?", "What is the school's holiday schedule?",
            "How do I get my child’s report card?", "What are the enrollment requirements?", "How do I update my contact information?"
        ],
        "admin" => [
            "How do I manage student enrollment?", "How do I reset a user password?", "What is the school's contact number?",
            "How can I update school announcements?", "How do I access teacher schedules?", 
            "How do I handle facility requests?", "What is the protocol for safety drills?",
            "How do I submit budget reports?", "Who do I contact for maintenance issues?", 
            "How can I arrange special events?"
        ]
    ];

    echo json_encode(["questions" => $topQuestions[$role] ?? []]);
}
?>








