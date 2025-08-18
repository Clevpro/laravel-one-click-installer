/**
 * Laravel One-Click Installer - JavaScript Enhancements
 * 
 * This file provides additional interactive features for the installer.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize installer features
    initializeFormValidation();
    initializeProgressTracking();
    initializeAnimations();
    initializeAccessibility();
    
    /**
     * Form validation enhancements
     */
    function initializeFormValidation() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            // Real-time validation
            const inputs = form.querySelectorAll('input[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', validateField);
                input.addEventListener('input', clearFieldError);
            });
            
            // Form submission handling
            form.addEventListener('submit', handleFormSubmission);
        });
    }
    
    /**
     * Validate individual form field
     */
    function validateField(event) {
        const field = event.target;
        const value = field.value.trim();
        const fieldName = field.name;
        
        // Clear previous errors
        clearFieldError(event);
        
        // Validation rules
        let isValid = true;
        let errorMessage = '';
        
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required.';
        } else if (field.type === 'email' && value && !isValidEmail(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address.';
        } else if (field.type === 'password' && value && value.length < 8) {
            isValid = false;
            errorMessage = 'Password must be at least 8 characters long.';
        } else if (fieldName === 'db_port' && value && (value < 1 || value > 65535)) {
            isValid = false;
            errorMessage = 'Port must be between 1 and 65535.';
        }
        
        if (!isValid) {
            showFieldError(field, errorMessage);
        }
        
        return isValid;
    }
    
    /**
     * Clear field error styling
     */
    function clearFieldError(event) {
        const field = event.target;
        field.classList.remove('border-red-500');
        
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }
    
    /**
     * Show field error
     */
    function showFieldError(field, message) {
        field.classList.add('border-red-500');
        
        const errorElement = document.createElement('p');
        errorElement.className = 'field-error mt-1 text-sm text-red-600';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    }
    
    /**
     * Handle form submission
     */
    function handleFormSubmission(event) {
        const form = event.target;
        const submitButton = form.querySelector('button[type="submit"]');
        
        if (submitButton) {
            submitButton.disabled = true;
            
            // Add loading state
            const originalText = submitButton.textContent;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            `;
            
            // Re-enable if form submission fails
            setTimeout(() => {
                if (submitButton.disabled) {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            }, 10000);
        }
    }
    
    /**
     * Progress tracking and step navigation
     */
    function initializeProgressTracking() {
        const currentStep = getCurrentStep();
        const totalSteps = getTotalSteps();
        
        if (currentStep && totalSteps) {
            updateProgressBar(currentStep, totalSteps);
            updateStepIndicators(currentStep);
        }
    }
    
    /**
     * Get current step from page
     */
    function getCurrentStep() {
        const stepElement = document.querySelector('[data-current-step]');
        return stepElement ? parseInt(stepElement.getAttribute('data-current-step')) : null;
    }
    
    /**
     * Get total steps
     */
    function getTotalSteps() {
        const stepsElement = document.querySelector('[data-total-steps]');
        return stepsElement ? parseInt(stepsElement.getAttribute('data-total-steps')) : 5;
    }
    
    /**
     * Update progress bar
     */
    function updateProgressBar(current, total) {
        const progressBar = document.querySelector('.installer-progress-bar');
        if (progressBar) {
            const percentage = (current / total) * 100;
            progressBar.style.width = `${percentage}%`;
        }
    }
    
    /**
     * Update step indicators
     */
    function updateStepIndicators(currentStep) {
        const indicators = document.querySelectorAll('.step-indicator');
        
        indicators.forEach((indicator, index) => {
            const stepNumber = index + 1;
            
            indicator.classList.remove('active', 'completed');
            
            if (stepNumber < currentStep) {
                indicator.classList.add('completed');
            } else if (stepNumber === currentStep) {
                indicator.classList.add('active');
            }
        });
    }
    
    /**
     * Initialize animations
     */
    function initializeAnimations() {
        // Fade in content
        const content = document.querySelector('.fade-in');
        if (content) {
            content.style.opacity = '0';
            content.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                content.style.transition = 'all 0.5s ease-out';
                content.style.opacity = '1';
                content.style.transform = 'translateY(0)';
            }, 100);
        }
        
        // Animate step indicators
        const indicators = document.querySelectorAll('.step-indicator');
        indicators.forEach((indicator, index) => {
            setTimeout(() => {
                indicator.style.opacity = '1';
                indicator.style.transform = 'scale(1)';
            }, index * 100);
        });
    }
    
    /**
     * Accessibility enhancements
     */
    function initializeAccessibility() {
        // Keyboard navigation
        document.addEventListener('keydown', handleKeyboardNavigation);
        
        // Focus management
        manageFocus();
        
        // Screen reader announcements
        announceStepChanges();
    }
    
    /**
     * Handle keyboard navigation
     */
    function handleKeyboardNavigation(event) {
        if (event.key === 'Enter' && event.target.matches('a')) {
            event.target.click();
        }
    }
    
    /**
     * Manage focus for better accessibility
     */
    function manageFocus() {
        const firstInput = document.querySelector('input:not([type="hidden"])');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 500);
        }
    }
    
    /**
     * Announce step changes to screen readers
     */
    function announceStepChanges() {
        const currentStep = getCurrentStep();
        if (currentStep) {
            announceToScreenReader(`Step ${currentStep} of installation wizard`);
        }
    }
    
    /**
     * Utility functions
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = message;
        
        document.body.appendChild(announcement);
        
        setTimeout(() => {
            document.body.removeChild(announcement);
        }, 1000);
    }
    
    /**
     * Auto-save form data to localStorage (optional)
     */
    function initializeAutoSave() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="url"]');
            
            inputs.forEach(input => {
                // Load saved data
                const savedValue = localStorage.getItem(`installer_${input.name}`);
                if (savedValue && !input.value) {
                    input.value = savedValue;
                }
                
                // Save on change
                input.addEventListener('input', () => {
                    localStorage.setItem(`installer_${input.name}`, input.value);
                });
            });
        });
    }
    
    // Initialize auto-save if enabled
    if (window.installerConfig && window.installerConfig.autoSave) {
        initializeAutoSave();
    }
    
    // Password strength indicator (if needed)
    const passwordInput = document.querySelector('input[name="password"]');
    if (passwordInput) {
        passwordInput.addEventListener('input', updatePasswordStrength);
    }
    
    function updatePasswordStrength(event) {
        const password = event.target.value;
        const strength = calculatePasswordStrength(password);
        
        // You can add UI elements to show password strength here
        console.log('Password strength:', strength);
    }
    
    function calculatePasswordStrength(password) {
        let score = 0;
        
        if (password.length >= 8) score += 1;
        if (/[a-z]/.test(password)) score += 1;
        if (/[A-Z]/.test(password)) score += 1;
        if (/[0-9]/.test(password)) score += 1;
        if (/[^A-Za-z0-9]/.test(password)) score += 1;
        
        return score;
    }
});

// Export for use in other scripts
window.InstallerJS = {
    validateEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },
    
    showNotification: function(message, type = 'info') {
        // Simple notification system
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
};