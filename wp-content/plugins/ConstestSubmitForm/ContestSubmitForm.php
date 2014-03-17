<?php
/**************************************************************************

Plugin Name:  ContestSubmit
Plugin URI:   https://github.com/khoimt/
Version:      1.0.0
Description:  create submit form in posts that have "problem" tag name
Author:       khoimt
Author URI:   

**************************************************************************/
    function insertSubmitForm($content) {
        if (!is_single() || !preg_match("<\!--SubmitForm-->", $content)) {
            return $content;
        }
        
        wp_register_style('submit-form', plugin_dir_url(__FILE__) . 'submit-form.css', array(), '1.0.0' );
		wp_enqueue_style( 'submit-form');

        $submitForm = getForm();
        return str_replace('<!--SubmitForm-->', $submitForm, $content);

    }

    function getForm() {
        ob_start();
        require_once dirname(__FILE__) . '/submitform.php';
        return ob_get_clean();
    }

    add_action('the_content', 'insertSubmitForm', 10);

?>