<?php
include('../Database/database_connection.php');

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login_Logout/logout.php');
    exit();
}

if (isset($_POST['receiverID'])) {
    $userID = $_SESSION['UserID'];
    $chatWithID = $_POST['receiverID'];

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

        while ($row = $result->fetch_assoc()) {
            if ($row['SenderID'] == $userID) {
                echo "<div class='sent-message'>{$row['Message']}<small>{$row['SentAt']}</small></div>";
            } else {
                echo "<div class='received-message'>{$row['Message']}<small>{$row['SentAt']}</small></div>";
            }
        }

        $stmt->close();
    }

    fetchChatMessages($conn, $userID, $chatWithID);
}
?>
