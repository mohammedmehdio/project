<?php
require_once 'connexion.php';

// Fetch all clubs
$sql_clubs = "SELECT * FROM club";
$clubs = $conn->query($sql_clubs);

// Fetch joined clubs for logged-in user
$id_user = $_SESSION['id_user']; // Assuming user ID is stored in session
$sql_joined_clubs = "SELECT club.club_name FROM user_club 
                     JOIN club ON user_club.id_club = club.id_club 
                     WHERE user_club.id_user = ?";
$stmt_joined_clubs = $conn->prepare($sql_joined_clubs);
$stmt_joined_clubs->bind_param('i', $id_user);
$stmt_joined_clubs->execute();
$joined_clubs = $stmt_joined_clubs->get_result();

// Fetch the logged-in user's name
$sql_user = "SELECT * FROM user WHERE id_user = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param('i', $id_user);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();
$username = $user_data['username'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts for modern typography -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Custom CSS for enhanced styling -->
    <link rel="stylesheet" href="../css/dashboard.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- jQuery (for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* Custom styles */
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
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 20px;
        }
        
    </style>
</head>
<body class="bg-light text-dark">

<!-- Navbar -->
<!-- Navbar -->
<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">ClubHub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Profile</a>
                </li>
                <li class="nav-item ms-3"> <!-- Add margin-start (ms) to separate the buttons -->
                    <a class="nav-link text-danger" href="logout.php"> <!-- Add text-danger for red color -->
                        Sign Out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>



<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1 id="welcomeMessage" class="fw-bold"></h1> <!-- Empty initially -->
        <script>
            var username = <?php echo json_encode($username); ?>;
            document.getElementById('welcomeMessage').textContent = "Hi, " + username + "! Welcome to Your Dashboard";
        </script>
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    <!-- Available Clubs Section -->
    <div class="mb-5">
        <h2 class="fw-semibold text-secondary mb-4">Available Clubs</h2>
        
        <!-- Search Bar -->
        <div class="input-group mb-4">
            <input type="text" id="searchInput" class="form-control" placeholder="Search Clubs" onkeyup="searchClubs()">
            <span class="input-group-text bg-primary text-white">
                <i class="fas fa-search"></i>
            </span>
        </div>

        <!-- Clubs Card Grid -->
        <div class="row">
            <?php while ($club = $clubs->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold"><?= $club["club_name"] ?></h5>
                            <p class="card-text text-muted"><?= $club["description"] ?></p>
                            <div>
                                <?php
                                $club_id = $club["id_club"];
                                $sql_check = "SELECT * FROM user_club WHERE id_user = ? AND id_club = ?";
                                $stmt_check = $conn->prepare($sql_check);
                                $stmt_check->bind_param('ii', $id_user, $club_id);
                                $stmt_check->execute();
                                $result_check = $stmt_check->get_result();

                                if ($result_check->num_rows > 0): ?>
                                    <button class="btn btn-outline-danger" onclick="leaveClub(<?= $club['id_club'] ?>)">
                                        <i class="fas fa-sign-out-alt"></i> Leave
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-outline-primary" onclick="joinClub(<?= $club['id_club'] ?>)">
                                        <i class="fas fa-sign-in-alt"></i> Join
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- User's Clubs Section -->
    <div>
        <h2 class="fw-semibold text-secondary mb-4">Your Clubs</h2>

        <ul class="list-group mb-4">
            <?php while ($joined = $joined_clubs->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= $joined['club_name'] ?>
                    <span class="badge bg-primary rounded-pill">Member</span>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>



<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function searchClubs() {
    var input = document.getElementById("searchInput").value.toLowerCase();
    var cards = document.querySelectorAll(".card");

    cards.forEach(function(card) {
        var clubName = card.querySelector(".card-title").textContent.toLowerCase();
        if (clubName.includes(input)) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
}

var id_user = <?php echo json_encode($id_user); ?>;
function joinClub(clubId) {
    $.ajax({
        url: 'join-button.php',
        type: 'POST',
        data: { action: 'join', club_id: clubId, id_user: id_user },
        success: function(response) { location.reload(); },
        error: function(xhr, status, error) { console.error("Error:", error); }
    });
}

function leaveClub(clubId) {
    $.ajax({
        url: 'leave-button.php',
        type: 'POST',
        data: { action: 'leave', club_id: clubId, id_user: id_user },
        success: function(response) { location.reload(); },
        error: function(xhr, status, error) { console.error("Error:", error); }
    });
}
</script>

</body>
</html>

