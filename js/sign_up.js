window.onload = function(){
    firstName.focus();
}
function validateForm() {
     let firstName = document.getElementById('firstName').value;
     let lastName = document.getElementById('lastName').value;
     let email = document.getElementById('email').value;
     let phone = document.getElementById('phone').value;
     let password = document.getElementById('password').value;
     let confirmPassword = document.getElementById('confirm_password').value;
     let birthDate = document.getElementById('birth_date').value;
 
     let FirstnamePattern = /^([a-zA-Z]{3,} ?)+$/;
     let lastNamePattern = /^[a-zA-Z]{3,}$/;
     let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
     let phonePattern = /^[0-9]{10}$/;
     let passwordPattern = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/; // At least 8 characters and one number
 
     let firstNameValid = FirstnamePattern.test(firstName);
     let lastNameValid = lastNamePattern.test(lastName);
     let emailValid = emailPattern.test(email);
     let phoneValid = phonePattern.test(phone);
     let passwordValid = passwordPattern.test(password);
     let confirmPasswordValid = password === confirmPassword;
 
     let ageValid = validateAge(birthDate);
 
     let firstNameError = document.getElementById('Firstname-error');
     let lastNameError = document.getElementById('Lastname-error');
     let emailError = document.getElementById('email-error');
     let phoneError = document.getElementById('phone_number-error');
     let passwordError = document.getElementById('password-error');
     let confirmPasswordError = document.getElementById('confirm_password-error');
     let ageError = document.getElementById('age-error');
 
     firstNameError.textContent = '';
     lastNameError.textContent = '';
     emailError.textContent = '';
     phoneError.textContent = '';
     passwordError.textContent = '';
     confirmPasswordError.textContent = '';
     ageError.textContent = '';
 
     if (!firstNameValid) {
         firstNameError.textContent = 'Please enter a valid Firstname';
     }
     if (!lastNameValid) {
         lastNameError.textContent = 'Please enter a valid Lastname';
     }
     if (!emailValid) {
         emailError.textContent = 'Please enter a valid email address';
     }
     if (!phoneValid) {
         phoneError.textContent = 'Please enter a valid phone number';
     }
     if (!passwordValid) {
         passwordError.textContent = 'Password must be at least 8 characters long and contain at least one number.';
     }
     if (!confirmPasswordValid) {
         confirmPasswordError.textContent = 'Passwords do not match.';
     }
     if (!ageValid) {
         ageError.textContent = 'Age must be between 17 and 50';
     }
 
     return firstNameValid && lastNameValid && emailValid && phoneValid && passwordValid && confirmPasswordValid && ageValid;
 }
 
 function validateAge(birthDate) {
     let birthDateObj = new Date(birthDate);
     let today = new Date();
     let age = today.getFullYear() - birthDateObj.getFullYear();
     let monthDifference = today.getMonth() - birthDateObj.getMonth();
 
     if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDateObj.getDate())) {
         age--;
     }
 
     return age >= 17 && age <= 50; // Age must be between 17 and 50
 }
 
 document.addEventListener('DOMContentLoaded', function() {
     let firstNameInput = document.getElementById('firstName');
     let lastNameInput = document.getElementById('lastName');
     let emailInput = document.getElementById('email');
     let phoneInput = document.getElementById('phone');
     let birthDateInput = document.getElementById('birth_date');
     let passwordInput = document.getElementById('password');
     let confirmPasswordInput = document.getElementById('confirm_password');
 
     firstNameInput.addEventListener('keydown', function(event) {
         if (event.key === 'Enter' || event.key === 'ArrowDown') {
             lastNameInput.focus();
         }
     });
     lastNameInput.addEventListener('keydown', function(event) {
         if (event.key === 'Enter' || event.key === 'ArrowDown') {
             emailInput.focus();
         } else if (event.key === 'ArrowUp') {
             firstNameInput.focus();
         }
     });
     emailInput.addEventListener('keydown', function(event) {
         if (event.key === 'Enter' || event.key === 'ArrowDown') {
             phoneInput.focus();
         } else if (event.key === 'ArrowUp') {
             lastNameInput.focus();
         }
     });
     phoneInput.addEventListener('keydown', function(event) {
         if (event.key === 'Enter' || event.key === 'ArrowDown') {
             birthDateInput.focus();
         } else if (event.key === 'ArrowUp') {
             emailInput.focus();
         }
     });
     birthDateInput.addEventListener('keydown', function(event) {
         if (event.key === 'Enter' || event.key === 'ArrowDown') {
             passwordInput.focus();
         } else if (event.key === 'ArrowUp') {
             phoneInput.focus();
         }
     });
     passwordInput.addEventListener('keydown', function(event) {
         if (event.key === 'Enter' || event.key === 'ArrowDown') {
             confirmPasswordInput.focus();
         } else if (event.key === 'ArrowUp') {
             birthDateInput.focus();
         }
     });
     confirmPasswordInput.addEventListener('keydown', function(event) {
         if (event.key === 'Enter') {
             validateForm();
         } else if (event.key === 'ArrowUp') {
             passwordInput.focus();
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
 