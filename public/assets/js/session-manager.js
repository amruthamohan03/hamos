/**
 * Session Manager - Client Side
 * Handles session timeout, warnings, and keep-alive
 */

class SessionManager {
    constructor(config = {}) {
        // Configuration (all times in SECONDS for consistency)
        this.baseUrl = config.baseUrl || '';
        this.sessionTimeout = config.sessionTimeout || 1800; // 30 minutes default
        this.warningTime = config.warningTime || 300; // 5 minutes warning (in SECONDS)
        this.checkInterval = config.checkInterval || 60000; // Check every minute (in milliseconds)
        this.keepAliveInterval = config.keepAliveInterval || 300000; // Keep alive every 5 minutes (in milliseconds)
        this.redirectUrl = config.redirectUrl || '/auth/login';
        
        // State
        this.lastActivity = Date.now();
        this.checkTimer = null;
        this.keepAliveTimer = null;
        this.warningShown = false;
        this.isActive = false;
        this.countdownInterval = null;
        
        // Initialize
        this.init();
    }
    
    /**
     * Initialize session manager
     */
    async init() {
        try {
            // Get session config from server
            const config = await this.getSessionConfig();
            
            if (config.isLoggedIn) {
                this.sessionTimeout = config.timeout;
                this.isActive = true;
                
                // Setup activity tracking
                this.setupActivityTracking();
                
                // Start monitoring
                this.startMonitoring();
                
                // Start keep-alive
                this.startKeepAlive();
                
                console.log('Session Manager initialized');
            }
        } catch (error) {
            console.error('Session Manager initialization failed:', error);
        }
    }
    
    /**
     * Get session configuration from server
     */
    async getSessionConfig() {
        const response = await fetch(`${this.baseUrl}/auth/getConfig`);
        
        if (!response.ok) {
            throw new Error('Failed to get session config');
        }
        
        return await response.json();
    }
    
