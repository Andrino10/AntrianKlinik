/**
 * Main JavaScript File
 * Script umum untuk semua halaman
 * 
 * @package Smart Queue System
 * @subpackage Assets/JS
 */

// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    console.log('Smart Queue System loaded');
    
    // Initialize tooltips jika ada
    initializeTooltips();
    
    // Setup event listeners
    setupEventListeners();
});

/**
 * Initialize tooltips
 */
function initializeTooltips() {
    // Placeholder untuk tooltip initialization
}

/**
 * Setup global event listeners
 */
function setupEventListeners() {
    // Dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
    // Setup form submission handlers
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Add any global form validation here
        });
    });
}

/**
 * Show alert message
 * @param {string} message 
 * @param {string} type - 'success', 'danger', 'warning', 'info'
 */
function showAlert(message, type = 'info') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    
    const contentArea = document.querySelector('.content-area');
    if (contentArea) {
        contentArea.insertBefore(alert, contentArea.firstChild);
        
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }
}

/**
 * Make AJAX request
 * @param {string} url 
 * @param {object} data 
 * @param {function} callback 
 */
function makeAjaxRequest(url, data = {}, callback = null) {
    const formData = new FormData();
    
    if (typeof data === 'object') {
        for (const key in data) {
            formData.append(key, data[key]);
        }
    }
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (callback) {
            callback(data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan', 'danger');
    });
}

/**
 * Format currency to IDR
 * @param {number} amount 
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
}

/**
 * Format date to Indonesia format
 * @param {string} date 
 */
function formatDateId(date) {
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        locale: 'id-ID'
    };
    return new Date(date).toLocaleDateString('id-ID', options);
}

/**
 * Validate email format
 * @param {string} email 
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Validate NIK (16 digits)
 * @param {string} nik 
 */
function validateNIK(nik) {
    return /^\d{16}$/.test(nik);
}

/**
 * Disable form on submit
 * @param {HTMLFormElement} form 
 */
function disableFormOnSubmit(form) {
    form.addEventListener('submit', function() {
        const submitBtn = form.querySelector('[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';
        }
    });
}

/**
 * Confirm action before proceeding
 * @param {string} message 
 */
function confirmAction(message = 'Apakah Anda yakin?') {
    return confirm(message);
}

