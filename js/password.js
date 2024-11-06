window.onload = function(){
    current_password.focus();
}

function togglePasswordVisibility(id) {
    let input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    let current_password = document.getElementById('current_password');
    let new_password = document.getElementById('new_password');
    let confirm_password = document.getElementById('confirm_password');
    

    current_password.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === 'ArrowDown') {
            new_password.focus();
        }
    });
    new_password.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === 'ArrowDown') {
            confirm_password.focus();
        } else if (event.key === 'ArrowUp') {
            current_password.focus();
        }
    });
    confirm_password.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            validateForm();
        } else if (event.key === 'ArrowUp') {
            new_password.focus();
        }
    });
});

function togglePasswordVisibility(id) {
    let input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}