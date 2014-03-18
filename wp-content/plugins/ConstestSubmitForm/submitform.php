<?php
    $salt = md5('md5_' . rand(0, 1000000));
    $_SESSION['submit_salt'][$salt] = TIME_NOW;
?>
<div class="clear"></div>
<div id="problem-submit-block" class="problem-submit-block">
    <?php if (is_user_logged_in()) : ?>
        <form action="<?php echo plugin_dir_url(__FILE__) ?>submit.php" method="POST" enctype="multipart/form-data">
            <div class='left-block'>
                <input name="problems[]" type="file" />
                <div class="clear"></div>
                <input name="problems[]" type="file" />
                <div class="clear"></div>
                <input type='hidden' name='salt' value='<?php echo $salt ?>' />
            </div>
            <div class='right-block'>
                <input type="submit" value='Nộp Bài'/>
            </div>
        </form>
    <?php else: ?>
        <div class="msg">
            You must login to submit your result
        </div>
    <?php endif ?>
</div>