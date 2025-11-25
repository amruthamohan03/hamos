/**
 * Client Form Validation Utilities
 * Standalone JavaScript helpers for enhanced form validation
 */

const ClientFormValidator = (function() {
  'use strict';

  // Configuration
  const config = {
    maxFileSize: 5242880, // 5MB in bytes
    allowedExtensions: ['pdf', 'jpg', 'jpeg', 'png'],
    allowedMimeTypes: ['application/pdf', 'image/jpeg', 'image/png'],
    phonePattern: /^[0-9+\-\s()]{7,20}$/,
    emailPattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  };

  /**
   * Validate email format
   */
  function validateEmail(email) {
    if (!email) return { valid: true, message: '' };
    
    if (!config.emailPattern.test(email)) {
      return { valid: false, message: 'Invalid email format' };
    }
    
    return { valid: true, message: '' };
  }

  /**
   * Validate phone number
   */
  function validatePhone(phone) {
    if (!phone) return { valid: true, message: '' };
    
    if (!config.phonePattern.test(phone)) {
      return { 
        valid: false, 
        message: 'Phone must be 7-20 characters and contain only numbers, +, -, (), or spaces' 
      };
    }
    
    return { valid: true, message: '' };
  }

  /**
   * Validate file upload
   */
  function validateFile(file) {
    if (!file) return { valid: true, message: '' };

    // Check file size
    if (file.size > config.maxFileSize) {
      return { 
        valid: false, 
        message: `File size must be less than ${formatFileSize(config.maxFileSize)}` 
      };
    }

    // Check file extension
    const extension = getFileExtension(file.name);
    if (!config.allowedExtensions.includes(extension)) {
      return { 
        valid: false, 
        message: `Only ${config.allowedExtensions.join(', ').toUpperCase()} files are allowed` 
      };
    }

    // Check MIME type
    if (!config.allowedMimeTypes.includes(file.type)) {
      return { 
        valid: false, 
        message: 'Invalid file type' 
      };
    }

    return { valid: true, message: '' };
  }

  /**
   * Validate date format and logic
   */
  function validateDate(dateString, fieldName = 'Date') {
    if (!dateString) return { valid: true, message: '' };

    // Check format (YYYY-MM-DD)
    const datePattern = /^\d{4}-\d{2}-\d{2}$/;
    if (!datePattern.test(dateString)) {
      return { valid: false, message: `${fieldName} must be in YYYY-MM-DD format` };
    }

    // Check if valid date
    const date = new Date(dateString);
    if (isNaN(date.getTime())) {
      return { valid: false, message: `${fieldName} is not a valid date` };
    }

    return { valid: true, message: '', date: date };
  }

  /**
   * Validate date range (start must be before end)
   */
  function validateDateRange(startDate, endDate, startLabel = 'Start date', endLabel = 'End date') {
    if (!startDate || !endDate) return { valid: true, message: '' };

    const start = new Date(startDate);
    const end = new Date(endDate);

    if (start > end) {
      return { 
        valid: false, 
        message: `${startLabel} must be before ${endLabel}` 
      };
    }

    return { valid: true, message: '' };
  }

  /**
   * Validate numeric range
   */
  function validateNumericRange(value, min, max, fieldName = 'Value') {
    if (value === '' || value === null || value === undefined) {
      return { valid: true, message: '' };
    }

    const numValue = parseFloat(value);
    
    if (isNaN(numValue)) {
      return { valid: false, message: `${fieldName} must be a number` };
    }

    if (numValue < min || numValue > max) {
      return { 
        valid: false, 
        message: `${fieldName} must be between ${min} and ${max}` 
      };
    }

    return { valid: true, message: '' };
  }

  /**
   * Validate required field
   */
  function validateRequired(value, fieldName = 'Field') {
    if (!value || value.toString().trim() === '') {
      return { valid: false, message: `${fieldName} is required` };
    }
    return { valid: true, message: '' };
  }

  /**
   * Validate string length
   */
  function validateLength(value, min, max, fieldName = 'Field') {
    if (!value) return { valid: true, message: '' };

    const length = value.trim().length;
    
    if (length < min || length > max) {
      return { 
        valid: false, 
        message: `${fieldName} must be between ${min} and ${max} characters` 
      };
    }

    return { valid: true, message: '' };
  }

  /**
   * Validate entire form
   */
  function validateForm(formData) {
    const errors = [];

    // Required: Company Name
    const companyName = validateRequired(formData.company_name, 'Company name');
    if (!companyName.valid) {
      errors.push(companyName.message);
    } else {
      const companyNameLength = validateLength(formData.company_name, 2, 200, 'Company name');
      if (!companyNameLength.valid) errors.push(companyNameLength.message);
    }

    // Required: Client Type
    const clientType = validateRequired(formData.client_type, 'Client type');
    if (!clientType.valid) {
      errors.push(clientType.message);
    } else if (!['I', 'E', 'L', 'IE'].includes(formData.client_type)) {
      errors.push('Invalid client type selected');
    }

    // Optional: Email validations
    if (formData.email) {
      const email = validateEmail(formData.email);
      if (!email.valid) errors.push('Primary ' + email.message);
    }

    if (formData.email_secondary) {
      const emailSecondary = validateEmail(formData.email_secondary);
      if (!emailSecondary.valid) errors.push('Secondary ' + emailSecondary.message);
    }

    if (formData.payment_contact_email) {
      const paymentEmail = validateEmail(formData.payment_contact_email);
      if (!paymentEmail.valid) errors.push('Payment contact ' + paymentEmail.message);
    }

    // Optional: Phone validations
    if (formData.phone) {
      const phone = validatePhone(formData.phone);
      if (!phone.valid) errors.push('Primary phone: ' + phone.message);
    }

    if (formData.phone_secondary) {
      const phoneSecondary = validatePhone(formData.phone_secondary);
      if (!phoneSecondary.valid) errors.push('Secondary phone: ' + phoneSecondary.message);
    }

    // Optional: Credit term validation
    if (formData.credit_term) {
      const creditTerm = validateNumericRange(formData.credit_term, 0, 365, 'Credit term');
      if (!creditTerm.valid) errors.push(creditTerm.message);
    }

    // Optional: Date validations
    if (formData.import_export_validity) {
      const importExportDate = validateDate(formData.import_export_validity, 'Import/Export validity');
      if (!importExportDate.valid) errors.push(importExportDate.message);
    }

    if (formData.attestation_validity) {
      const attestationDate = validateDate(formData.attestation_validity, 'Attestation validity');
      if (!attestationDate.valid) errors.push(attestationDate.message);
    }

    if (formData.contract_start_date && formData.contract_validity) {
      const contractRange = validateDateRange(
        formData.contract_start_date, 
        formData.contract_validity,
        'Contract start date',
        'Contract validity date'
      );
      if (!contractRange.valid) errors.push(contractRange.message);
    }

    return {
      valid: errors.length === 0,
      errors: errors
    };
  }

  /**
   * Helper: Get file extension
   */
  function getFileExtension(filename) {
    return filename.split('.').pop().toLowerCase();
  }

  /**
   * Helper: Format file size
   */
  function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
  }

  /**
   * Helper: Sanitize HTML to prevent XSS
   */
  function sanitizeHTML(str) {
    const temp = document.createElement('div');
    temp.textContent = str;
    return temp.innerHTML;
  }

  /**
   * Helper: Show field error
   */
  function showFieldError(fieldElement, message) {
    fieldElement.classList.add('is-invalid');
    
    let feedback = fieldElement.nextElementSibling;
    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
      feedback = document.createElement('div');
      feedback.className = 'invalid-feedback';
      fieldElement.parentNode.insertBefore(feedback, fieldElement.nextSibling);
    }
    
    feedback.textContent = message;
    feedback.style.display = 'block';
  }

  /**
   * Helper: Clear field error
   */
  function clearFieldError(fieldElement) {
    fieldElement.classList.remove('is-invalid');
    
    const feedback = fieldElement.nextElementSibling;
    if (feedback && feedback.classList.contains('invalid-feedback')) {
      feedback.style.display = 'none';
    }
  }

  /**
   * Helper: Clear all form errors
   */
  function clearAllErrors(formElement) {
    const invalidFields = formElement.querySelectorAll('.is-invalid');
    invalidFields.forEach(field => clearFieldError(field));
    formElement.classList.remove('was-validated');
  }

  /**
   * Real-time field validation
   */
  function setupRealtimeValidation(formElement) {
    // Email fields
    const emailFields = formElement.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
      field.addEventListener('blur', function() {
        if (this.value) {
          const result = validateEmail(this.value);
          if (!result.valid) {
            showFieldError(this, result.message);
          } else {
            clearFieldError(this);
          }
        }
      });
    });

    // Phone fields
    const phoneFields = formElement.querySelectorAll('input[type="tel"]');
    phoneFields.forEach(field => {
      field.addEventListener('blur', function() {
        if (this.value) {
          const result = validatePhone(this.value);
          if (!result.valid) {
            showFieldError(this, result.message);
          } else {
            clearFieldError(this);
          }
        }
      });
    });

    // File inputs
    const fileInputs = formElement.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
      input.addEventListener('change', function() {
        if (this.files.length > 0) {
          const result = validateFile(this.files[0]);
          if (!result.valid) {
            showFieldError(this, result.message);
            this.value = ''; // Clear invalid file
          } else {
            clearFieldError(this);
          }
        }
      });
    });

    // Date fields
    const dateFields = formElement.querySelectorAll('input[type="date"]');
    dateFields.forEach(field => {
      field.addEventListener('change', function() {
        if (this.value) {
          const result = validateDate(this.value, this.previousElementSibling.textContent);
          if (!result.valid) {
            showFieldError(this, result.message);
          } else {
            clearFieldError(this);
          }
        }
      });
    });

    // Required fields
    const requiredFields = formElement.querySelectorAll('[required]');
    requiredFields.forEach(field => {
      field.addEventListener('blur', function() {
        const result = validateRequired(this.value, this.previousElementSibling.textContent);
        if (!result.valid) {
          showFieldError(this, result.message);
        } else {
          clearFieldError(this);
        }
      });
    });
  }

  /**
   * Character counter for textarea/input
   */
  function setupCharacterCounter(element, maxLength) {
    const counter = document.createElement('small');
    counter.className = 'text-muted float-end';
    element.parentNode.appendChild(counter);

    function updateCounter() {
      const remaining = maxLength - element.value.length;
      counter.textContent = `${remaining} characters remaining`;
      
      if (remaining < 0) {
        counter.classList.remove('text-muted');
        counter.classList.add('text-danger');
      } else {
        counter.classList.remove('text-danger');
        counter.classList.add('text-muted');
      }
    }

    element.addEventListener('input', updateCounter);
    updateCounter();
  }

  // Public API
  return {
    validateEmail,
    validatePhone,
    validateFile,
    validateDate,
    validateDateRange,
    validateNumericRange,
    validateRequired,
    validateLength,
    validateForm,
    showFieldError,
    clearFieldError,
    clearAllErrors,
    setupRealtimeValidation,
    setupCharacterCounter,
    sanitizeHTML,
    formatFileSize,
    config
  };

})();

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
  const clientForm = document.getElementById('clientForm');
  
  if (clientForm) {
    // Setup real-time validation
    ClientFormValidator.setupRealtimeValidation(clientForm);
    
    // Setup character counters for text areas
    const textareas = clientForm.querySelectorAll('textarea[maxlength]');
    textareas.forEach(textarea => {
      const maxLength = parseInt(textarea.getAttribute('maxlength'));
      if (maxLength) {
        ClientFormValidator.setupCharacterCounter(textarea, maxLength);
      }
    });

    // Prevent form submission if validation fails
    clientForm.addEventListener('submit', function(e) {
      // Let HTML5 validation run first
      if (!this.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('was-validated');
        return false;
      }

      // Additional custom validation
      const formData = new FormData(this);
      const formDataObj = Object.fromEntries(formData.entries());
      const validation = ClientFormValidator.validateForm(formDataObj);

      if (!validation.valid) {
        e.preventDefault();
        e.stopPropagation();
        
        // Show errors
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: '<ul style="text-align:left;"><li>' + validation.errors.join('</li><li>') + '</li></ul>',
            confirmButtonText: 'OK'
          });
        } else {
          alert('Validation errors:\n' + validation.errors.join('\n'));
        }
        
        return false;
      }
    });

    // Clear validation on reset
    clientForm.addEventListener('reset', function() {
      ClientFormValidator.clearAllErrors(this);
    });
  }
});

// Export for module systems (if needed)
if (typeof module !== 'undefined' && module.exports) {
  module.exports = ClientFormValidator;
}
