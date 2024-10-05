document.getElementById('viewClubs').addEventListener('click', function() {
     // Code to fetch and display clubs
     console.log('View Clubs button clicked');
 });
 
 document.getElementById('modifyClubs').addEventListener('click', function() {
     // Code to modify clubs
     console.log('Modify Clubs button clicked');
 });
 
 document.getElementById('viewUsers').addEventListener('click', function() {
     // Code to fetch and display users
     console.log('View Users button clicked');
 });
 
 document.getElementById('modifyUsers').addEventListener('click', function() {
     // Code to modify users
     console.log('Modify Users button clicked');
 });
 document.addEventListener('DOMContentLoaded', () => {
     const viewUsersButton = document.getElementById('viewUsers');
     const usersListDiv = document.getElementById('usersList');
 
     viewUsersButton.addEventListener('click', () => {
         // Clear previous users
         usersListDiv.innerHTML = '';
 
         // Fetch users from view-users.php
         fetch('view-users.php')
             .then(response => {
                 if (!response.ok) {
                     throw new Error('Network response was not ok');
                 }
                 return response.text();
             })
             .then(data => {
                 // Display the fetched data in the usersList div
                 usersListDiv.innerHTML = data;
             })
             .catch(error => {
                 console.error('Error fetching users:', error);
                 usersListDiv.innerHTML = '<p>Error fetching users. Please try again.</p>';
             });
     });
 });
 document.addEventListener('DOMContentLoaded', function() {
     // Delete User
     document.addEventListener('click', function(e) {
         if (e.target.classList.contains('deleteUser')) {
             const userId = e.target.getAttribute('data-id');
 
             if (confirm('Are you sure you want to delete this user?')) {
                 fetch('delete-user.php', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json'
                     },
                     body: JSON.stringify({ id_user: userId })
                 })
                 .then(response => response.json())
                 .then(data => {
                     if (data.success) {
                         alert('User deleted successfully.');
                         location.reload(); // Reload the page to see changes
                     } else {
                         alert('Error: ' + data.message);
                     }
                 })
                 .catch(error => console.error('Error:', error));
             }
         }
     });
 
     // Modify User
     document.addEventListener('click', function(e) {
         if (e.target.classList.contains('editUser')) {
             const userId = e.target.getAttribute('data-id');
             const username = e.target.getAttribute('data-username');
             const email = e.target.getAttribute('data-email');
 
             // Show a prompt to modify user details (you can replace this with a modal or form)
             const newUsername = prompt("Enter new username:", username);
             const newEmail = prompt("Enter new email:", email);
 
             if (newUsername && newEmail) {
                 fetch('modify-user.php', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json'
                     },
                     body: JSON.stringify({ id_user: userId, username: newUsername, email: newEmail })
                 })
                 .then(response => response.json())
                 .then(data => {
                     if (data.success) {
                         alert('User modified successfully.');
                         location.reload(); // Reload the page to see changes
                     } else {
                         alert('Error: ' + data.message);
                     }
                 })
                 .catch(error => console.error('Error:', error));
             }
         }
     });
 });
 
 