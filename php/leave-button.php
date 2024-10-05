<?php
// Include the database connection
require 'connexion.php';

// Get the input data from the request
$data = json_decode(file_get_contents('php://input'), true);
$id_user = $data['id_user'];
$club_name = $data['club_name'];

if (!$id_user || !$club_name) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    // Step 1: Get the id_club from the club name
    $stmt = $conn->prepare("SELECT id_club FROM club WHERE club_name = ?");
    $stmt->bind_param("s", $club_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $club = $result->fetch_assoc();

    if ($club) {
        $id_club = $club['id_club'];

        // Step 2: Delete the record from user_club
        $stmt = $conn->prepare("DELETE FROM user_club WHERE id_user = ? AND id_club = ?");
        $stmt->bind_param("ii", $id_user, $id_club);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Successfully left the club']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to leave the club']);
        }
    } else {
        // Club not found
        echo json_encode(['success' => false, 'message' => 'Club not found']);
    }

} catch (mysqli_sql_exception $e) {
    error_log('Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
