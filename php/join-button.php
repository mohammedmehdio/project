<?php
// Include the database connection
require 'connexion.php';

// Get the input data from the request
$data = $_POST;
var_dump($data);

$id_user = $_SESSION['id_user'] ?? null;
$id_club = $data['club_id'] ?? null;

if (!$id_club) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {


    if ($id_club) {
       
        // Step 2: Insert the id_user and id_club into user_club
        $stmt = $conn->prepare("INSERT INTO user_club (id_user, id_club) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_user, $id_club);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Successfully joined the club']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to join the club']);
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
