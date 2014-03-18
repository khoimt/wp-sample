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
     return getResultTable();
}

function getResultTable() {
    ob_start();
    require_once realpath(dirname(__FILE__) . '/resulttable.php');
    return ob_get_clean();
}

add_shortcode('result_page', 'insertResultPage');


?>