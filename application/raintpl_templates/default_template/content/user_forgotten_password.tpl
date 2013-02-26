<h4>Password Recovery</h4>

{function="form_open($CI->config->item('user_forgotten_password_uri'))"}

<label for="email">Email:</label>
<input class="{function="$CI->core_module_library->form_error_class('email')"}"
       type="text" name="email" value="{function="set_value('email')"}" />

{function="form_submit('submit', 'Send login request')"}

{function="form_close()"}
