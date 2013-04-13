<?php echo form_open(current_url()) ;?>

<input type="hidden" name="id" value="<?php echo (isset($user->id)) ? $user->id : set_value('id') ;?>">

<fieldset>
    <legend>Edit account</legend>

    <label for="username">Username:</label>
    <input class="<?php echo form_error_class('username') ;?>"
           type="text" name="username" value="<?php echo (isset($user->username)) ? $user->username : set_value('username') ;?>" />

    <label for="password">Password:</label>
    <input class="<?php echo form_error_class('password') ;?>"
           type="password" name="password" value="" autocomplete="off" />

    <label for="passconf">Confirm password:</label>
    <input class="<?php echo form_error_class('passconf') ;?>"
           type="password" name="passconf" value="" />

    <label for="email">Email:</label>
    <input class="<?php echo form_error_class('email') ;?>"
           type="text" name="email" value="<?php echo (isset($user->username)) ? $user->username : set_value('email') ;?>" />

</fieldset>

<input type="submit" value="Save" name="save" />
<input type="submit" value="Delete" name="delete" />
<a href="<?php echo get_back_link() ;?>">Cancel</a> |
<a href="<?php echo current_url() ;?>">Reset</a>

<?php echo form_close() ;?>

