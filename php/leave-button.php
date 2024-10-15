<?php
// Include the database connection
require 'connexion.php';

// Get the input data from the request
$id_user = $_POST['id_user'] ?? null;
$club_id = $_POST['club_id'] ?? null;

if (!$id_user || !$club_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    // Step 2: Delete the record from user_club
    $stmt = $conn->prepare("DELETE FROM user_club WHERE id_user = ? AND id_club = ?");
    $stmt->bind_param("ii", $id_user, $club_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Successfully left the club']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to leave the club']);
    }
} catch (mysqli_sql_exception $e) {
    error_log('Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
