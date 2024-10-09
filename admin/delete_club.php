<?php
require '../php/connexion.php';

$id = $_GET['id_club'];

// Prepare the SQL statement to delete the club
$sql = "DELETE FROM club WHERE id_club=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);

// Execute the statement
if ($stmt->execute()) {
    // Redirect to clubs.php after successful deletion
    header("Location: clubs.php");
    exit();
} else {
    echo "Error deleting club: " . $conn->error;
}
?>
