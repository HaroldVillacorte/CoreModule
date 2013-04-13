<h4>User module settings</h4>

<?php echo form_open(current_url()) ;?>

<fieldset>
    <legend>Expire times</legend>

    <label for="user_activation_expire_limit">User activation email expire limit:</label>
    <input type="text" name="user_activation_expire_limit" class="<?php echo form_error_class('user_activation_expire_limit') ;?>"
           value="<?php echo (setting_get('user_activation_expire_limit')) ?
           setting_get('user_activation_expire_limit') : set_value('user_activation_expire_limit');?>" />

    <label for="user_forgotten_password_code_expire_limit">Forgotten password code expire limit:</label>
    <input type="text" name="user_forgotten_password_code_expire_limit" class="<?php echo form_error_class('user_forgotten_password_code_expire_limit') ;?>"
           value="<?php echo (setting_get('user_forgotten_password_code_expire_limit')) ?
           setting_get('user_forgotten_password_code_expire_limit') : set_value('user_forgotten_password_code_expire_limit');?>" />

</fieldset>

<fieldset>
    <legend>Persistent logged-in cookie</legend>

    <label for="user_persistent_cookie_name">Logged-in cookie name:</label>
    <input type="text" name="user_persistent_cookie_name" class="<?php echo form_error_class('user_persistent_cookie_name') ;?>"
       value="<?php echo (setting_get('user_persistent_cookie_name')) ?
       setting_get('user_persistent_cookie_name') : set_value('user_persistent_cookie_name');?>" />

    <label for="user_persistent_cookie_expire">Logged-in cookie expire time:</label>
    <input type="text" name="user_persistent_cookie_expire" class="<?php echo form_error_class('user_persistent_cookie_expire') ;?>"
       value="<?php echo (setting_get('user_persistent_cookie_expire')) ?
       setting_get('user_persistent_cookie_expire') : set_value('user_persistent_cookie_expire');?>" />

</fieldset>

<fieldset>
    <legend>Login attempts lockout rules</legend>

    <label for="user_login_attempts_max">Max login attempts:</label>
    <input type="text" name="user_login_attempts_max" class="<?php echo form_error_class('user_login_attempts_max') ;?>"
       value="<?php echo (setting_get('user_login_attempts_max')) ?
       setting_get('user_login_attempts_max') : set_value('user_login_attempts_max');?>" />

    <label for="user_login_attempts_time">Max login attempts time span:</label>
    <input type="text" name="user_login_attempts_time" class="<?php echo form_error_class('user_login_attempts_time') ;?>"
       value="<?php echo (setting_get('user_login_attempts_time')) ?
       setting_get('user_login_attempts_time') : set_value('user_login_attempts_time');?>" />

    <label for="user_login_attempts_lockout_time">User locked out time:</label>
    <input type="text" name="user_login_attempts_lockout_time" class="<?php echo form_error_class('user_login_attempts_lockout_time') ;?>"
       value="<?php echo (setting_get('user_login_attempts_lockout_time')) ?
       setting_get('user_login_attempts_lockout_time') : set_value('user_login_attempts_lockout_time');?>" />

</fieldset>

<input type="submit" value="Save" name="submit" />

<?php echo form_close() ;?>
