/**
 * Contact Forms Module
 * Handles all contact forms and newsletter functionality
 */

export class ContactForms {
    constructor() {
        this.generalContactForm = document.querySelector('#general-contact-form, #contact-form');
        this.newsletterForm = document.querySelector('#newsletter-form');
        this.singleCandidateForm = document.querySelector('#single-candidate-contact-form');

        this.init();
    }

    init() {
        this.initGeneralContactForm();
        this.initNewsletterForm();
        this.initSingleCandidateForm();
        this.setupFormValidation();
    }

    initGeneralContactForm() {
        if (!this.generalContactForm) return;

        this.generalContactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.handleGeneralContactSubmit(e);
        });

        // Add real-time validation
        this.addRealTimeValidation(this.generalContactForm);
    }

    initNewsletterForm() {
        if (!this.newsletterForm) return;

        this.newsletterForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.handleNewsletterSubmit(e);
        });

        // Add email validation
        const emailInput = this.newsletterForm.querySelector('input[type="email"]');
        if (emailInput) {
            emailInput.addEventListener('blur', () => {
                this.validateEmail(emailInput);
            });
        }
    }

    initSingleCandidateForm() {
        if (!this.singleCandidateForm) return;

        this.singleCandidateForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.handleCandidateContactSubmit(e);
        });

        this.addRealTimeValidation(this.singleCandidateForm);
    }

    async handleGeneralContactSubmit(e) {
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');

        if (!this.validateForm(form)) {
            return;
        }

        const originalText = submitBtn.innerHTML;
        this.setFormLoading(submitBtn, 'পাঠানো হচ্ছে...');

        try {
            const formData = new FormData(form);
            formData.append('action', 'ducsu_general_contact');
            formData.append('nonce', window.ducsu_ajax.nonce);

            const response = await fetch(window.ducsu_ajax.ajax_url, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification(data.data, 'success');
                this.resetForm(form);
                this.trackFormSubmission('general_contact', 'success');
            } else {
                this.showNotification(data.data, 'error');
                this.trackFormSubmission('general_contact', 'error');
            }
        } catch (error) {
            console.error('General contact form error:', error);
            this.showNotification('বার্তা পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।', 'error');
            this.trackFormSubmission('general_contact', 'error');
        } finally {
            this.resetFormLoading(submitBtn, originalText);
        }
    }

    async handleNewsletterSubmit(e) {
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const emailInput = form.querySelector('input[type="email"]');

        if (!this.validateEmail(emailInput)) {
            return;
        }

        const originalText = submitBtn.innerHTML;
        this.setFormLoading(submitBtn, 'প্রক্রিয়াধীন...');

        try {
            const formData = new FormData(form);
            formData.append('action', 'ducsu_newsletter_subscribe');
            formData.append('nonce', window.ducsu_ajax.nonce);

            const response = await fetch(window.ducsu_ajax.ajax_url, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification(data.data, 'success');
                this.resetForm(form);
                this.trackFormSubmission('newsletter', 'success');

                // Store successful subscription in localStorage
                this.storeNewsletterSubscription(emailInput.value);
            } else {
                this.showNotification(data.data, 'error');
                this.trackFormSubmission('newsletter', 'error');
            }
        } catch (error) {
            console.error('Newsletter subscription error:', error);
            this.showNotification('সাবস্ক্রিপশনে সমস্যা হয়েছে। পরে চেষ্টা করুন।', 'error');
            this.trackFormSubmission('newsletter', 'error');
        } finally {
            this.resetFormLoading(submitBtn, originalText);
        }
    }

    async handleCandidateContactSubmit(e) {
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');

        if (!this.validateForm(form)) {
            return;
        }

        const originalText = submitBtn.innerHTML;
        this.setFormLoading(submitBtn, 'পাঠানো হচ্ছে...');

        try {
            const formData = new FormData(form);
            formData.append('action', 'ducsu_contact');
            formData.append('nonce', window.ducsu_ajax.nonce);

            const response = await fetch(window.ducsu_ajax.ajax_url, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification(data.data, 'success');
                this.resetForm(form);
                this.trackFormSubmission('candidate_contact', 'success');
            } else {
                this.showNotification(data.data, 'error');
                this.trackFormSubmission('candidate_contact', 'error');
            }
        } catch (error) {
            console.error('Candidate contact form error:', error);
            this.showNotification('বার্তা পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।', 'error');
            this.trackFormSubmission('candidate_contact', 'error');
        } finally {
            this.resetFormLoading(submitBtn, originalText);
        }
    }

    setupFormValidation() {
        // Setup custom validation messages
        const forms = [this.generalContactForm, this.newsletterForm, this.singleCandidateForm].filter(Boolean);

        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                this.setupInputValidation(input);
            });
        });
    }

    setupInputValidation(input) {
        // Custom validation messages
        const validationMessages = {
            'name': 'অনুগ্রহ করে আপনার নাম লিখুন',
            'email': 'অনুগ্রহ করে সঠিক ইমেইল ঠিকানা দিন',
            'message': 'অনুগ্রহ করে আপনার বার্তা লিখুন',
            'phone': 'অনুগ্রহ করে সঠিক ফোন নম্বর দিন'
        };

        input.addEventListener('invalid', (e) => {
            e.preventDefault();
            const fieldName = input.name;
            const message = validationMessages[fieldName] || 'এই ফিল্ডটি সঠিকভাবে পূরণ করুন';
            this.showFieldError(input, message);
        });

        input.addEventListener('input', () => {
            this.clearFieldError(input);
        });
    }

    addRealTimeValidation(form) {
        const requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');

        requiredFields.forEach(field => {
            field.addEventListener('blur', () => {
                this.validateField(field);
            });

            field.addEventListener('input', () => {
                if (field.classList.contains('error')) {
                    this.validateField(field);
                }
            });
        });
    }

    validateForm(form) {
        const requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let message = '';

        // Check if required field is empty
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            message = 'এই ফিল্ডটি আবশ্যক';
        }

        // Specific validations
        if (value && field.type === 'email') {
            isValid = this.isValidEmail(value);
            message = isValid ? '' : 'সঠিক ইমেইল ঠিকানা দিন';
        }

        if (value && field.type === 'tel') {
            isValid = this.isValidPhone(value);
            message = isValid ? '' : 'সঠিক ফোন নম্বর দিন';
        }

        if (value && field.name === 'name' && value.length < 2) {
            isValid = false;
            message = 'নাম কমপক্ষে ২ অক্ষরের হতে হবে';
        }

        if (value && field.name === 'message' && value.length < 10) {
            isValid = false;
            message = 'বার্তা কমপক্ষে ১০ অক্ষরের হতে হবে';
        }

        if (isValid) {
            this.clearFieldError(field);
        } else {
            this.showFieldError(field, message);
        }

        return isValid;
    }

    validateEmail(emailInput) {
        if (!emailInput) return false;

        const email = emailInput.value.trim();
        const isValid = email && this.isValidEmail(email);

        if (!isValid) {
            this.showFieldError(emailInput, 'সঠিক ইমেইল ঠিকানা দিন');
        } else {
            this.clearFieldError(emailInput);
        }

        return isValid;
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidPhone(phone) {
        // Simple phone validation - can be enhanced
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]+$/;
        return phoneRegex.test(phone) && phone.replace(/\D/g, '').length >= 10;
    }

    showFieldError(field, message) {
        this.clearFieldError(field);

        field.classList.add('error', 'border-red-500');

        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error text-red-500 text-sm mt-1';
        errorDiv.textContent = message;

        field.parentNode.appendChild(errorDiv);
    }

    clearFieldError(field) {
        field.classList.remove('error', 'border-red-500');

        const errorDiv = field.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    setFormLoading(submitBtn, loadingText) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            ${loadingText}
        `;
    }

    resetFormLoading(submitBtn, originalText) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }

    resetForm(form) {
        form.reset();

        // Clear any error messages
        const errorDivs = form.querySelectorAll('.field-error');
        errorDivs.forEach(div => div.remove());

        // Remove error classes
        const errorFields = form.querySelectorAll('.error');
        errorFields.forEach(field => {
            field.classList.remove('error', 'border-red-500');
        });

        // Focus first input
        const firstInput = form.querySelector('input, textarea');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'error' ? 'bg-red-500 text-white' :
                type === 'success' ? 'bg-green-500 text-white' :
                    'bg-blue-500 text-white'
        }`;

        notification.innerHTML = `
            <div class="flex items-center space-x-3">
                ${type === 'error' ?
            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>' :
            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
        }
                <span>${message}</span>
                <button class="ml-4 hover:opacity-75" type="button">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Show notification
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Auto hide after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);

        // Close button functionality
        notification.querySelector('button').addEventListener('click', () => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        });
    }

    storeNewsletterSubscription(email) {
        try {
            const subscriptions = JSON.parse(localStorage.getItem('ducsu_newsletter_subscriptions') || '[]');
            if (!subscriptions.includes(email)) {
                subscriptions.push(email);
                localStorage.setItem('ducsu_newsletter_subscriptions', JSON.stringify(subscriptions));
            }
        } catch (error) {
            console.warn('Failed to store newsletter subscription:', error);
        }
    }

    trackFormSubmission(formType, status) {
        try {
            const event = {
                type: 'form_submission',
                form_type: formType,
                status: status,
                timestamp: Date.now(),
                user_agent: navigator.userAgent,
                url: window.location.href
            };

            const events = JSON.parse(localStorage.getItem('ducsu_analytics_events') || '[]');
            events.push(event);

            if (events.length > 100) {
                events.splice(0, events.length - 100);
            }

            localStorage.setItem('ducsu_analytics_events', JSON.stringify(events));

            if (typeof gtag !== 'undefined') {
                gtag('event', 'form_submit', {
                    'form_type': formType,
                    'form_status': status
                });
            }
        } catch (error) {
            console.warn('Failed to track form submission:', error);
        }
    }

    validateFormManually(formSelector) {
        const form = document.querySelector(formSelector);
        return form ? this.validateForm(form) : false;
    }

    resetFormManually(formSelector) {
        const form = document.querySelector(formSelector);
        if (form) {
            this.resetForm(form);
        }
    }

    getFormData(formSelector) {
        const form = document.querySelector(formSelector);
        if (!form) return null;

        const formData = new FormData(form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        return data;
    }

    prefillForm(formSelector, data) {
        const form = document.querySelector(formSelector);
        if (!form || !data) return;

        Object.keys(data).forEach(key => {
            const field = form.querySelector(`[name="${key}"]`);
            if (field) {
                field.value = data[key];
            }
        });
    }

    handleEscape() {
        const forms = [this.generalContactForm, this.newsletterForm, this.singleCandidateForm].filter(Boolean);
        forms.forEach(form => {
            const errorFields = form.querySelectorAll('.error');
            errorFields.forEach(field => {
                this.clearFieldError(field);
            });
        });
    }

    destroy() {
        const forms = [this.generalContactForm, this.newsletterForm, this.singleCandidateForm].filter(Boolean);
        forms.forEach(form => {
            this.resetForm(form);
        });
    }
}