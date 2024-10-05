<?php
// Include the database connection
require 'connexion.php'; // Adjusted path to access connexion.php

$data = json_decode(file_get_contents('php://input'), true);
$id_user = $data['id_user'];

if ($id_user) {
    // Prepare and execute deletion
    $stmt = $conn->prepare("DELETE FROM USER WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
}

$conn->close();
?>
