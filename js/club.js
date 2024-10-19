// club.js

// Function to search clubs
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
 
 // Variables and functions for joining and leaving clubs
 var id_user = JSON.parse(document.getElementById('id_user').value);
 
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
 