<?php
/**
 * Application Core Helpers & URL Resolution
 * Project: COVID-19 Information and Awareness Website
 * 
 * Provides robust, environment-agnostic helpers for URL generation,
 * asset linking, redirect handling, and output sanitization.
 */

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(dirname(__DIR__, 2)));
}

if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'app');
}

/**
 * Calculate the relative path prefix from the currently executing script to project root.
 * 
 * @return string Relative prefix (e.g. '', '../', '../../', '../../../')
 */
function get_base_url() {
    $rootDir = realpath(dirname(__DIR__, 2));
    
    // Fallback if SCRIPT_FILENAME is not set (e.g., CLI environments)
    if (!isset($_SERVER['SCRIPT_FILENAME'])) {
        return '';
    }
    
    $scriptDir = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
    
    if ($rootDir === false || $scriptDir === false || $rootDir === $scriptDir) {
        return '';
    }
    
    // Normalize directory separators
    $rootDir = str_replace('\\', '/', $rootDir);
    $scriptDir = str_replace('\\', '/', $scriptDir);
    
    if (substr($scriptDir, 0, strlen($rootDir)) === $rootDir) {
        $relative = trim(substr($scriptDir, strlen($rootDir)), '/');
        if ($relative === '') {
            return '';
        }
        $segments = explode('/', $relative);
        return str_repeat('../', count($segments));
    }
    
    return '';
}

/**
 * Generate a relative application URL to a project page or file.
 * 
 * @param string $path Path relative to project root (e.g. 'public/pages/auth/login.php')
 * @return string Resolved relative URL
 */
function url($path = '') {
    $prefix = get_base_url();
    return $prefix . ltrim($path, '/');
}

/**
 * Generate a relative URL to a static asset in public/assets/.
 * 
 * @param string $assetPath Path inside public/assets/ (e.g. 'css/style.css', 'js/main.js')
 * @return string Resolved relative asset URL
 */
function asset($assetPath = '') {
    return url('public/assets/' . ltrim($assetPath, '/'));
}

/**
 * Safely escape string content for HTML output (XSS defense).
 * 
 * @param string|null $value Value to escape
 * @return string Escaped string
 */
function escape($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
