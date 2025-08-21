<?php
include("../Database/database_connection.php"); 

header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="database_backup_' . date("Y-m-d_H-i-s") . '.sql"');

$tables = array();
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}

$output = "";

foreach ($tables as $table) {
    $createTableResult = $conn->query("SHOW CREATE TABLE $table");
    $createTableRow = $createTableResult->fetch_row();
    $output .= "\n\n" . $createTableRow[1] . ";\n\n";

    $tableDataResult = $conn->query("SELECT * FROM $table");
    while ($row = $tableDataResult->fetch_assoc()) {
        $output .= "INSERT INTO $table VALUES(";
        foreach ($row as $fieldValue) {
            $output .= "'" . $conn->real_escape_string($fieldValue) . "', ";
        }
        $output = rtrim($output, ", ");
        $output .= ");\n";
    }
    $output .= "\n\n";
}

$conn->close();
echo $output;
?>
