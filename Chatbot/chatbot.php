<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

$questionsAndAnswers = [
    "What is the school schedule?" => "The school schedule is available on the school website.",
    "How do I check my grades?" => "You can check your grades by logging into the student portal.",
    "Where can I find study materials?" => "Study materials are available on the resource portal.",
    "What are the exam dates?" => "Exam dates are listed in the academic calendar.",
    "Who is my homeroom teacher?" => "Your homeroom teacher can be found in your student profile.",
    "How do I access my assignments?" => "Assignments are accessible via the assignments portal.",
    "What is the school policy on attendance?" => "Attendance policies are outlined in the student handbook.",
    "How do I join after-school programs?" => "After-school program information is available at the front desk.",
    "Where can I get counseling support?" => "Counseling support is offered in the wellness center.",
    "How do I get a library card?" => "Library cards are available upon registration at the library.",
    // Additional Q&A pairs for each role...
];

function findClosestMatch($input, $array) {
    $closest = null;
    $shortest = -1;

    foreach ($array as $question => $answer) {
        $lev = levenshtein($input, $question);
        if ($lev == 0) {
            return $answer;
        }
        if ($lev <= $shortest || $shortest < 0) {
            $closest = $answer;
            $shortest = $lev;
        }
    }
    return $closest;
}

$userMessage = $data['message'];
$response = $questionsAndAnswers[$userMessage] ?? findClosestMatch($userMessage, $questionsAndAnswers);

if (!$response) {
    $response = "I'm sorry, I don't understand that question. Please try again or contact support.";
}

echo json_encode(["response" => $response]);
?>




