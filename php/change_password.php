<?php
// change_password.php
include 'connexion.php';

// Check if the user is logged in
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit;
}

// Fetch current hashed password from database
$id_user = $_SESSION['id_user'];
$stmt = $conn->prepare("SELECT password FROM USER WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$stmt->bind_result($hashed_password);
$stmt->fetch();
$stmt->close();

// Handle password change submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (password_verify($current_password, $hashed_password)) {
        if ($new_password === $confirm_password) {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in the database
            $stmt = $conn->prepare("UPDATE USER SET password = ? WHERE id_user = ?");
            $stmt->bind_param("si", $new_hashed_password, $id_user);
            if ($stmt->execute()) {
                echo "<p style='color: green; position:absolute; bottom:10px; margin:10px;'>Password updated successfully!</p>";
            } else {
                echo "<p style='color: red; position:absolute; bottom:10px; margin:10px;'>Error updating password.</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color: red; position:absolute; bottom:10px; margin:10px;'>New password and confirmation do not match.</p>";
        }
    } else {
        echo "<p style='color: red; position:absolute; bottom:10px; margin:10px;'>Current password is incorrect.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/password.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../js/password.js"></script>
    <title>Change Password</title>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold" style="position: absolute; left:2rem; " href="#">ClubHub</a>
        <div class="profile-dropdown" style="position: absolute; right:0;">
        <a class="navbar-brand"  href="club.php">Dashboard</a>
        <a class="navbar-brand" href="profile.php">Profile</a>
    </div>
</nav>
<br>
<br>
    <form class="form" action="change_password.php" method="post">
    <p class="title">Change Password</p>
        <label>
        <input class="input" type="password" name="current_password" id="current_password" style="width: 100%;" required>
        <span>Current Password</span>
        <i class="toggle-password" onclick="togglePasswordVisibility('current_password')"><i class="fas fa-eye" style="color: rgb(44, 144, 167); position:relative; right:0.6rem;"></i></i>
        </label>
        <label>
        <input class="input" type="password" name="new_password" id="new_password" style="width: 100%;" required>
        <span>New Password</span>
        </label>
        <label>
        <input class="input" type="password" name="confirm_password" id="confirm_password" style="width: 100%;" required>
        <span>Confirm New Password</span>
        </label>
       <a href="profile.php"> <button style="border-radius : 10px; height:3.2rem">
          <span class="circle1"></span>
          <span class="circle2"></span>
          <span class="circle3"></span>
          <span class="circle4"></span>
          <span class="circle5"></span>
          <span class="text" type="submit">Change Password</span>
      </button></a>
        <p class="signin">Back to profile ? <a href="profile.php">Profile</a> </p>
    </form>
    
</body>
</html>
