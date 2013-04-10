<h4>User Edit</h4>

<?php echo form_open($this->config->item('user_edit_uri')) ;?>

<input type="hidden" name="id" value="<?php echo (isset($user)) ? $user->id : set_value('id') ;?>">

<label for="username">Username:</label>
<input class="<?php echo form_error_class('username') ;?>"
       type="text" name="username" value="<?php echo (isset($user)) ? $user->username : set_value('username') ;?>" />

<label for="password">Password:</label>
<input class="<?php echo form_error_class('password') ;?>"
       type="password" name="password" value="" autocomplete="off" />

<label for="passconf">Confirm password:</label>
<input class="<?php echo form_error_class('passconf') ;?>"
       type="password" name="passconf" value="" />

<label for="email">Email:</label>
<input class="<?php echo form_error_class('email') ;?>"
       type="text" name="email" value="<?php echo (isset($user)) ? $user->email : set_value('email') ;?>" />

<?php
echo form_submit('save', 'Save');
if (isset($user))
    echo form_submit('delete', 'Delete');
echo ' <a href="' . current_url() . '">Reset</a>';
echo form_close();
?>
