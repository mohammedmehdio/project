<?php
require_once 'connexion.php';

// Ensure user is logged in
$id_user = $_SESSION['id_user'];

// Fetch user's joined clubs
$sql_joined_clubs = "SELECT club.id_club, club.club_name FROM user_club 
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
    <title>Your Clubs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url(../img/blue.jpg) no-repeat center center fixed;
            background-size: cover;
            font-family: "Poppins", sans-serif;
        }
        .container {
            max-width: 900px;
        }
        .club-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }
        .club-card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-leave {
            background-color: #ff4d4d;
            border: none;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-leave:hover {
            background-color: #ff3333;
        }

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
    </style>
</head>
<body>
 <!-- Navbar with Dashboard and password change modal trigger -->
 <nav class="navbar navbar-expand-lg" style="margin-bottom: 20px;">
    <div class="container">
        <a class="navbar-brand fw-bold" style="position: absolute; left:2rem; " href="#">ClubHub</a>
        <div class="profile-dropdown" style="position: absolute; right:0;">
        <a class="navbar-brand"  href="club.php">Dashboard</a>
        <a class="navbar-brand" href="profile.php">Profile</a>
    </div>
</nav>

<div class="container py-5">
    <h2 class="fw-semibold text-secondary mb-4">Your Clubs</h2>

    <!-- Search Bar -->
    <div class="mb-4">
        <input type="text" id="searchInput" class="form-control" placeholder="Search your clubs..." onkeyup="searchClubs()">
    </div>

    <!-- Clubs as Cards -->
    <div class="row g-4" id="clubCards">
        <?php while ($joined = $joined_clubs->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card club-card">
                    <div class="card-body">
                        <span class="club-name"><?= htmlspecialchars($joined['club_name']) ?></span>
                        <button class="btn-leave" onclick="leaveClub(<?= $joined['id_club'] ?>)">Leave</button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // JavaScript for Filtering Clubs
    function searchClubs() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const cards = document.querySelectorAll(".club-card");

        cards.forEach(card => {
            const clubName = card.querySelector(".club-name").textContent.toLowerCase();
            card.style.display = clubName.includes(input) ? "" : "none";
        });
    }

    // AJAX call for leaving a club
    function leaveClub(clubId) {
        $.ajax({
            url: 'leave-button.php',
            type: 'POST',
            data: { action: 'leave', club_id: clubId, id_user: <?= json_encode($id_user); ?> },
            success: function(response) {
                location.reload(); // Refresh the page after leaving the club
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }
</script>
</body>
</html>
