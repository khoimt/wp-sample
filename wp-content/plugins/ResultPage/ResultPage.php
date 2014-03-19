<?php
/**************************************************************************

Plugin Name:  ResultPage
Plugin URI:   https://github.com/khoimt/
Version:      1.0.0
Description:  create submit form in posts that have "problem" tag name
Author:       khoimt
Author URI:   

**************************************************************************/
function insertResultPage( $atts, $content, $name ) {
    wp_register_style('result-page', plugin_dir_url(__FILE__) . 'result-page.css', array(), '1.0.0' );
    wp_enqueue_style( 'result-page');
     return getResultTable();
}

function getResultTable() {
    ob_start();
    require_once realpath(dirname(__FILE__) . '/resulttable.php');
    return ob_get_clean();
}

add_shortcode('result_page', 'insertResultPage');

?>