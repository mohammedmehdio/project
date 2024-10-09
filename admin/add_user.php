<?php require "../php/connexion.php"; ?>

<?php
$msg = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $birthdate = $_POST['birth_date'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password

    // Prepare SQL statement to insert new user
    $sql = "INSERT INTO user (first_name, last_name, email, username, phone, birth_date, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssss', $first_name, $last_name, $email, $username, $phone, $birthdate, $password);

    // Execute the query and check for success
    if ($stmt->execute()) {
        $msg = 'New user added successfully.';
    } else {
        $msg = 'Error: ' . $conn->error;
    }
}
?>

<?php require "header.php"; ?>

<div class="container">
    <div class="card mt-5">
        <div class="card-header">
            <h2>Add New User</h2>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="birthdate">Birthdate:</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-success">Add User</button>
            </form>

            <!-- Success or error message -->
            <?php if ($msg): ?>
                <div class="alert alert-info mt-4"><?= $msg ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>
