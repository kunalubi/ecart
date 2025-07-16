<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the frameworks
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter4.github.io/CodeIgniter4/
 */

if (!function_exists('getSubdomain')) {
    function getSubdomain() {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $host = strtolower($host);
        $host = preg_replace('/:\d+$/', '', $host);
        $parts = explode('.', $host);
        // Localhost: kunal.localhost => [kunal, localhost]
        if (count($parts) == 2 && $parts[1] === 'localhost') {
            return $parts[0];
        }
        return null;
    }
}
