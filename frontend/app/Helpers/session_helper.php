<?php

if (!function_exists('save_session_and_redirect')) {
    /**
     * Force save session data before redirect
     */
    function save_session_and_redirect($url, $data = [])
    {
        // Set session data
        if (!empty($data)) {
            session()->set($data);
        }
        
        // Force commit session to disk
        session()->close();
        
        // Redirect
        header('Location: ' . site_url($url));
        exit;
    }
}
