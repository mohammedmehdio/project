<?php require "../php/connexion.php";

// SQL statement to get users
$sql_users = "SELECT * FROM user";
$users = $conn->query($sql_users);
?>

<?php require "header.php"; ?>

<div class="container">
    <div class="card mt-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>All Members</h2>
            <div class="input-group" style="width: 400px;">
                <!-- Search Bar with improved design -->
                <input type="text" id="searchInput" class="form-control" placeholder="Search" onkeyup="searchUsers()">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fas fa-search" style="height:25px"></i> <!-- Font Awesome search icon for visual enhancement -->
                    </span>
                </div>
            </div>
            <!-- Print Button -->
            <button onclick="printUsers()" class="btn btn-secondary ml-3">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
        <div class="card-body">
            <a href="add_user.php" class="btn btn-success mb-3">Add New Member</a> <!-- Link to add a new user -->
            <table id="userTable" class='table table-bordered'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Phone</th>
                        <th>Birthdate</th>
                        <th>Clubs Joined</th>
                        <th>Action</th> <!-- Action column is kept for screen view -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $user["id_user"] ?></td>
                            <td><?= $user["first_name"] ?></td>
                            <td><?= $user["last_name"] ?></td>
                            <td><?= $user["email"] ?></td>
                            <td><?= $user["username"] ?></td>
                            <td><?= $user["phone"] ?></td>
                            <td><?= $user["birth_date"] ?></td>

                            <!-- Show Clubs Joined -->
                            <td>
                                <ul>
                                    <?php
                                    // Get clubs that this user has joined
                                    $user_id = $user["id_user"];
                                    $sql_clubs = "SELECT club.club_name FROM user_club 
                                                  JOIN club ON club.id_club = user_club.id_club 
                                                  WHERE user_club.id_user = ?";
                                    $stmt_clubs = $conn->prepare($sql_clubs);
                                    $stmt_clubs->bind_param('i', $user_id);
                                    $stmt_clubs->execute();
                                    $result_clubs = $stmt_clubs->get_result();
                                    
                                    while ($club = $result_clubs->fetch_assoc()): ?>
                                        <li><?= $club['club_name'] ?></li>
                                    <?php endwhile; ?>
                                </ul>
                            </td>

                            <!-- Actions -->
                            <td>
                                <a href="edit.php?id_user=<?= $user["id_user"] ?>" class="btn btn-primary">Edit</a>
                                <a onclick="return confirm('Are you sure you want to delete this member?')" href="delete.php?id_user=<?= $user["id_user"] ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Optionally remove footer if missing -->
<?php /* require "footer.php"; */ ?>

<!-- JavaScript for Print and Search Functionality -->
<script>
// Function to print the table
function printUsers() {
    // Create a new window
    var printWindow = window.open('', '', 'height=900,width=1000');
    
    // Get the HTML content of the table without the action column
    var tableRows = Array.from(document.querySelectorAll('#userTable tr')).map(row => {
        // Get all cells in the row, except the last one (Action column)
        var cells = Array.from(row.children);
        cells.pop(); // Remove the action column
        return '<tr>' + cells.map(cell => cell.outerHTML).join('') + '</tr>';
    }).join('');

    // Write the content to the new window
    printWindow.document.write('<html><head><title>Print User Information</title>');
    printWindow.document.write('<style>table {width: 100%; border-collapse: collapse;} th, td {border: 1px solid black; padding: 8px; text-align: left;} th {background-color: #f2f2f2;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h2>All Members</h2>'); // Optional: Add a title
    printWindow.document.write('<table class="table table-bordered">' + tableRows + '</table>'); // Write the table without action column
    printWindow.document.write('</body></html>');

    // Close the document to render it
    printWindow.document.close();

    // Wait for the content to load before printing
    printWindow.onload = function() {
        printWindow.print();
        printWindow.close(); // Close the print window after printing
    };
}

// Function to search users
function searchUsers() {
    // Get the input value and convert it to lower case
    var input = document.getElementById("searchInput").value.toLowerCase();
    
    // Get all rows of the user table
    var rows = document.querySelectorAll("#userTable tbody tr");
    
    // Loop through the rows and hide those that don't match the search query
    rows.forEach(function(row) {
        // Get the text content of the relevant columns (ID, Username, Email, Phone, Last Name)
        var id = row.children[0].textContent.toLowerCase();
        var firstName = row.children[1].textContent.toLowerCase();
        var lastName = row.children[2].textContent.toLowerCase();
        var email = row.children[3].textContent.toLowerCase();
        var username = row.children[4].textContent.toLowerCase();
        var phone = row.children[5].textContent.toLowerCase();
        
        // Check if any of the columns contain the search query
        if (id.includes(input) || firstName.includes(input) || lastName.includes(input) || email.includes(input) || username.includes(input) || phone.includes(input)) {
            row.style.display = ""; // Show the row if it matches
        } else {
            row.style.display = "none"; // Hide the row if it doesn't match
        }
    });
}
</script>

<!-- Add Font Awesome Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
