<?php
require '../php/connexion.php';

$id = $_GET['id_user'];

// Delete from user_club first to satisfy foreign key constraint
$sql = "DELETE FROM user_club WHERE id_user=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();

// Now delete from user table
$sql = "DELETE FROM user WHERE id_user=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    // Redirect to index.php after successful deletion
    header("Location: index.php");
    exit(); // Always exit after redirecting to prevent further script execution
} else {
    // Handle error, if needed
    echo "Error deleting record: " . $conn->error;
}
?>
