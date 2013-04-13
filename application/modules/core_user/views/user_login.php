<?php echo form_open($this->config->item('user_login_uri')) ;?>

<fieldset>
    <legend>Login</legend>

    <label for="username">Username:</label>
    <input class="<?php echo form_error_class('username') ;?>"
           type="text" name="username" value="" />

    <label for="password">Password:</label>
    <input class="<?php echo form_error_class('password') ;?>"
           type="password" name="password" value="" autocomplete="off" />
</fieldset>

<label for="set_persistent_login">Remember me.</label>
<p><?php echo form_checkbox('set_persistent_login', TRUE, set_value('set_persistent_login')) ;?></p>

<?php echo form_submit('submit', 'Login') ;?>

<a href="<?php echo base_url() . $this->core_user_library->user_add_uri ;?>">Create account</a> |
<a href="<?php echo base_url() . $this->core_user_library->user_forgotten_password_uri ;?>">Recover password</a>

<?php echo form_close() ;?>
