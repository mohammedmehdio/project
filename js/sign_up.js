function validateForm() {
     let Firstname = document.getElementById('Firstname').value;
     let Lastname = document.getElementById('Lastname').value;
     let email = document.getElementById('email').value;
     let phone_number = document.getElementById('Phone-number').value;
     let password = document.getElementById('password').value;
     let confirm_password = document.getElementById('Confirm-password').value;
     let date = document.getElementById('date').value;
 
     let FirstnamePattern = /^[a-zA-Z]{3,}$/;
     let LastnamePattern = /^[a-zA-Z]{3,}$/;
     let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
     let phone_numberPattern = /^[0-9]{10}$/;
     let passwordPattern = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/; // At least 8 characters and one number
 
     let FirstnameValid = FirstnamePattern.test(Firstname);
     let LastnameValid = LastnamePattern.test(Lastname);
     let emailValid = emailPattern.test(email);
     let phone_numberValid = phone_numberPattern.test(phone_number);
     let passwordValid = passwordPattern.test(password);
     let confirm_passwordValid = password === confirm_password;
 
     let ageValid = validateAge(date);
 
     let FirstnameError = document.getElementById('Firstname-error');
     let LastnameError = document.getElementById('Lastname-error');
     let emailError = document.getElementById('email-error');
     let phone_numberError = document.getElementById('phone_number-error');
     let passwordError = document.getElementById('password-error');
     let confirm_passwordError = document.getElementById('confirm_password-error');
     let ageError = document.getElementById('age-error');
     
 
     FirstnameError.textContent = '';
     LastnameError.textContent = '';
     emailError.textContent = '';
     phone_numberError.textContent = '';
     passwordError.textContent = '';
     confirm_passwordError.textContent = '';
 
     if (!FirstnameValid) {
         FirstnameError.textContent = 'Please enter a valid Firstname';
     }
 
     if (!LastnameValid) {
         LastnameError.textContent = 'Please enter a valid Lastname';
     }
 
     if (!emailValid) {
         emailError.textContent = 'Please enter a valid email address';
     }
 
     if (!phone_numberValid) {
         phone_numberError.textContent = 'Please enter a valid phone number';
     }
 
     if (!passwordValid) {
         passwordError.textContent = 'Password must be at least 8 characters long and contain at least one number.';
     }
 
     if (!confirm_passwordValid) {
         confirm_passwordError.textContent = 'Passwords do not match.';
     }
 
     if (!ageValid) {
           ageError.textContent = 'Age must be between 17 and 50';
         
     }
 
     if (FirstnameValid && LastnameValid && emailValid && phone_numberValid && passwordValid && confirm_passwordValid && ageValid) {
         
         location.reload();
     }
 }
 
 function validateAge(date) {
     let birthDate = new Date(date);
     let today = new Date();
     let age = today.getFullYear() - birthDate.getFullYear();
     let monthDifference = today.getMonth() - birthDate.getMonth();
 
     if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
         age--;
     }
 
     return age >= 17 && age<=50; // Age must be greater than or equal to 17
 }

 document.addEventListener('DOMContentLoaded', function(){
     let FirstnameInput = document.getElementById('Firstname');
     let LastnameInput = document.getElementById('Lastname');
     let emailInput = document.getElementById('email');
     let phone_numberInput = document.getElementById('Phone-number');
     let ageInput = document.getElementById('date');
     let passwordInput = document.getElementById('password');
     let confirm_passwordInput = document.getElementById('Confirm-password');

     FirstnameInput.addEventListener('keydown', function(event){
           if(event.key === 'Enter'){
               LastnameInput.focus();
          
           } else if(event.key === 'ArrowDown'){
               LastnameInput.focus();
           } 
     });
     LastnameInput.addEventListener('keydown', function(event){
               if(event.key === 'Enter'){
                    emailInput.focus();
               } else if(event.key === 'ArrowDown'){
                    emailInput.focus();
               } 
               else if(event.key === 'ArrowUp'){
                    FirstnameInput.focus();
               }
     });
     emailInput.addEventListener('keydown', function(event){
               if(event.key === 'Enter'){
                    phone_numberInput.focus();
               } else if(event.key === 'ArrowDown'){
                    phone_numberInput.focus();
               } else if(event.key === 'ArrowUp'){
                    FirstnameInput.focus();
               } 
     });
     phone_numberInput.addEventListener('keydown', function(event){
               if(event.key === 'Enter'){
                    ageInput.focus();
               } else if(event.key === 'ArrowDown'){
                    ageInput.focus();
               } else if(event.key === 'ArrowUp'){
                    emailInput.focus();
               } 
     });
     ageInput.addEventListener('keydown', function(event){
               if(event.key === 'Enter'){
                    passwordInput.focus();
               } else if(event.key === 'ArrowDown'){
                    passwordInput.focus();
               } else if(event.key === 'ArrowUp'){
                    phone_numberInput.focus();
               } 
     });
     passwordInput.addEventListener('keydown', function(event){
               if(event.key === 'Enter'){
                    confirm_passwordInput.focus();
               } else if(event.key === 'ArrowDown'){
                    confirm_passwordInput.focus();
               } else if(event.key === 'ArrowUp'){
                    ageInput.focus();
               } 
     });
     confirm_passwordInput.addEventListener('keydown', function(event){
               if(event.key === 'Enter'){
                    validateForm();
               } else if(event.key === 'ArrowUp'){
                    passwordInput.focus();
               } 
     });

 })
 function togglePasswordVisibility(id) {
     let input = document.getElementById(id);
     if (input.type === "password") {
         input.type = "text";
     } else {
         input.type = "password";
     }
 }
 