<?php
/**
 * Session Manager Class - FIXED VERSION
 * Handles all session operations with security features
 */
class SessionManager {
    // Session configuration - ADJUSTED TIMEOUTS
    private const SESSION_TIMEOUT = 3600; // 1 hour (was 30 min)
    private const SESSION_REGENERATE_TIME = 600; // 10 minutes (was 5 min)
    private const MAX_IDLE_TIME = 7200; // 2 hours (was 1 hour)
    
    /**
     * Initialize session with security settings
     */
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            // Session security settings - FIXED HTTPS CHECK
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            
            // Only use secure cookies if on HTTPS
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
                    || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
            ini_set('session.cookie_secure', $isHttps ? 1 : 0);
            
            ini_set('session.cookie_samesite', 'Lax'); // Changed from Strict
            
            // Set session lifetime
            ini_set('session.gc_maxlifetime', self::MAX_IDLE_TIME);
            ini_set('session.cookie_lifetime', 0); // Until browser closes
            
            session_start();
            
            // Initialize session security
            self::initSecurity();
            
            // Check session validity
            self::validateSession();
        }
    }
    
    /**
     * Initialize session security parameters
     */
    private static function initSecurity() {
        if (!isset($_SESSION['initiated'])) {
            session_regenerate_id(true);
            $_SESSION['initiated'] = true;
            $_SESSION['user_agent'] = self::getUserAgent();
            $_SESSION['ip_address'] = self::getIpAddress();
            $_SESSION['created_at'] = time();
            $_SESSION['last_activity'] = time();
            $_SESSION['last_regeneration'] = time();
        }
    }
    
    /**
     * Validate session security and timeout
     */
    private static function validateSession() {
        // Check if session exists
        if (!isset($_SESSION['initiated'])) {
            return;
        }
        
        // Validate user agent - RELAXED CHECK
        if (!self::validateUserAgent()) {
            // Log suspicious activity but don't destroy immediately
            error_log("Session user agent mismatch for session: " . session_id());
            // Only destroy if user is logged in and agent changed
            if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) {
                self::destroy();
                return;
            }
        }
        
        // Skip IP validation entirely (mobile users, VPN changes, etc.)
        // If you need it, enable validateIpAddress() method
        
        // Check session timeout
        if (self::isTimedOut()) {
            self::destroy();
            header('Location: /login.php?timeout=1');
            exit;
        }
        
        // Regenerate session ID periodically - WITH PROTECTION
        if (self::shouldRegenerateId() && !self::isAjaxRequest()) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
        
        // Update last activity
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Check if request is AJAX
     */
    private static function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Check if session is timed out
     */
    private static function isTimedOut() {
        if (!isset($_SESSION['last_activity'])) {
            return false; // Changed from true
        }
        
        // Only check timeout for logged-in users
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            return false;
        }
        
        $inactiveTime = time() - $_SESSION['last_activity'];
        
        // Check for session timeout
        if ($inactiveTime > self::SESSION_TIMEOUT) {
            error_log("Session timed out: " . session_id() . " (inactive for {$inactiveTime} seconds)");
            return true;
        }
        
        // Check for max idle time
        if (isset($_SESSION['created_at'])) {
            $totalTime = time() - $_SESSION['created_at'];
            if ($totalTime > self::MAX_IDLE_TIME) {
                error_log("Session max time exceeded: " . session_id() . " (total {$totalTime} seconds)");
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if session ID should be regenerated
     */
    private static function shouldRegenerateId() {
        if (!isset($_SESSION['last_regeneration'])) {
            return true;
        }
        
        return (time() - $_SESSION['last_regeneration']) > self::SESSION_REGENERATE_TIME;
    }
    
    /**
     * Validate user agent - RELAXED
     */
    private static function validateUserAgent() {
        if (!isset($_SESSION['user_agent'])) {
            return true;
        }
        
        // Allow minor user agent variations (browser updates, etc.)
        $currentUA = self::getUserAgent();
        $sessionUA = $_SESSION['user_agent'];
        
        // Extract major browser info only
        $currentBrowser = self::extractBrowserInfo($currentUA);
        $sessionBrowser = self::extractBrowserInfo($sessionUA);
        
        return $currentBrowser === $sessionBrowser;
    }
    
    /**
     * Extract major browser info from user agent
     */
    private static function extractBrowserInfo($userAgent) {
        // Extract browser name and major version
        if (preg_match('/(Chrome|Firefox|Safari|Edge|Opera)\/\d+/', $userAgent, $matches)) {
            return $matches[0];
        }
        return substr($userAgent, 0, 50); // Fallback
    }
    
    /**
     * Validate IP address
     */
    private static function validateIpAddress() {
        if (!isset($_SESSION['ip_address'])) {
            return true;
        }
        
        // Relaxed validation - allow IP changes (for mobile users, VPN, etc.)
        return true;
    }
    
    /**
     * Get user agent
     */
    private static function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    }
    
    /**
     * Get IP address
     */
    private static function getIpAddress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Get first IP in chain
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        }
    }
    
    /**
     * Set user session data after login
     */
    public static function setUserSession($userData) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_data'] = [
            'id'            => $userData['id'],
            'fullname'      => $userData['fullname'],
            'username'      => $userData['username'],
            'role_name'     => $userData['role_name'],
            'role_id'       => $userData['role_id'],
            'profile_image' => $userData['profile_image'] ?? null,
            'email'         => $userData['email'] ?? ''
        ];
        $_SESSION['is_logged_in'] = true;
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time(); // Reset activity timer
        
        // Regenerate session ID on login for security
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
        
        // Log successful login
        error_log("User logged in: " . $userData['username'] . " (ID: " . $userData['id'] . ")");
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['is_logged_in']) && 
               $_SESSION['is_logged_in'] === true && 
               isset($_SESSION['user_id']);
    }
    
    /**
     * Get user ID
     */
    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get user data
     */
    public static function getUserData($key = null) {
        if ($key === null) {
            return $_SESSION['user_data'] ?? [];
        }
        
        return $_SESSION['user_data'][$key] ?? null;
    }
    
    /**
     * Get remaining session time in seconds
     */
    public static function getRemainingTime() {
        if (!isset($_SESSION['last_activity'])) {
            return self::SESSION_TIMEOUT;
        }
        
        $elapsed = time() - $_SESSION['last_activity'];
        $remaining = self::SESSION_TIMEOUT - $elapsed;
        
        return max(0, $remaining);
    }
    
    /**
     * Update session activity (keep alive)
     */
    public static function keepAlive() {
        if (self::isLoggedIn()) {
            $_SESSION['last_activity'] = time();
            return [
                'success' => true,
                'remaining' => self::getRemainingTime(),
                'timeout' => self::SESSION_TIMEOUT
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Not logged in'
        ];
    }
    
    /**
     * Destroy session completely
     */
    public static function destroy() {
        // Log logout
        if (isset($_SESSION['user_data']['username'])) {
            error_log("User logged out: " . $_SESSION['user_data']['username']);
        }
        
        $_SESSION = [];
        
        // Delete session cookie
        if (isset($_COOKIE[session_name()])) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 3600,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        session_destroy();
    }
    
    /**
     * Set flash message
     */
    public static function setFlash($key, $message, $type = 'info') {
        $_SESSION['flash'][$key] = [
            'message' => $message,
            'type' => $type
        ];
    }
    
    /**
     * Get and clear flash message
     */
    public static function getFlash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $flash = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $flash;
        }
        
        return null;
    }
    
    /**
     * Get session timeout value (for JavaScript)
     */
    public static function getTimeout() {
        return self::SESSION_TIMEOUT;
    }
    
    /**
     * Get session configuration for JavaScript
     */
    public static function getConfig() {
        return [
            'timeout' => self::SESSION_TIMEOUT,
            'remaining' => self::getRemainingTime(),
            'isLoggedIn' => self::isLoggedIn(),
            'lastActivity' => $_SESSION['last_activity'] ?? null
        ];
    }
    
    /**
     * Debug session info (remove in production)
     */
    public static function getDebugInfo() {
        if (!isset($_SESSION['initiated'])) {
            return ['error' => 'Session not initiated'];
        }
        
        return [
            'session_id' => session_id(),
            'is_logged_in' => self::isLoggedIn(),
            'user_id' => self::getUserId(),
            'last_activity' => $_SESSION['last_activity'] ?? null,
            'last_activity_ago' => isset($_SESSION['last_activity']) ? (time() - $_SESSION['last_activity']) : null,
            'created_at' => $_SESSION['created_at'] ?? null,
            'session_age' => isset($_SESSION['created_at']) ? (time() - $_SESSION['created_at']) : null,
            'remaining_time' => self::getRemainingTime(),
            'user_agent_match' => self::validateUserAgent(),
            'is_https' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'cookie_params' => session_get_cookie_params()
        ];
    }
}