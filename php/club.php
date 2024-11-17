<?php
require_once 'connexion.php';

// Define the default profile photo path
$default_photo_path = "../img/profile.png"; // Path to the default photo

// Fetch the logged-in user's information including profile photo
$id_user = $_SESSION['id_user']; // Assuming user ID is stored in session
$sql_user = "SELECT username, profile_photo FROM user WHERE id_user = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param('i', $id_user);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();
$username = $user_data['username'];

// Check if the user has a profile photo, otherwise use default
$profile_photo = !empty($user_data['profile_photo']) ? $user_data['profile_photo'] : $default_photo_path;

// Fetch all clubs
$sql_clubs = "SELECT * FROM club";
$clubs = $conn->query($sql_clubs);

// Fetch joined clubs for logged-in user
$sql_joined_clubs = "SELECT club.club_name FROM user_club 
                     JOIN club ON user_club.id_club = club.id_club 
                     WHERE user_club.id_user = ?";
$stmt_joined_clubs = $conn->prepare($sql_joined_clubs);
$stmt_joined_clubs->bind_param('i', $id_user);
$stmt_joined_clubs->execute();
$joined_clubs = $stmt_joined_clubs->get_result();
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
        ul {
    padding-left: 0;
}
.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between; /* Add this */
    background-color: rgba(27, 103, 218, 0.356);
}

.profile-dropdown {
    position: relative;
    display: flex;
    align-items: center;
    margin-left: auto; /* Ensure it stays at the right */
}
/* Add these styles inside the <style> tag */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
}

.card-body {
    padding: 30px;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #4a4a4a;
}

.card-text {
    color: #6c757d;
    font-size: 1rem;
}

.btn-outline-primary,
.btn-outline-danger {
    border-width: 2px;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover,
.btn-outline-danger:hover {
    color: #fff;
}

.btn-outline-primary:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
}
/* Add these styles inside the <style> tag */
.card {
    background: rgba(0, 0, 51, 0.7); /* Dark transparent blue background */
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
    color: #fff;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.5);
    background: rgba(0, 0, 77, 0.9); /* Slightly darker blue on hover */
}

.card-body {
    padding: 30px;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #f1f1f1;
}

.card-text {
    color: #d3d3d3;
    font-size: 1rem;
}

.btn-outline-primary,
.btn-outline-danger {
    border-width: 2px;
    border-radius: 20px;
    transition: all 0.3s ease;
    color: #fff;
    background-color: transparent;
}

.btn-outline-primary {
    border-color: #1e90ff;
}

.btn-outline-danger {
    border-color: #dc3545;
}

.btn-outline-primary:hover {
    background-color: #1e90ff;
    border-color: #1e90ff;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
}

/* Animation for card elements */
.card-title, .card-text, .btn {
    animation: fadeInUp 0.5s ease forwards;
    opacity: 0;
    transform: translateY(30px);
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    </style>
</head>
<body class="bg-light text-dark">

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold" style="position: absolute; left:2rem; " href="#">ClubHub</a>
        <div class="profile-dropdown" style="position: absolute; right:0;">
    <div onclick="toggle()" class="profile-dropdown-btn">
        <div class="profile-img">
            <img src="../uploads/<?php echo htmlspecialchars($profile_photo); ?>" alt="Profile Photo" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
        </div>
        <span id="profile" class="fw-bold" style="color: white; font-size: 1.2rem; padding-right: 5px;">
            <?php echo htmlspecialchars($username); ?>
            <i class="fa-solid fa-angle-down" style="font-size: small"></i>
        </span>
    </div>
    <ul class="profile-dropdown-list">
        <li class="profile-dropdown-list-item">
            <a href="profile.php">
                <i class="fa-regular fa-user"></i>
                Edit Profile
            </a>
        </li>
        <li class="profile-dropdown-list-item">
            <a href="stats.php">
                <i class="fa-solid fa-chart-line"></i>
                Analytics
            </a>
        </li>
        <hr/>
        <li class="profile-dropdown-list-item" id="logouthover">
            <a href="logout.php">
                <i class="fa-solid fa-arrow-right-from-bracket" style="background-color: #e41c38;"></i>
                Log out
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
            document.getElementById('welcomeMessage').textContent = "Hi, " + username + " ! Welcome to Your Dashboard";
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
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= $club["club_name"] ?></h5>
                    <p class="card-text"><?= $club["description"] ?></p>
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

    <!-- User's Clubs Section -->
            <?php while ($joined = $joined_clubs->fetch_assoc()): ?>
                    <?= $joined['club_name'] ?>
            <?php endwhile; ?>


<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let profileDropdownList = document.querySelector(".profile-dropdown-list");
let btn = document.querySelector(".profile-dropdown-btn");

let classList = profileDropdownList.classList;

const toggle = () => classList.toggle("active");

window.addEventListener("click", function (e) {
  if (!btn.contains(e.target)) classList.remove("active");
});

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

