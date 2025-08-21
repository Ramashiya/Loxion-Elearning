<?php
include('../Database/database_connection.php');

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login_Logout/logout.php');
    exit();
}
if (isset($_POST['update_status'])) {
    $user_id = $_POST['user_id'];
    $status = $_POST['status'] == 'activate' ? 1 : 0;
    
    $sql = "UPDATE users SET active = ? WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $status, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($status == 0) {
        if (isset($_SESSION['UserID']) && $_SESSION['UserID'] == $user_id) {
            session_unset();
            session_destroy();
            header('Location: login.php?error=account_deactivated');
            exit();
        }
    }
}

$sql = "SELECT UserID, Name, Role, active FROM users";
$result = $conn->query($sql);
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
    

       
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        tr:hover {
            background: #ddd;
        }

        /* Form Styles */
        form {
            display: inline;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Users</h1>
        <table>
            <thead>
                <tr>
                    <th>UserID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['UserID']); ?></td>
                        <td><?php echo htmlspecialchars($user['Name']); ?></td>
                        <td><?php echo htmlspecialchars($user['Role']); ?></td>
                        <td><?php echo $user['active'] ? 'Active' : 'Inactive'; ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['UserID']); ?>">
                                <input type="hidden" name="status" value="<?php echo $user['active'] ? 'deactivate' : 'activate'; ?>">
                                <button type="submit" name="update_status"><?php echo $user['active'] ? 'Deactivate' : 'Activate'; ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
