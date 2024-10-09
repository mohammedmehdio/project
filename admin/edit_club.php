<?php require "../php/connexion.php"; 

$id = $_GET['id_club']; // Get club ID from the URL
$msg = ''; // Initialize message variable

// Prepare the SQL statement to fetch club info
$sql = "SELECT * FROM club WHERE id_club=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$club = $result->fetch_assoc(); // Fetch associative array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $club_name = $_POST['club_name'];
    $description = $_POST['description'];

    // SQL statement for update
    $sql = "UPDATE club SET club_name=?, description=? WHERE id_club=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $club_name, $description, $id);

    if ($stmt->execute()) {
        $msg = 'Club edited successfully.';
        header("Location: clubs.php");
        exit(); // Ensure to exit after redirecting
    }
}
?>

<?php require "header.php"; ?>

<div class="container p-5 my-5">
    <div class="card">
        <div class="card-header">
            <h2>Edit Club Info</h2>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="container mb-4">
                    <label for="club_name">Club Name:</label>
                    <input type="text" class="form-control" id="club_name" name="club_name" value="<?php echo htmlspecialchars($club['club_name']); ?>" required>
                </div>

                <div class="container mb-4">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($club['description']); ?></textarea>
                </div>

                <div class="container mb-3">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
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
