<?php
require '../php/connexion.php';

$id_user = $_GET['id_user'];
$id_club = $_GET['id_club'];

// Prepare the SQL statement to delete user from the club
$sql = "DELETE FROM user_club WHERE id_user=? AND id_club=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id_user, $id_club);

// Execute the statement
if ($stmt->execute()) {
    // Redirect back to the clubs page
    header("Location: clubs.php");
    exit();
} else {
    echo "Error removing user from the club: " . $conn->error;
}
?>
