<?php
    $fields = array(
        'User',  'Email', 'Score'
    );
    $dataArr = getDataArray($scoreDetailArr);
    $probArr = getProblemArray();
    $size = count(reset($dataArr));

    $fields = array_merge($fields, $probArr);
?>

<div class="clear"></div>
<table id="result-table" class="result-table">
    <tr class="rstbl-header-row">
        <?php foreach($fields as $field): ?>
        <th class="rstbl-header"><?php echo $field ?></th>
        <?php endforeach;?>
    </tr>
    
    <?php foreach($dataArr as $row): ?>

    <tr class="rstbl-row">
        <td class="result-cell"><?php echo $row['user'] ?></td>
        <td class="result-cell"><?php echo $row['user_email'] ?></td>
        <td class="result-cell"><?php echo $row['score'] . '/' . $row['max_score'] ?></td>
        <?php foreach($probArr as $proName) : ?>
        <td class="result-cell"><?php echo $row[$proName] ?></td>
        <?php endforeach; ?>
    <?php endforeach; ?>
</table>

<?php
    /**
     * @global wpdb $wpdb
     */
    function getDataArray(&$scoreDetailArr) {
        global $wpdb;
        $query = <<<__QUERY__
    SELECT
        a.user_login,
        a.display_name user_name,
        a.user_email,
        a.prob_name,
        a.max_score,
        b.score,
        b.status,
        b.score_detail
    FROM
        (SELECT * FROM wp_users, wp_problem) AS a
    LEFT JOIN wp_submit b
        ON a.user_login = b.user_name
            AND a.prob_name = b.prob_name
            AND b.deleted = 0
    ORDER BY
        a.user_login, a.prob_name
    LIMIT 200;
__QUERY__;

        $arr = $wpdb->get_results($query, ARRAY_A);
        $return = array();
        $item = array();
        $user = '';
        foreach ($arr as $value) {
            if ($user != $value['user_login']) {
                if (!empty($item)) {
                    $return[] = $item;
                }
                $item = array(
                    'user' => $value['user_login'],
                    'user_email' => $value['user_email'],
                    'score' => $value['score'],
                    'max_score' => $value['max_score'],
                );
                $user = $value['user_login'];
            } else {
                $item['score'] += $value['score'];
                $item['max_score'] += $value['max_score'];
            }

            $probName = $value['prob_name'];
            $status = $value['status'];
            $scoreDetail = $value['score_detail'];

            $item[$probName] = empty($status)
                                ? 'Not Submit'
                                : (empty($scoreDetail) ? $status : $scoreDetail);
        }

        if (!empty($item)) {
            $return[] = $item;
        }
        return $return;
    }

    /**
     *
     * @global wpdb $wpdb
     */
    function getProblemArray() {
        global $wpdb;
        $query = 'SELECT prob_name FROM wp_problem WHERE `status`=\'active\' ORDER BY prob_name;';
        return $wpdb->get_col($query);
    }

