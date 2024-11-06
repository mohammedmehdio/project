<?php
require "../php/connexion.php";

$id = $_GET['id_user']; // Get user ID from the URL
$msg = ''; // Initialize message variable

// Prepare the SQL statement
$sql = "SELECT * FROM user WHERE id_user=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id); // 'i' indicates that $id is an integer
$stmt->execute();
$result = $stmt->get_result();
$person = $result->fetch_assoc(); // Fetch associative array

if (isset($_POST['username'], $_POST['email'], $_POST['first_name'], $_POST['last_name'], $_POST['phone'], $_POST['birth_date'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $birthdate = $_POST['birth_date'];

    // SQL statement for update
    $sql = "UPDATE user SET username=?, email=?, first_name=?, last_name=?, phone=?, birth_date=? WHERE id_user=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssi', $username, $email, $first_name, $last_name, $phone, $birthdate, $id); // 'ssssssi' indicates 5 strings and 1 integer

    // Execute and verify
    if ($stmt->execute()) {
        $msg = 'Member edited successfully.';
        header("Location: index.php");
        exit(); // Ensure to exit after redirecting
    }
}

?>

<?php include "header.php"; ?>

<div class="container p-5 my-5">
    <div class="card">
        <div class="card-header">
            <h2>Edit Info</h2>
        </div>
        <div class="card-body">
            <form method="post" id="mfform">
            <div class="container mb-4">
    <label for="first_name">First Name:</label>
    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($person['first_name']); ?>" required>
</div>

<div class="container mb-4">
    <label for="last_name">Last Name:</label>
    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($person['last_name']); ?>" required>
</div>

<div class="container mb-4">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($person['email']); ?>" required>
                </div>

                <div class="container mb-4">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($person['username']); ?>" required>
                </div>

<div class="container mb-4">
    <label for="phone">Phone:</label>
    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($person['phone']); ?>" required>
</div>

<div class="container mb-4">
    <label for="birthdate">Birthdate:</label>
    <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($person['birth_date']); ?>" required>
</div>
                
                <div class="container mb-3">
                    <button type="submit" class="btn btn-primary">Edit Now</button>
                </div>
            </form>

            <?php if ($msg): ?>
            <div class="container mt-4">
                <div class="alert alert-success" id="alert"><strong>Success!</strong> <?php echo $msg; ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>



<script>
$("#mfform").submit(function(){

    <?php $_POST = null; ?>

});
</script>






<h1 class="text-danger" ><? print_r($person); ?></h1>

<?php include "footer.php"; ?>