    /**
     * Setup activity tracking
     */
    setupActivityTracking() {
        const events = ['mousedown', 'keydown', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.updateActivity();
            }, true);
        });
    }
    
    /**
     * Update last activity timestamp
     */
    updateActivity() {
        this.lastActivity = Date.now();
        
        // Hide warning if shown
        if (this.warningShown) {
            this.hideWarning();
        }
    }
    
    /**
     * Start session monitoring
     */
    startMonitoring() {
        // Check session status periodically
        this.checkTimer = setInterval(() => {
            this.checkSession();
        }, this.checkInterval);
    }
    
    /**
     * Stop session monitoring
     */
    stopMonitoring() {
        if (this.checkTimer) {
            clearInterval(this.checkTimer);
            this.checkTimer = null;
        }
    }
    
    /**
     * Start keep-alive timer
     */
    startKeepAlive() {
        this.keepAliveTimer = setInterval(() => {
            this.sendKeepAlive();
        }, this.keepAliveInterval);
    }
    
    /**
     * Stop keep-alive timer
     */
    stopKeepAlive() {
        if (this.keepAliveTimer) {
            clearInterval(this.keepAliveTimer);
            this.keepAliveTimer = null;
        }
    }
    
    /**
     * Check session status
     */
    async checkSession() {
        try {
            const response = await fetch(`${this.baseUrl}/auth/checkSession`);
            const data = await response.json();
            
            if (!data.isLoggedIn) {
                this.handleTimeout();
                return;
            }
            
            // remaining is in SECONDS from server
            const remaining = data.remaining;
            
            // Show warning if time is running out (compare seconds to seconds)
            if (remaining <= this.warningTime && remaining > 0 && !this.warningShown) {
                this.showWarning(remaining);
            }
            
            // Auto logout if timed out
            if (remaining <= 0) {
                this.handleTimeout();
            }
            
        } catch (error) {
            console.error('Session check failed:', error);
        }
    }
    
    /**
     * Send keep-alive ping to server
     */
    async sendKeepAlive() {
        try {
            const response = await fetch(`${this.baseUrl}/auth/keepAlive`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (!data.success) {
                console.warn('Keep-alive failed');
            } else {
                console.log('Keep-alive sent successfully');
            }
            
        } catch (error) {
            console.error('Keep-alive error:', error);
        }
    }
    
    /**
     * Show timeout warning
     */
    showWarning(remainingSeconds) {
        this.warningShown = true;
        
        // Create warning modal if it doesn't exist
        if (!document.getElementById('sessionWarningModal')) {
            this.createWarningModal();
        }
        
        // Update warning message
        this.updateWarningMessage(remainingSeconds);
        
        // Show modal
        const modalElement = document.getElementById('sessionWarningModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        // Start countdown
        this.startWarningCountdown(remainingSeconds);
    }
    
    /**
     * Update warning message
     */
    updateWarningMessage(remainingSeconds) {
        const minutes = Math.floor(remainingSeconds / 60);
        const seconds = Math.floor(remainingSeconds % 60);
        
        const message = `Your session will expire in ${minutes} minute${minutes !== 1 ? 's' : ''} ${seconds} second${seconds !== 1 ? 's' : ''}. Any unsaved changes will be lost.`;
        
        const messageElement = document.getElementById('sessionWarningMessage');
        if (messageElement) {
            messageElement.textContent = message;
        }
    }
    
    /**
     * Start warning countdown
     */
    startWarningCountdown(initialSeconds) {
        // Clear any existing countdown
        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
        }
        
        let remaining = initialSeconds;
        
        this.countdownInterval = setInterval(() => {
            remaining--;
            
            if (remaining <= 0) {
                clearInterval(this.countdownInterval);
                this.countdownInterval = null;
                this.handleTimeout();
                return;
            }
            
            // Update message
            this.updateWarningMessage(remaining);
            
        }, 1000);
    }
    
    /**
     * Hide warning
     */
    hideWarning() {
        this.warningShown = false;
        
        // Clear countdown
        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
            this.countdownInterval = null;
        }
        
        const modalElement = document.getElementById('sessionWarningModal');
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        }
    }
    
    /**
     * Create warning modal
     */
    createWarningModal() {
        const modalHtml = `
            <div class="modal fade" id="sessionWarningModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Session Timeout Warning
                            </h5>
                        </div>
                        <div class="modal-body">
                            <p id="sessionWarningMessage" class="mb-0"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="sessionManager.logout()">
                                Logout Now
                            </button>
                            <button type="button" class="btn btn-primary" onclick="sessionManager.extendSession()">
                                Continue Working
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }
    
    /**
     * Extend session
     */
    async extendSession() {
        try {
            await this.sendKeepAlive();
            this.updateActivity();
            this.hideWarning();
            
            // Show success message
            this.showNotification('Session extended successfully', 'success');
            
        } catch (error) {
            console.error('Failed to extend session:', error);
            this.showNotification('Failed to extend session', 'danger');
        }
    }
    
    /**
     * Handle session timeout
     */
    handleTimeout() {
        this.stopMonitoring();
        this.stopKeepAlive();
        
        // Hide warning modal if shown
        this.hideWarning();
        
        // Show timeout message
        this.showNotification('Your session has expired. Redirecting to login...', 'warning');
        
        // Redirect to login page after 2 seconds
        setTimeout(() => {
            window.location.href = this.redirectUrl;
        }, 2000);
    }
    
    /**
     * Logout user
     */
    logout() {
        this.stopMonitoring();
        this.stopKeepAlive();
        this.hideWarning();
        window.location.href = `${this.baseUrl}/auth/logout`;
    }
    
    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        // Create notification element if needed
        if (!document.getElementById('sessionNotification')) {
            const notificationHtml = `
                <div id="sessionNotification" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                    <div class="toast align-items-center border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body"></div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', notificationHtml);
        }
        
        const toastElement = document.querySelector('#sessionNotification .toast');
        const toastBody = toastElement.querySelector('.toast-body');
        
        // Set message and style
        toastBody.textContent = message;
        toastElement.className = `toast align-items-center text-white bg-${type} border-0`;
        
        // Show toast
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
    }
    
    /**
     * Destroy session manager
     */
    destroy() {
        this.stopMonitoring();
        this.stopKeepAlive();
        this.hideWarning();
        this.isActive = false;
    }
}

// Initialize session manager when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Get base URL from the page
    const baseUrl = window.BASE_URL || '';
    
    // Initialize session manager
    window.sessionManager = new SessionManager({
        baseUrl: baseUrl,
        sessionTimeout: 1800,      // 30 minutes (in seconds)
        warningTime: 300,          // 5 minutes warning (in SECONDS, not milliseconds!)
        checkInterval: 60000,      // Check every minute (in milliseconds)
        keepAliveInterval: 300000, // Keep alive every 5 minutes (in milliseconds)
        redirectUrl: `${baseUrl}/auth/login`
    });
});