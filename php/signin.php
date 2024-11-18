<?php
session_start();
include 'connexion.php';

header('Content-Type: application/json'); // Ensure the response is JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the statement to retrieve user information including id_user and usertype
    $stmt = $conn->prepare("SELECT id_user, password, usertype FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo json_encode(array("error" => "Invalid email or password."));
        exit;
    }

    // Bind the result to retrieve id_user, hashed password, and usertype
    $stmt->bind_result($id_user, $hashed_password, $usertype);
    $stmt->fetch();

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Store session information
        $_SESSION['id_user'] = $id_user;
        $_SESSION['usertype'] = $usertype;  // Store the usertype in the session

        // Send a success response along with id_user and usertype
        echo json_encode(array("success" => true, "id_user" => $id_user, "usertype" => $usertype));
    } else {
        echo json_encode(array("error" => "Invalid email or password."));
    }

    $stmt->close();
} else {
    echo json_encode(array("error" => "Invalid request method."));
}
$conn->close();
?>
