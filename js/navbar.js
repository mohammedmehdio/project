// Select the toggle button and menu
const toggle = document.querySelector(".toggle");
const menu = document.querySelector(".menu");

// Toggle mobile menu function
function toggleMenu() {
    menu.classList.toggle("active");
}

// Event Listener for menu toggle
toggle.addEventListener("click", toggleMenu, false);
