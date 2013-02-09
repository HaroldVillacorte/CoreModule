<!-- Flashdata messages -->
<?php if (isset($message_success)) :?>
    <div class="alert-box success">
        <?php echo $message_success ;?>
        <a href="" class="close">&times;</a>
    </div>
<?php endif ;?>
<?php if (isset($message_error)) :?>
    <div class="alert-box alert">
        <?php echo $message_error ;?>
        <a href="" class="close">&times;</a>
    </div>
<?php endif ;?>
<?php if (isset($message_notice)) :?>
    <div class="alert-box">
        <?php echo $message_notice ;?>
        <a href="" class="close">&times;</a>
    </div>
<?php endif ;?>

<!-- Form error messages -->
<?php if (isset($validation_errors)) :?>
    <div class="alert-box secondary">
        <?php echo $validation_errors ;?>
        <a href="" class="close">&times;</a>
    </div>
<?php endif ;?>
