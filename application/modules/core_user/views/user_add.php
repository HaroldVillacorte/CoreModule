<?php echo form_open(current_url()) ;?>

<fieldset>
    <legend>Register</legend>

    <label for="username">Username:</label>
    <input class="<?php echo form_error_class('username') ;?>"
           type="text" name="username" value="<?php echo set_value('username') ;?>" />

    <label for="password">Password:</label>
    <input class="<?php echo form_error_class('password') ;?>"
           type="password" name="password" value="" autocomplete="off" />

    <label for="passconf">Confirm password:</label>
    <input class="<?php echo form_error_class('passconf') ;?>"
           type="password" name="passconf" value="" />

    <label for="email">Email:</label>
    <input class="<?php echo form_error_class('email') ;?>"
           type="text" name="email" value="<?php echo set_value('email') ;?>" />

</fieldset>

<input type="submit" value="Add account" name="add" />

<a href="<?php echo get_back_link() ;?>">Cancel</a> |
<a href="<?php echo current_url() ;?>">Reset</a>

<?php echo form_close() ;?>
