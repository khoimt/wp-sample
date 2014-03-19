<?php
require __DIR__ . '/wp-load.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

class Test {
    /**
     *
     * @global wpdb $wpdb
     */
    public static function testFunc() {
        global $wpdb;
        $query = 'SELECT * FROM wp_problem WHERE status="active" AND expired_time >= FROM_UNIXTIME(0)';

        $arr = $wpdb->get_results($query);
        $item = reset($arr);
        var_dump($arr);
    }
}

Test::testfunc();