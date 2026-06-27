/**
 * Form Validation Script
 * Script untuk validasi form client-side
 * 
 * @package Smart Queue System
 * @subpackage Assets/JS
 */

document.addEventListener('DOMContentLoaded', function() {
    setupFormValidation();
});

/**
 * Setup form validation untuk semua form
 */
function setupFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
        
        // Real-time validation
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateInput(this);
            });
        });
    });
}

/**
 * Validasi seluruh form
 * @param {HTMLFormElement} form 
 */
function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('.form-control[required]');
    
    inputs.forEach(input => {
        if (!validateInput(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Validasi input individual
 * @param {HTMLInputElement} input 
 */
function validateInput(input) {
    const value = input.value.trim();
    const type = input.type;
    const name = input.name;
    
    // Clear previous error
    const errorElement = document.getElementById(`error-${name}`);
    if (errorElement) {
        errorElement.remove();
    }
    input.classList.remove('is-invalid');
    
    // Validasi empty
    if (input.hasAttribute('required') && value === '') {
        showInputError(input, 'Field ini wajib diisi');
        return false;
    }
    
    // Validasi berdasarkan type
    switch(type) {
        case 'email':
            if (value && !validateEmail(value)) {
                showInputError(input, 'Email tidak valid');
                return false;
            }
            break;
            
        case 'number':
            if (value && isNaN(value)) {
                showInputError(input, 'Harus berupa angka');
                return false;
            }
            break;
            
        case 'tel':
            if (value && !validatePhone(value)) {
                showInputError(input, 'Nomor telepon tidak valid');
                return false;
            }
            break;
            
        case 'password':
            if (value && value.length < 8) {
                showInputError(input, 'Password minimal 8 karakter');
                return false;
            }
            break;
    }
    
    // Validasi khusus berdasarkan name
    if (name === 'nik') {
        if (value && !validateNIK(value)) {
            showInputError(input, 'NIK harus 16 digit');
            return false;
        }
    }
    
    if (name === 'password_confirm') {
        const passwordField = document.querySelector('[name="password"]');
        if (value && passwordField && value !== passwordField.value) {
            showInputError(input, 'Password tidak cocok');
            return false;
        }
    }
    
    if (name === 'tanggal_lahir') {
        if (value) {
            const birthDate = new Date(value);
            const today = new Date();
            if (birthDate > today) {
                showInputError(input, 'Tanggal lahir tidak boleh masa depan');
                return false;
            }
        }
    }
    
    return true;
}

/**
 * Tampilkan error message untuk input
 * @param {HTMLInputElement} input 
 * @param {string} message 
 */
function showInputError(input, message) {
    input.classList.add('is-invalid');
    
    const errorElement = document.createElement('div');
    errorElement.id = `error-${input.name}`;
    errorElement.className = 'error-message';
    errorElement.textContent = message;
    errorElement.style.color = '#E63757';
    errorElement.style.fontSize = '12px';
    errorElement.style.marginTop = '4px';
    
    input.parentNode.appendChild(errorElement);
}

/**
 * Validasi email
 * @param {string} email 
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Validasi NIK
 * @param {string} nik 
 */
function validateNIK(nik) {
    return /^\d{16}$/.test(nik);
}

/**
 * Validasi nomor telepon
 * @param {string} phone 
 */
function validatePhone(phone) {
    const re = /^(\+62|0)[0-9]{9,12}$/;
    return re.test(phone);
}

/**
 * Validasi password match
 * @param {string} password 
 * @param {string} confirm 
 */
function validatePasswordMatch(password, confirm) {
    return password === confirm && password.length >= 8;
}

