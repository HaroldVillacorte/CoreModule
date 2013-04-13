<?php echo form_open(current_url()) ;?>

<fieldset>
    <legend>Recover password</legend>

    <label for="email">Email:</label>
    <input class="<?php echo form_error_class('email') ;?>"
           type="text" name="email" value="<?php echo set_value('email') ;?>" />

</fieldset>

<input type="submit" value="Send login request" name="submit" />
<a href="<?php echo get_back_link() ;?>">Cancel</a>

<?php echo form_close() ;?>
