function searchClubs() {
    const searchInput = document.getElementById('clubSearch').value.toLowerCase();
    const clubs = document.querySelectorAll('.container');
    
    clubs.forEach((club) => {
        const clubName = club.querySelector('.sportclub').textContent.toLowerCase();
        if (clubName.includes(searchInput)) {
            club.style.display = "block"; // Show matching club
        } else {
            club.style.display = "none"; // Hide non-matching club
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.button_join');

    buttons.forEach((button) => {
        // Create a message element for displaying feedback
        const messageElement = document.createElement('p');
        messageElement.classList.add('status-message'); // Add class for styling
        button.parentElement.appendChild(messageElement); // Append message element below the button

        button.addEventListener('click', function() {
            const clubName = button.parentElement.querySelector('.sportclub').innerText;
            const id_user = localStorage.getItem('id_user');

            if (id_user) {
                if (button.textContent === 'Join') {
                    // Join the club
                    fetch('../php/join-button.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id_user: id_user,
                            club_name: clubName
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            button.textContent = 'Leave'; // Change button text to Leave
                            button.style.backgroundColor = 'red'; // Change button color to red
                            messageElement.textContent = 'Successfully joined the club!'; // Update message
                            messageElement.style.color = 'green'; // Set text color to green
                        } else {
                            messageElement.textContent = 'Error: ' + data.message; // Update message
                            messageElement.style.color = 'red'; // Set text color to red
                        }
                    })
                    .catch(error => console.error('Error:', error));
                } else {
                    // Leave the club
                    fetch('../php/leave-button.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id_user: id_user,
                            club_name: clubName
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            button.textContent = 'Join'; // Change button text back to Join
                            button.style.backgroundColor = ''; // Reset button color
                            messageElement.textContent = 'Successfully left the club!'; // Update message
                            messageElement.style.color = 'red'; // Set text color to red
                        } else {
                            messageElement.textContent = 'Error: ' + data.message; // Update message
                            messageElement.style.color = 'red'; // Set text color to red
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            } else {
                messageElement.textContent = 'Invalid user data.'; // Update message
                messageElement.style.color = 'red'; // Set text color to red
            }
        });
    });
});
