<?php require "../php/connexion.php";

// SQL statement to get clubs
$sql_clubs = "SELECT * FROM club";
$clubs = $conn->query($sql_clubs);
?>

<?php require "header.php"; ?>
<script>
    function searchClubs() {
    var input = document.getElementById("searchInput").value.toLowerCase();
    var rows = document.querySelectorAll("#clubTable tbody tr");

    rows.forEach(function(row) {
        var id = row.children[0].textContent.toLowerCase();
        var clubName = row.children[1].textContent.toLowerCase();
        
        if (id.includes(input) || clubName.includes(input)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

function printClubs() {
    var printWindow = window.open('', '', 'height=900,width=1000');
    var tableRows = Array.from(document.querySelectorAll('#clubTable tr')).map(row => {
        var cells = Array.from(row.children);
        cells.pop(); // Remove the action column
        return '<tr>' + cells.map(cell => cell.outerHTML).join('') + '</tr>';
    }).join('');

    printWindow.document.write('<html><head><title>Print Club Information</title>');
    printWindow.document.write('<style>table {width: 100%; border-collapse: collapse;} th, td {border: 1px solid black; padding: 8px; text-align: left;} th {background-color: #f2f2f2;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h2>All Clubs</h2>');
    printWindow.document.write('<table class="table table-bordered">' + tableRows + '</table>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    printWindow.onload = function() {
        printWindow.print();
        printWindow.close();
    };
}
</script>
<div class="container">
    <div class="card mt-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>All Clubs</h2>
            <div class="input-group" style="width: 400px;">
                <!-- Search Bar -->
                <input type="text" id="searchInput" class="form-control" placeholder="Search" onkeyup="searchClubs()">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fas fa-search" style="height:25px;"></i> <!-- Font Awesome search icon -->
                    </span>
                </div>
            </div>
            <!-- Print Button -->
            <button onclick="printClubs()" class="btn btn-secondary ml-3">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
        <div class="card-body">
            <a href="add_club.php" class="btn btn-success mb-3">Add New Club</a>
            <table id="clubTable" class='table table-bordered'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Club Name</th>
                        <th width="500px">Description</th>
                        <th>Members</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($club = $clubs->fetch_assoc()): ?>
                        <tr>
                            <td><?= $club["id_club"] ?></td>
                            <td><?= $club["club_name"] ?></td>
                            <td><?= $club["description"] ?></td>

                            <!-- Show Members -->
                            <td>
                                <ul>
                                    <?php
                                    $club_id = $club["id_club"];
                                    $sql_members = "SELECT user.id_user, user.username FROM user_club 
                                                    JOIN user ON user.id_user = user_club.id_user 
                                                    WHERE user_club.id_club = ?";
                                    $stmt_members = $conn->prepare($sql_members);
                                    $stmt_members->bind_param('i', $club_id);
                                    $stmt_members->execute();
                                    $result_members = $stmt_members->get_result();
                                    
                                    while ($member = $result_members->fetch_assoc()): ?>
                                        <li>
                                            <?= $member['username'] ?>
                                            <!-- Kick user button -->
                                            <a href="kick_user.php?id_user=<?= $member['id_user'] ?>&id_club=<?= $club['id_club'] ?>" 
                                               onclick="return confirm('Are you sure you want to kick this member?')" 
                                               class="btn btn-danger btn-sm">Kick</a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </td>

                            <td>
                                <a href="edit_club.php?id_club=<?= $club["id_club"] ?>" class="btn btn-primary">Edit</a>
                                <a onclick="return confirm('Are you sure you want to delete this club?')" 
                                   href="delete_club.php?id_club=<?= $club["id_club"] ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>

<!-- Add Font Awesome Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
