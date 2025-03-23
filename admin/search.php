<?php
    include 'db_connection.php'; // Your database connection file

    $search = $_GET['query']; // Get search query

    $sql = "SELECT * FROM users WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$search%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

echo json_encode($users);
?>
