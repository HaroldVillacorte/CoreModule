<h4>Password Recovery</h4>

<?php echo form_open($this->config->item('user_forgotten_password_uri')) ;?>

<label for="email">Email:</label>
<input class="<?php echo form_error_class('email') ;?>"
       type="text" name="email" value="<?php echo set_value('email') ;?>" />

<?php echo form_submit('submit', 'Send login request') ;?>

<?php echo form_close() ;?>
