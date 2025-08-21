<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pre_style.css">
    <title>Generate Token</title>

    <script>
        function toggleGradeSelection() {
            const role = document.getElementById('role').value;
            const gradeSection = document.getElementById('gradeSection');
            if (role === 'student') {
                gradeSection.style.display = 'block';
            } else {
                gradeSection.style.display = 'none';
            }
        }

        function formatCountdown(expiryDate) {
            const now = new Date();
            const expiry = new Date(expiryDate);
            const timeDiff = expiry - now;

            if (timeDiff <= 0) {
                return "Expired";
            }

            const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

            return `${days}d ${hours}h ${minutes}m ${seconds}s`;
        }
    </script>
</head>
<body>
    
    <form action="" method="post">
        <label for="role">Select Role:</label>
        <select name="role" id="role" onchange="toggleGradeSelection()">
            <option value="" disabled selected>--select--</option>
            <option value="student">Student</option>
            <option value="parent">Parent</option>
            <option value="teacher">Teacher</option>
            <option value="admin">Admin</option>
        </select>

        <div id="gradeSection" style="display: none;">
            <label for="grade">Select Grade:</label>
            <select name="grade" id="grade">
                <option value="" disabled selected>--select--</option>
                <option value="8">Grade 8</option>
                <option value="9">Grade 9</option>
                <option value="10">Grade 10</option>
                <option value="11">Grade 11</option>
                <option value="12">Grade 12</option>
            </select>
        </div>

        <button type="submit">Generate Token</button>
    </form>

    <?php
  include('../Database/database_connection.php');
  if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login_Logout/logout.php');
    exit();
}
    $query = "
        SELECT pt.TokenID, pt.Token, pt.Role, pt.Grade, pt.Classroom, pt.UsedByUserID, pt.UsedOn, pt.GeneratedOn, pt.ExpiresOn, 
               u.Name, u.Lastname
        FROM pre_registration_tokens pt
        LEFT JOIN users u ON pt.UsedByUserID = u.UserID
    ";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>TokenID</th><th>Token</th><th>Role</th><th>Grade</th><th>Classroom</th><th>Used By</th><th>Generated On</th><th>Expires On</th><th>Status</th><th>Action</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            $usedBy = isset($row['Name']) && isset($row['Lastname']) ? $row['Name'] . " " . $row['Lastname'] : "Not Used";
            $currentTime = time();
            $expiresOnTime = strtotime($row['ExpiresOn']);
            $expiryDisplay = "";
            $status = "";
            $action = "";

            if ($row['UsedOn']) {
                $status = "Used on " . $row['UsedOn'];
                $expiryDisplay = "Active";
            } else {
                if ($expiresOnTime < $currentTime) {
                    $status = "Expired";
                    $expiryDisplay = "Expired";
                    $action = "<form action='reactivate_token.php' method='post' style='display:inline;'>
                        <input type='hidden' name='token_id' value='" . $row['TokenID'] . "'>
                        <button type='submit'>Reactivate</button>
                    </form>";
                } else {
                    $status = "<span id='countdown-" . $row['TokenID'] . "'></span>";
                    $expiryDisplay = $row['ExpiresOn'];
                    echo "<script>
                        document.getElementById('countdown-" . $row['TokenID'] . "').innerText = formatCountdown('" . $row['ExpiresOn'] . "');
                    </script>";
                }
            }

            echo "<tr>";
            echo "<td>" . $row['TokenID'] . "</td>";
            echo "<td>" . $row['Token'] . "</td>";
            echo "<td>" . ucfirst($row['Role']) . "</td>";
            echo "<td>" . ($row['Grade'] ? $row['Grade'] : "N/A") . "</td>";
            echo "<td>" . ($row['Classroom'] ? $row['Classroom'] : "N/A") . "</td>";
            echo "<td>" . $usedBy . "</td>";
            echo "<td>" . $row['GeneratedOn'] . "</td>";
            echo "<td>" . $expiryDisplay . "</td>";
            echo "<td>" . $status . "</td>";
            echo "<td>" . $action . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No tokens found.";
    }


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $grade = isset($_POST['grade']) ? $_POST['grade'] : NULL;

    $token = bin2hex(random_bytes(3));

    if ($role === 'student') {
        $classrooms = ['A', 'B', 'C', 'D'];
        $selectedClassroom = $classrooms[array_rand($classrooms)];
    } else {
        $selectedClassroom = NULL; 
    }

    $currentYear = date("Y");
    $expirationDate = "$currentYear-12-19 23:59:59";

    if (date("Y-m-d") > "$currentYear-12-19") {
        $expirationDate = ($currentYear + 1) . "-12-19 23:59:59";
    }

    $query = "INSERT INTO pre_registration_tokens (Token, Role, Grade, Classroom, GeneratedOn, ExpiresOn) VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt === false) {
        echo "<script>alert('Prepare failed: " . mysqli_error($conn) . "');</script>";
    } else {
        mysqli_stmt_bind_param($stmt, "sssss", $token, $role, $grade, $selectedClassroom, $expirationDate);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Token generated successfully: $token');</script>";
        } else {
            echo "<script>alert('Execute failed: " . mysqli_stmt_error($stmt) . "');</script>";
        }
        mysqli_stmt_close($stmt);
    }

}
    mysqli_close($conn);
    ?>

</body>
</html>
