<?php
// profile.php


include 'connexion.php';

// Get the logged-in user's ID from the session
$id_user = $_SESSION['id_user'];

// Fetch the user's information from the database
$stmt = $conn->prepare("SELECT first_name, last_name, email, username, phone, birth_date, profile_photo, password FROM USER WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $email, $username, $phone, $birth_date, $profile_photo, $hashed_password);
$stmt->fetch();
$stmt->close();

// Set default photo if none exists
if (empty($profile_photo)) {
    $profile_photo = "../img/profile.png"; // Change to your default photo filename
}

// Handle form submission for updating profile and password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update profile information
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $birth_date = $_POST['birth_date'];

    // Handle profile photo upload
    $profile_photo_name = $profile_photo; // Keep the current photo by default
    if (!empty($_FILES['profile_photo']['name'])) {
        $profile_photo = $_FILES['profile_photo'];
        $profile_photo_name = basename($profile_photo['name']);
        $upload_directory = "../uploads/";
        $target_file = $upload_directory . $profile_photo_name;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "jpeg", "png", "gif");

        // Validate file type
        if (in_array($image_file_type, $allowed_types)) {
            move_uploaded_file($profile_photo['tmp_name'], $target_file);

            // Update the profile photo in the database
            $stmt = $conn->prepare("UPDATE USER SET profile_photo = ? WHERE id_user = ?");
            $stmt->bind_param("si", $profile_photo_name, $id_user);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Update the user's information in the database
    $stmt = $conn->prepare("UPDATE USER SET first_name = ?, last_name = ?, email = ?, username = ?, phone = ?, birth_date = ? WHERE id_user = ?");
    $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $username, $phone, $birth_date, $id_user);
    $stmt->execute();
    $stmt->close();

    header("Location: profile.php");
exit;
    // Handle password change
    if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Check if the current password is correct
        if (password_verify($current_password, $hashed_password)) {
            if ($new_password === $confirm_password) {
                // Hash the new password
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $stmt = $conn->prepare("UPDATE USER SET password = ? WHERE id_user = ?");
                $stmt->bind_param("si", $new_hashed_password, $id_user);
                if ($stmt->execute()) {
                    // Password updated successfully
                } else {
                    echo "<p style='color: red; '>Error updating password: " . $stmt->error . "</p>";
                }
                $stmt->close();
            } else {
                echo "<p style='color: red;'>New password and confirmation do not match.</p>";
            }
        } else {
            echo "<p style='color: red; position:absolute; top:0;'>Current password is incorrect.</p>";
        }
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>User Profile</title>
    <style>
        /* General styles */
       

        body {
            background: url(../img/blue.jpg) no-repeat center center fixed;
            
        }

        /* Profile container */
        .profile-container {
            width: 100%;
            max-width: 700px;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 20px;
        }

        .profile-photo img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ddd;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .profile-photo img:hover {
            transform: scale(1.05);
        }

        /* Hide the file input */
        .profile-photo input[type="file"] {
            display: none;
        }

        .form label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            text-align: left;
            color: #555;
        }

        .form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }

        .form input:focus {
            border-color: #4CAF50;
            outline: none;
        }

       

        /* Password Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            color: #aaa;
            cursor: pointer;
        }

 
 
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500&display=swap");
:root {
  --primary: #eeeeee;
  --secondary: #227c70;
  --green: #82cd47;
  --secondary-light: rgb(34, 124, 112, 0.2);
  --secondary-light-2: rgb(127, 183, 126, 0.1);
  --white: #fff;
  --black: #393e46;

  --shadow: 0px 2px 8px 0px var(--secondary-light);
}

* {
  margin: 0;
  padding: 0;
  list-style-type: none;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  height: 100vh;
  width: 100%;
  background-color: var(--primary);
}

.navbar {
  display: flex;
  align-items: center;
  height: 70px;
  background-color: var(--white);
  padding: 0 8%;
  box-shadow: var(--shadow);
}

.navbar-logo {
  cursor: pointer;
}

.navbar-list {
  width: 100%;
  text-align: right;
  padding-right: 2rem;
}

.navbar-list li {
  display: inline-block;
  margin: 0 1rem;
}

.navbar-list li a {
  font-size: 1rem;
  font-weight: 500;
  color: var(--black);
  text-decoration: none;
}

.navbar {
  background-color: rgba(27, 103, 218, 0.356);
}
.navbar-brand, .nav-link {
  padding: 0;
  color: #fff !important;
  margin-left: 0px;
}
.hero-section {
  background: linear-gradient(to right, #6a11cb, #2575fc);
  color: #fff;
  padding: 60px 0;
  text-align: center;
  background: transparent ;
}


body {
  font-family: 'Roboto', sans-serif;
}
.navbar {
  background-color: rgba(27, 103, 218, 0.356);
}
.navbar-brand, .nav-link {
  padding: 0;
  color: #fff !important;
  margin-left: 0px;
}
.hero-section {
  background: linear-gradient(to right, #6a11cb, #2575fc);
  color: #fff;
  padding: 60px 0;
  text-align: center;
  background: transparent ;
}

ul {
  padding-left: 0;
}
.navbar {
  display: flex;
  align-items: center;
  justify-content: space-between; /* Add this */
  background-color: rgba(27, 103, 218, 0.356);
}

/* button */


.button {
  width: 150px;
  padding: 0;
  border: none;
  transform: rotate(2deg);
  transform-origin: center;
  font-family: "Gochi Hand", cursive;
  text-decoration: none;
  font-size: 15px;
  cursor: pointer;
  padding-bottom: 3px;
  border-radius: 5px;
  box-shadow: 0 2px 0 #494a4b;
  transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  background-color: #5cdb95;
}

