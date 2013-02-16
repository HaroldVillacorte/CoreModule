<!-- demonstration accounts -->
<p>
    <strong><em>demo/demo (authenticated) or admin/admin (admin)</em></strong>
</p>
<h4>User Login</h4>

<?php echo form_open($this->config->item('user_login_uri')) ;?>

<label for="username">Username:</label>
<input class="<?php echo $this->core_module_library->form_error_class('username') ;?>"
       type="text" name="username" value="" />

<label for="password">Password:</label>
<input class="<?php echo $this->core_module_library->form_error_class('password') ;?>"
       type="password" name="password" value="" autocomplete="off" />

<label for="set_persistent_login">Remember me.</label>
<p><?php echo form_checkbox('set_persistent_login', TRUE, set_value('set_persistent_login')) ;?></p>

<?php echo form_submit('submit', 'Login') ;?>

<a href="<?php echo base_url() . 'user/add/' ;?>">Create account</a>

<?php echo form_close() ;?>
