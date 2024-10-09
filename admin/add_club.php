<?php require "../php/connexion.php"; 

$msg = ''; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $club_name = $_POST['club_name'];
    $description = $_POST['description'];

    // Insert query to add a new club
    $sql = "INSERT INTO club (club_name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $club_name, $description);

    if ($stmt->execute()) {
        $msg = 'New club added successfully.';
        header("Location: clubs.php");
        exit(); // Redirect after successful addition
    }
}
?>

<?php require "header.php"; ?>

<div class="container p-5 my-5">
    <div class="card">
        <div class="card-header">
            <h2>Add New Club</h2>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="container mb-4">
                    <label for="club_name">Club Name:</label>
                    <input type="text" class="form-control" id="club_name" name="club_name" required>
                </div>

                <div class="container mb-4">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>

                <div class="container mb-3">
                    <button type="submit" class="btn btn-primary">Add Club</button>
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

<?php require "footer.php"; ?>
