<!-- demonstration accounts -->
<p>
    <strong><em>demo/demo (authenticated) or admin/admin (admin)</em></strong>
</p>
<h4>User Login</h4>

{function="form_open($CI->config->item('user_login_uri'))"}

<label for="username">Username:</label>
<input class="{function="$CI->core_module_library->form_error_class('username')"}"
       type="text" name="username" value="" />

<label for="password">Password:</label>
<input class="{function="$CI->core_module_library->form_error_class('password')"}"
       type="password" name="password" value="" autocomplete="off" />

<label for="set_persistent_login">Remember me.</label>
<p>{function="form_checkbox('set_persistent_login', TRUE, set_value('set_persistent_login'))"}</p>

{function="form_submit('submit', 'Login')"}

<a href="{$user_add_url}">Create account</a> | <a href="{$user_user_forgotten_password_url}">Recover password</a>

{function="form_close()"}
