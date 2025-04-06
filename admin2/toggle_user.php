<?php
    include 'db_connection.php';

    $user_id = $_POST['user_id'];
    $ability = $_POST['ability']; // 'enabled' or 'disabled'

    $sql = "UPDATE users SET ability = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $ability, $user_id);
    $stmt->execute();

    echo json_encode(["success" => true]);
?>
