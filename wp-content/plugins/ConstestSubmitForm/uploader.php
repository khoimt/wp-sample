<?php

class ProblemUploader {
    private static $_whiteList = array('prob1.php', 'prob3.txt', 'prob2.php');
    private static $_user, $_uploadDir;
    private static $_submitTable = 'wp_submit';
    private static $_problemTable = 'wp_problem';
    const SUCCESS = 0;
    const ERROR = 1;

    public function __construct() {
        self::$_user = wp_get_current_user();
        self::$_uploadDir = wp_upload_dir();
    }

    public function process($name, $tmpName, $type) {
        if (!in_array($name, self::$_whiteList) || !is_uploaded_file($tmpName)) {
            return;
        }

        if (($filePath = $this->coppyUploading($name, $tmpName, $type)) != self::ERROR) {
            $this->insert2DB($name, $filePath);
        }

        return;
    }

    /**
     *
     * @param string $name
     * @param string $tmpName
     * @param string $type
     * @return string|int - return 1 if error, else return uploaded file path
     */
    public function coppyUploading($name, $tmpName, $type) {
        $userLogin = self::$_user->get('user_login');
        $baseDir = realpath(self::$_uploadDir['basedir'] . '/contest/user');
        $uploadDir = $baseDir . '/' . $userLogin;
        $newName = str_replace('.php', '.txt', $name);
        $targetName = $uploadDir . '/' . $newName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777);
        }

        chmod($uploadDir,0777);

        if (!move_uploaded_file($tmpName, $targetName)) {
            error_log('Upload error: ' . $userLogin . ':' . $name . ':' . $tmpName);
            return self::ERROR;
        }

        chmod($targetName,0777);
        return $targetName;
    }

    /**
     *
     * @param string $name
     * @param string $filePath
     */
    public function insert2DB ($name, $filePath) {
        $userLogin = self::$_user->get('user_login');
        $probName = substr($name, strlen($name) - 4);

        $this->deleteOldSubmition($probName, $userLogin);
        if ($this->insertSubmition($probName, $filePath, $userLogin) == self::ERROR) {
            error_log('Insert to db error: ' . $userLogin . ':' . $name . ':' . $filePath);
        }

        return self::SUCCESS;
    }

    /**
     *
     * @global wpdb $wpdb
     * @param string $probName
     * @param string $userLogin
     */
    public function insertSubmition($probName, $filePath, $userLogin) {
        global $wpdb;

        $problemInfo = $this->getProblem($probName);
        if (empty($problemInfo)) {
            return self::ERROR;
        }

        $insertedData = array(
            'user_name' => $userLogin,
            'prob_name' => $probName,
            'path' => $filePath,
            'score' => 0,
            'num_test' => $problemInfo->num_test,
            'test_result' => '',
            'status' => 'pending',
            'deleted' => 0
        );

        if ($wpdb->replace($insertedData)) {
            return self::SUCCESS;
        }
        return self::ERROR;
    }

    /**
     *
     * @global wpdb $wpdb
     * @param String $probName
     */
    public function getProblem($probName) {
        global $wpdb;

        $query = 'SELECT * FROM ' . self::$_problemTable
                    . ' WHERE prob_name = \'' . mysql_escape_string($probName) . '\''
                    . ' AND status=\'active\' LIMIT 1';
        return $wpdb->get_row($query);
    }

    /**
     *
     * @global wpdb $wpdb
     * @param string $probName
     * @param string $userLogin
     */
    public function deleteOldSubmition($probName, $userLogin) {
        global $wpdb;
        
        $updatedData = array(
                'deleted' => 1,
                'deleted_date' => TIME_NOW,
                'deleted_user' =>$userLogin,
            );
        
        $where = array(
            'user_name' => $userLogin,
            'prob_name' => $probName,
            'deleted' => 0,
        );

        $wpdb->update(self::$_submitTable, $updatedData, $where);
    }

    public function handleUploading() {
        $n = count ($_FILES['problems']['name']);
        $nameArr = $_FILES['problems']['name'];
        $tmpArr = $_FILES['problems']['tmp_name'];
        $typeArr = $_FILES['problems']['type'];
        for ($i = 0; $i < $n; $i++) {
            $this->process($nameArr[$i], $tmpArr[$i], $typeArr[$i]);
        }

        $redirectUrl = get_bloginfo('url') . '/ket-qua';
        wp_redirect($redirectUrl);
    }
}