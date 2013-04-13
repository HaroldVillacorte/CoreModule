<h5><?php echo ucfirst($user->username) ;?>, are you sure you want to delete your account?</h5>
<p>
    <?php echo form_open(current_url()) ;?>

    <?php echo form_submit('delete', 'Yes, I hate you!') ;?> |
    <a href="<?php echo get_back_link() ;?>">Cancel</a>

    <?php echo form_close() ;?>

</p>
