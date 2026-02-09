const registerForm = document.querySelector('.form');

if (registerForm && document.querySelector('#confirm_password')) {
    const passwordInput = document.querySelector('#password');
    const confirmInput = document.querySelector('#confirm_password');

    const showError = (input, message) => {
        let error = input.parentElement.querySelector('.form-error');
        if (!error) {
            error = document.createElement('div');
            error.classList.add('form-error');
            error.style.color = '#ef4444';
            error.style.fontSize = '0.85rem';
            input.parentElement.appendChild(error);
        }
        error.textContent = message;
    };

    const clearError = (input) => {
        const error = input.parentElement.querySelector('.form-error');
        if (error) {
            error.remove();
        }
    };

    const validatePassword = () => {
        const value = passwordInput.value;
        const valid = value.length >= 8 && /[A-Z]/.test(value) && /\d/.test(value) && /[^A-Za-z0-9]/.test(value);
        if (!valid) {
            showError(passwordInput, 'Password must include 8 characters, an uppercase letter, a number, and a special character.');
        } else {
            clearError(passwordInput);
        }
        return valid;
    };

    const validateConfirm = () => {
        if (passwordInput.value !== confirmInput.value) {
            showError(confirmInput, 'Passwords do not match.');
            return false;
        }
        clearError(confirmInput);
        return true;
    };

    passwordInput.addEventListener('input', validatePassword);
    confirmInput.addEventListener('input', validateConfirm);

    registerForm.addEventListener('submit', (event) => {
        const isPasswordValid = validatePassword();
        const isConfirmValid = validateConfirm();
        if (!isPasswordValid || !isConfirmValid) {
            event.preventDefault();
        }
    });
}
