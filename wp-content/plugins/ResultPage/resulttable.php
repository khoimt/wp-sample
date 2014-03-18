<?php
    /**
     * @global wpdb $wpdb
     * @var wpdb
     */
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

    $totalQuery = 'SELECT user_name, SUM(score) AS score FROM wp_submit WHERE deleted=0 GROUP BY user_name';
    $maxQuery = 'SELECT SUM(max_score) AS max_score FROM wp_problem WHERE `status`=\'active\'';

    $dataArr = $wpdb->get_results($query, ARRAY_A);

    $totalArr = $wpdb->get_results($totalQuery, ARRAY_A);
    $scoreArr = array();
    foreach($totalArr as $value) {
        $scoreArr[$value['user_name']] = $value['score'];
    }

    $maxScoreRS = $wpdb->get_row($maxQuery);
    $maxScore = $maxScoreRS ? $maxScoreRS->max_score : 0;

    $fields = array(
        'User',
        'Email',
        'Problem',
        'Status',
        'Score Detail',
        'Score',
        'Total',
    );
?>

<div class="clear"></div>
<table id="result-table" class="result-table">
    <tr class="rstbl-header-row">
        <?php foreach($fields as $field): ?>
        <th class="rstbl-header"><?php echo $field ?></th>
        <?php endforeach;?>
    </tr>
    <?php $i = 1; ?>
    <?php foreach($dataArr as $row): ?>

    <tr class="rstbl-row">
        <td><?php echo $row['user_login'] ?></td>
        <td><?php echo $row['user_email'] ?></td>
        <td><?php echo $row['prob_name'] ?></td>
        <td><?php echo empty($row['status']) ? 'not submit' : $row['status']; ?></td>
        <td><?php echo $row['score_detail'] ?></td>
        <td><?php echo (int)$row['score'] . '/' . $row['max_score'] ?></td>
        <?php if ($i++ % 2): ?>
            <td rowspan="2"><?php echo ($scoreArr[$row['user_login']] ? $scoreArr[$row['user_login']] : 0). '/' . $maxScore ?></td>
        <?php endif;?>
    </tr>
    <?php endforeach; ?>
</table>

