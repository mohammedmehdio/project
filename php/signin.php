
<?php
include 'connexion.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT password FROM USER WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        die("No user found with that email address.");
    }

    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    if (password_verify($password, $hashed_password)) {
        echo "Login successful!";
      
    } else {
        echo "Invalid password.";
    }

    $stmt->close();
}
$conn->close();
?>
