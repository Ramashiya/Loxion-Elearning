<?php

$file_path = realpath('../Admin/uploads/' . htmlspecialchars($assignment['FilePath']));
if ($file_path && file_exists($file_path)) {
    echo "<p><a href='$file_path' class='download-button' download>Download Assignment</a></p>";
} else {
    echo "<p>File not found or no permission to access.</p>";
}




?>