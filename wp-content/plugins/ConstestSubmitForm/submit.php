<?php

require_once(realpath(dirname(__FILE__) . '/../../../wp-load.php'));

if (!is_user_logged_in()
        || !array_key_exists('salt', $_POST)
        || !array_key_exists($_POST['salt'], $_SESSION['submit_salt'])) {
    wp_die('Lỗi: Session không hợp lệ, vui lòng thử lại hoặc liên hệ admin');
}

unset($_SESSION['submit_salt'][$_POST['salt']]);

require_once realpath(dirname(__FILE__) . '/uploader.php');
$ob = new ProblemUploader();
$ob->handleUploading();