.button span {
  background: #f1f5f8;
  display: block;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  border: 2px solid #494a4b;
}

.button:active {
  transform: translateY(5px);
  padding-bottom: 0px;
  outline: 0;
}

button {
  font-family: Arial, Helvetica, sans-serif;
  font-weight: bold;
  color: white;
  background-color: #171717;
  padding: 1em 2em;
  border: none;
  border-radius: .6rem;
  position: relative;
  cursor: pointer;
  overflow: hidden;
  width: 100%;
  margin: auto;
}

button span:not(:nth-child(6)) {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  height: 70px;
  width: 70px;
  background-color: #0c66ed;
  border-radius: 50%;
  transition: .6s ease;
}

button span:nth-child(6) {
  position: relative;
}

button span:nth-child(1) {
  transform: translate(-3.3em, -6em);
}

button span:nth-child(2) {
  transform: translate(-7em, 2.3em);
}

button span:nth-child(3) {
  transform: translate(-.2em, 1.8em);
}

button span:nth-child(4) {
  transform: translate(3.5em, 2.4em);
}

button span:nth-child(5) {
  transform: translate(3.5em, -6.8em);
}

button:hover span:not(:nth-child(6)) {
  transform: translate(-50%, -50%) scale(14);
  transition: 1.5s ease;
}
  

@keyframes pulse {
  from {
    transform: scale(0.9);
    opacity: 1;
  }

  to {
    transform: scale(1.8);
    opacity: 0;
  }
}


/* Add a simple fade-in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.navbar-brand:hover {
  color: #dedee4dc !important;
}
        
    </style>
</head>
<body>
    <!-- Navbar with Dashboard and password change modal trigger -->
    <nav class="navbar navbar-expand-lg" style="margin-bottom: 20px;">
    <div class="container">
        <a class="navbar-brand fw-bold" style="position: absolute; left:2rem; " href="#">ClubHub</a>
        <div class="profile-dropdown" style="position: absolute; right:0;">
        <a class="navbar-brand"  href="club.php">Dashboard</a>
        <a class="navbar-brand" href="change_password.php">Password</a>
    </div>
</nav>

 

    <div class="profile-container" style="margin: auto;">
        <h2 id="welcomeMessage" class="fw-bold"></h2>
        <script>
            var username = <?php echo json_encode($username); ?>;
            document.getElementById('welcomeMessage').textContent = username ;
        </script>

        <form class="form" action="profile.php" method="post" enctype="multipart/form-data">
            <div class="profile-photo">
                <!-- Clicking on the image triggers file input click -->
                <img src="../uploads/<?php echo htmlspecialchars($profile_photo); ?>" alt="Profile Photo" id="profile-photo-preview" onclick="document.getElementById('profile-photo-input').click();">
                <input type="file" name="profile_photo" accept="image/*" id="profile-photo-input" onchange="previewPhoto()">
            </div>
            <label>Firstname</label>
            <input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($first_name); ?>" required>

            <label>Lastname</label>
            <input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($last_name); ?>" required>

            <label>Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label>Username</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>

            <label>Phone Number</label>
            <input type="phone" name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>" required>

            <label>Birth Date</label>
            <input type="date" name="birth_date" id="birth_date" value="<?php echo htmlspecialchars($birth_date); ?>" required>

            <button style="border-radius : 10px; height:3.2rem">
          <span class="circle1"></span>
          <span class="circle2"></span>
          <span class="circle3"></span>
          <span class="circle4"></span>
          <span class="circle5"></span>
          <span class="text" type="submit">Save Changes</span>
      </button>
        </form>
    </div>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h3>Change Password</h3>
            <form class="form" action="profile.php" method="post">
                <label>Current Password</label>
                <input type="password" name="current_password" required>

                <label>New Password</label>
                <input type="password" name="new_password" required>

                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" required>

                <button type="submit">Change Password</button>
            </form>
        </div>
    </div>

    <script>
        // Profile photo preview
        function previewPhoto() {
            const file = document.getElementById('profile-photo-input').files[0];
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('profile-photo-preview').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }

        // Modal functions
        function openModal() {
            document.getElementById('passwordModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('passwordModal').style.display = 'none';
        }
     

document.addEventListener('DOMContentLoaded', function() {
    let firstName = document.getElementById('firstName');
    let lastName = document.getElementById('lastName');
    let email = document.getElementById('email');
    let username = document.getElementById('username');
    let phone = document.getElementById('phone');
    let birth_date = document.getElementById('birth_date');
        
    firstName.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === 'ArrowDown') {
            lastName.focus();
        }
    });
    lastName.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === 'ArrowDown') {
            email.focus();
        } else if (event.key === 'ArrowUp') {
            firstName.focus();
        }
    });
    email.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === 'ArrowDown') {
            username.focus();
        } else if (event.key === 'ArrowUp') {
            lastName.focus();
        }
    });
    username.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === 'ArrowDown') {
            phone.focus();
        } else if (event.key === 'ArrowUp') {
            email.focus();
        }
    });
    phone.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === 'ArrowDown') {
            birth_date.focus();
        } else if (event.key === 'ArrowUp') {
            username.focus();
        }
    });
    birth_date.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            validateForm();
        } else if (event.key === 'ArrowUp') {
            phone.focus();
        }
    });
});
    </script>
</body>
</html>

