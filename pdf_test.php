<?php
session_start();
ob_start(); 

include('Database/database_connection.php'); 

$studentID = 17;

// Fetch the student's full name and grade
$student_query = $conn->prepare("SELECT FirstName, Lastname, Grade FROM students WHERE StudentID = ?");
$student_query->bind_param("i", $studentID);
$student_query->execute();
$student_result = $student_query->get_result();
$student_info = $student_result->fetch_assoc();

// Get current date and time
$date_generated = date("Y-m-d H:i:s"); // Format: YYYY-MM-DD HH:MM:SS

if (isset($_POST['download_pdf'])) {
    // Include the FPDF library
    require('Student/fpdf186/fpdf.php');

    $stmt = $conn->prepare("SELECT Subject, Mark, Term FROM results WHERE StudentID = ?");
    $stmt->bind_param("i", $studentID);
    $stmt->execute();
    $result = $stmt->get_result();

    $pdf = new FPDF();
    $pdf->AddPage();
    
    // Set the header for the school name
    $pdf->SetFont('Helvetica', 'B', 20); 
    $pdf->Cell(0, 10, 'Polokwane High School', 0, 1, 'C'); 
    $pdf->Ln(10); 

    // Display student's full name, grade, and date of PDF generation
    $pdf->SetFont('Helvetica', '', 12); 
    $pdf->Cell(0, 10, 'Student Name: ' . htmlspecialchars($student_info['FirstName']) . ' ' . htmlspecialchars($student_info['Lastname']), 0, 1);
    $pdf->Cell(0, 10, 'Grade: ' . htmlspecialchars($student_info['Grade']), 0, 1);
    $pdf->Cell(0, 10, 'Date Generated: ' . $date_generated, 0, 1);
    $pdf->Ln(10); 

    // Title for the grades section
    $pdf->SetFont('Helvetica', 'B', 16); 
    $pdf->Cell(0, 10, 'Your Grades:', 0, 1, 'C'); 
    $pdf->Ln(10);

    // Table header
    $pdf->SetFont('Helvetica', 'B', 12);
    $pdf->Cell(60, 10, 'Subject', 1);
    $pdf->Cell(40, 10, 'Term', 1);
    $pdf->Cell(40, 10, 'Mark', 1);
    $pdf->Ln();

    // Table content
    $pdf->SetFont('Helvetica', '', 12);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(60, 10, htmlspecialchars($row['Subject']), 1);
            $pdf->Cell(40, 10, htmlspecialchars($row['Term']), 1);
            $pdf->Cell(40, 10, htmlspecialchars($row['Mark']), 1);
            $pdf->Ln();
        }
    } else {
        $pdf->Cell(0, 10, 'No results found.', 1);
    }

    ob_end_clean(); // Clean output buffer before sending PDF

    $pdf->Output('D', 'grades.pdf'); // 'D' option for download

    $stmt->close();
    $conn->close();
    exit();
}

$stmt = $conn->prepare("SELECT Subject, Mark, Term FROM results WHERE StudentID = ?");
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2 style="text-align: center;">Download Your Results:</h2>

    <form method="post" style="text-align: center;">
        <input type="hidden" name="download_pdf" value="1">
        <button type="submit">Download PDF</button>
    </form>
</body>
</html>

<?php
$stmt->close();
$conn->close(); 
?>
