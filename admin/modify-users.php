<?php
// Include the database connection
require '../php/connexion.php'; // Adjusted path to access connexion.php

// Fetch users from the database
$query = "SELECT id_user, username, email FROM USER";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id_user']}</td>
                <td>{$row['username']}</td>
                <td>{$row['email']}</td>
                <td>
                    <button class='deleteUser' data-id='{$row['id_user']}'>Delete</button>
                    <button class='editUser' data-id='{$row['id_user']}' data-username='{$row['username']}' data-email='{$row['email']}'>Modify</button>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No users found.";
}

$conn->close();
?>
