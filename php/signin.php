<?php
include 'connexion.php'; 
$_SESSION['username'] = $username;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Prepare the statement to retrieve user information including id_user
    $stmt = $conn->prepare("SELECT id_user, password FROM user WHERE email = ?"); // Use lowercase 'user' for table name
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo json_encode(array("error" => "Invalid email or password."));
        exit;
    }

    // Bind the result to retrieve id_user and hashed password
    $stmt->bind_result($id_user, $hashed_password);
    $stmt->fetch();

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Send success response along with id_user
        echo json_encode(array("success" => true, "id_user " => $id_user));
    } else {
        echo json_encode(array("error" => "Invalid email or password."));
    }
    $_SESSION ['id_user'] = $id_user;
    $_SESSION['username'] = $username;
    $stmt->close();
}
$conn->close();
?>
