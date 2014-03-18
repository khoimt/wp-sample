<?php

require_once(realpath(dirname(__FILE__) . '/../../../wp-load.php'));

$user = wp_get_current_user();
if ($user->get('user_login') !== 'keq9') return;

echo '<pre>';
ini_set('display_errors', 1);
error_reporting(E_ALL);

global $wpdb;
//$data = array(
//    'user_name' => 'keq9123',
//    'prob_name' => 'prob1',
//    'path' => '',
//    'score' => '0',
//    'num_test' => '10',
//    'test_result' => '************',
//    'status' => 'pending',
//    'deleted' => 0
//);
//
//echo "Insert: \n";
//var_dump($wpdb->replace('wp_submit', $data));


var_dump($wpdb->get_row('SELECT * FROM wp_problem WHERE 1 = 1 LIMIT 1'));