document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear all error messages
        document.querySelectorAll('.error-message').forEach(msg => msg.classList.remove('show'));
        
        let isValid = true;
        
        // Full Name validation
        const name = document.getElementById('name').value.trim();
        if (!name) {
            showError('nameError', 'Full name is required');
            isValid = false;
        } else if (name.length < 3) {
            showError('nameError', 'Name must be at least 3 characters');
            isValid = false;
        } else if (!/^[a-zA-Z\s]+$/.test(name)) {
            showError('nameError', 'Name can only contain letters and spaces');
            isValid = false;
        }
        
        // Email validation
        const email = document.getElementById('email').value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email) {
            showError('emailError', 'Email is required');
            isValid = false;
        } else if (!emailRegex.test(email)) {
            showError('emailError', 'Please enter a valid email address');
            isValid = false;
        }
        
        // Password validation
        const password = document.getElementById('password').value;
        if (!password) {
            showError('passwordError', 'Password is required');
            isValid = false;
        } else if (password.length < 8) {
            showError('passwordError', 'Password must be at least 8 characters');
            isValid = false;
        } else if (!/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password)) {
            showError('passwordError', 'Password must contain uppercase, lowercase, and number');
            isValid = false;
        }
        
        // Confirm Password validation
        const passwordConfirm = document.getElementById('passwordConfirm').value;
        if (!passwordConfirm) {
            showError('confirmError', 'Please confirm your password');
            isValid = false;
        } else if (password !== passwordConfirm) {
            showError('confirmError', 'Passwords do not match');
            isValid = false;
        }
        
        // Role validation
        const roleSelected = document.querySelector('input[name="role"]:checked');
        if (!roleSelected) {
            showError('roleError', 'Please select a user role');
            isValid = false;
        }
        
        // Submit if valid
        if (isValid) {
            form.submit();
        }
    });
    
    // Real-time validation for Full Name
    document.getElementById('name').addEventListener('blur', function() {
        const value = this.value.trim();
        const errorDiv = document.getElementById('nameError');
        if (value && value.length < 3) {
            showError('nameError', 'Name must be at least 3 characters');
        } else if (value && !/^[a-zA-Z\s]+$/.test(value)) {
            showError('nameError', 'Name can only contain letters and spaces');
        } else {
            errorDiv.classList.remove('show');
        }
    });
    
    // Real-time validation for Email
    document.getElementById('email').addEventListener('blur', function() {
        const value = this.value.trim();
        const errorDiv = document.getElementById('emailError');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (value && !emailRegex.test(value)) {
            showError('emailError', 'Please enter a valid email address');
        } else {
            errorDiv.classList.remove('show');
        }
    });
    
    // Real-time validation for Password
    document.getElementById('password').addEventListener('blur', function() {
        const value = this.value;
        const errorDiv = document.getElementById('passwordError');
        if (value && value.length < 8) {
            showError('passwordError', 'Password must be at least 8 characters');
        } else if (value && (!/[A-Z]/.test(value) || !/[a-z]/.test(value) || !/[0-9]/.test(value))) {
            showError('passwordError', 'Password must contain uppercase, lowercase, and number');
        } else {
            errorDiv.classList.remove('show');
        }
    });
    
    // Real-time validation for Confirm Password
    document.getElementById('passwordConfirm').addEventListener('blur', function() {
        const password = document.getElementById('password').value;
        const errorDiv = document.getElementById('confirmError');
        if (this.value && password !== this.value) {
            showError('confirmError', 'Passwords do not match');
        } else {
            errorDiv.classList.remove('show');
        }
    });
});

/**
 * Display error message for a field
 * @param {string} elementId - The ID of the error message element
 * @param {string} message - The error message to display
 */
function showError(elementId, message) {
    const errorDiv = document.getElementById(elementId);
    errorDiv.textContent = message;
    errorDiv.classList.add('show');
}