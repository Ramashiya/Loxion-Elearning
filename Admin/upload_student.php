<?php

include('../Database/database_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    $pdftotextPath = "C:\\Users\\ramas\\Music\\poppler-24.08.0\\Library\\bin\\pdftotext.exe";
    $uploadDir = './uploaded_files/';
    $dest_path = $uploadDir . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $dest_path)) {
        
        $outputFile = "C:\\xampp\\htdocs\\school\\output.txt"; 
        $command = "\"$pdftotextPath\" \"$dest_path\" \"$outputFile\" 2>&1";
        $output = shell_exec($command); 
        
        if ($output === null) {
            $text = file_get_contents($outputFile);
            
            $lines = explode("\n", $text);
            $names = [];
            $surnames = [];

            $nameSection = true;
            foreach ($lines as $line) {
                if (trim($line) === "Surname") {
                    $nameSection = false; 
                    continue;
                }
                if ($nameSection && preg_match('/^\d+\.\s+([a-zA-Z]+)/', $line, $matches)) {
                    $names[] = trim($matches[1]); // Capture first name
                } elseif (!$nameSection && preg_match('/^\d+\.\s+([a-zA-Z]+)/', $line, $matches)) {
                    $surnames[] = trim($matches[1]); // Capture surname
                }
            }

            
            if (count($names) === count($surnames)) {
                
                for ($i = 0; $i < count($names); $i++) {
                    $firstName = $names[$i];
                    $lastName = $surnames[$i];

                    $stmt = $conn->prepare("INSERT INTO students (Name, Surname) VALUES (?, ?)");
                    $stmt->bind_param("ss", $firstName, $lastName);

                    if ($stmt->execute()) {
                        echo "Student details uploaded successfully: $firstName $lastName;<br>";
                    } else {
                        echo "Error storing student details: " . $stmt->error . "<br>";
                    }
                }
            } else {
                echo "Mismatch in the number of first names and surnames found.";
            }
        } else {
            echo "Error executing pdftotext: $output";
        }
    } else {
        echo "Error moving the uploaded file.";
    }
}
?>
