<?php
    $salt = new stdClass();
    $salt->content = md5('md5_' . rand(0, 1000000));
    $salt->createdTime = TIME_NOW;
    $_SESSION['submit_salt'] = $salt;
?>
<div class="clear"></div>
<div id="problem-submit-block" class="problem-submit-block">
    <form action="<?php echo plugin_dir_url(__FILE__) ?>submit.php" method="POST" enctype="multipart-forms/data">
        <div class='left-block'>
            <input name="problems[0]" type="file" />
            <div class="clear"></div>
            <input name="problems[1]" type="file" />
            <div class="clear"></div>
            <input type='hidden' name='salt' value='<?php echo $salt->content ?>' />
        </div>
        <div class='right-block'>
            <input type="submit" value='Nộp Bài'/>
        </div>
    </form>
</div>