<?php
include('../Database/database_connection.php');

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login_Logout/logout.php');
    exit();
}

if (isset($_POST['chatWith'])) {
    $_SESSION['selectedChatUser'] = $_POST['chatWith'];
}

if (isset($_POST['receiverID']) && isset($_POST['message'])) {
    $senderID = $_SESSION['UserID'];
    $receiverID = $_POST['receiverID'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chat_messages (SenderID, ReceiverID, Message, SentAt) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param('iis', $senderID, $receiverID, $message);
        $stmt->execute();
        $stmt->close();
    }
}

function formatDate($dateString) {
    $date = strtotime($dateString);
    $today = strtotime("today");
    $yesterday = strtotime("yesterday");

    if ($date === $today) {
        return "Today";
    } elseif ($date === $yesterday) {
        return "Yesterday";
    }
    return date('D d M Y', $date);
}

function fetchChatMessages($conn, $userID, $chatWithID) {
    $query = "
        SELECT * FROM chat_messages 
        WHERE (SenderID = ? AND ReceiverID = ?) 
        OR (SenderID = ? AND ReceiverID = ?)
        ORDER BY SentAt ASC
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiii', $userID, $chatWithID, $chatWithID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $lastDate = "";

    while ($row = $result->fetch_assoc()) {
        $sentAt = strtotime($row['SentAt']);
        $formattedDate = formatDate($row['SentAt']); 
        $time = date('H:i:s', $sentAt);

        if ($formattedDate !== $lastDate) {
            echo "<div class='message-date' style='text-align: center; font-weight: bold;'>$formattedDate</div>";
            $lastDate = $formattedDate;
        }

        if ($row['SenderID'] == $userID) {
            echo "<div class='sent-message'>
                    <div class='message-text'>{$row['Message']}</div>
                    <small class='message-time'>{$time}</small>
                  </div>";
        } else {
            echo "<div class='received-message'>
                    <div class='message-text'>{$row['Message']}</div>
                    <small class='message-time'>{$time}</small>
                  </div>";
        }
    }

    $stmt->close();
}

function fetchUserList($conn, $loggedInUserID) {
    $query = "
        SELECT p.UserID, CONCAT(p.FirstName, ' ', p.LastName) AS FullName, 
               GROUP_CONCAT(CONCAT(s.FirstName, ' ', s.LastName) SEPARATOR ', ') AS ChildrenNames
        FROM parents p
        LEFT JOIN parent_student ps ON p.UserID = ps.ParentID
        LEFT JOIN students s ON ps.StudentID = s.StudentID
        WHERE p.UserID != ?
        GROUP BY p.UserID

        UNION ALL
        
        SELECT t.UserID, CONCAT(t.FirstName, ' ', t.LastName) AS FullName, 
               NULL AS ChildrenNames
        FROM teachers t
        WHERE t.UserID != ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $loggedInUserID, $loggedInUserID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($user = $result->fetch_assoc()) {
        if ($user['ChildrenNames'] !== null) {
            echo "<option value='{$user['UserID']}'>{$user['FullName']} (Children: {$user['ChildrenNames']})</option>";
        } else {
            echo "<option value='{$user['UserID']}'>{$user['FullName']}</option>";
        }
    }

    echo "<option value='admin'>Admin</option>";
}

$selectedUserID = isset($_SESSION['selectedChatUser']) ? $_SESSION['selectedChatUser'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat System</title>
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .chat-container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .chat-messages {
            height: 300px;
            overflow-y: auto;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
        }
        .sent-message {
            text-align: right;
            background-color: #e1f5fe;
            padding: 5px;
            border-radius: 5px;
            margin: 5px 0;
        }
        .received-message {
            text-align: left;
            background-color: #ffecb3;
            padding: 5px;
            border-radius: 5px;
            margin: 5px 0;
        }
        .message-date {
            font-weight: bold;
            font-size: 0.9em;
            margin: 10px 0;
        }
        .message-time {
            font-size: 0.8em;
            color: gray;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: none; 
        }
        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            font-size: 16px;
            transition: border-color 0.3s;
            margin-top: 10px; 
        }
        select:focus {
            border-color: #28a745; 
            outline: none; 
        }
        .chat-messages::-webkit-scrollbar {
            width: 8px;
        }
        .chat-messages::-webkit-scrollbar-thumb {
            background-color: #ccc; 
            border-radius: 10px;
        }
        .chat-messages::-webkit-scrollbar-thumb:hover {
            background-color: #888; 
        }
    </style>
<body>
    <div class="chat-container">
        <h2>Private Chat</h2>
        <div class="chat-messages">
            <?php
            if ($selectedUserID) {
                fetchChatMessages($conn, $_SESSION['UserID'], $selectedUserID);
            }
            ?>
        </div>
        <form method="POST">
            <select name="chatWith" onchange="this.form.submit()">
                <option value="" disabled>Select a parent or teacher to chat with</option>
                <?php 
                fetchUserList($conn, $_SESSION['UserID']); 
                if ($selectedUserID) {
                    echo "<script>document.querySelector('select[name=\"chatWith\"]').value = '$selectedUserID';</script>";
                }
                ?>
            </select>
            <input type="hidden" name="receiverID" value="<?php echo $selectedUserID; ?>">
            <textarea name="message" rows="3" placeholder="Type your message..."></textarea>
            <button type="submit" name="sendMessage">Send</button>
        </form>
    </div>
</body>
</html>
