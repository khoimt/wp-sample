<?php
/**************************************************************************

Plugin Name:  WPSession
Plugin URI:   https://github.com/khoimt/
Version:      1.0.0
Description:  Using wordpress session
Author:       khoimt
Author URI:   

**************************************************************************/
    function startSession() {
        if (!session_id()) {
            session_start();
        }
    }

    function stopSession() {
        session_destroy();
    }

    add_action('init', 'startSession', 1);
    add_action('wp_logout', 'stopSession');
    add_action('wp_login', 'stopSession');

?>