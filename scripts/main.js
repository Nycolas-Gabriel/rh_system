// main.js
document.addEventListener("DOMContentLoaded", function() {
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            const emailGroup = document.getElementById('emailGroup');
            const passwordGroup = document.getElementById('passwordGroup');
            let valid = true;

            // Verificação do email
            if (!email.endsWith('@coren-pe.gov.br')) {
                emailError.style.display = 'block';
                emailGroup.classList.add('error');
                valid = false;
            } else {
                emailError.style.display = 'none';
                emailGroup.classList.remove('error');
            }

            // Verificação da senha
            const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordRegex.test(password)) {
                passwordError.style.display = 'block';
                passwordGroup.classList.add('error');
                valid = false;
            } else {
                passwordError.style.display = 'none';
                passwordGroup.classList.remove('error');
            }

            if (!valid) {
                event.preventDefault();
            }
        });

        const errorIcons = document.querySelectorAll('.error-icon');

        errorIcons.forEach(icon => {
            icon.addEventListener('mouseover', function() {
                const errorBalloon = this.nextElementSibling;
                errorBalloon.style.display = 'block';
            });

            icon.addEventListener('mouseout', function() {
                const errorBalloon = this.nextElementSibling;
                errorBalloon.style.display = 'none';
            });
        });
    }
});
