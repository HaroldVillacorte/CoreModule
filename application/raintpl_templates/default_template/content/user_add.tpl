<h4>Add account</h4>

    {function="form_open($CI->config->item('user_add_uri'))"}

    <label for="username">Username:</label>
    <input class="{function="$CI->core_module_library->form_error_class('username')"}"
           type="text" name="username" value="{function="set_value('username')"}" />

    <label for="password">Password:</label>
    <input class="{function="$CI->core_module_library->form_error_class('password')"}"
           type="password" name="password" value="" autocomplete="off" />

    <label for="passconf">Confirm password:</label>
    <input class="{function="$CI->core_module_library->form_error_class('passconf')"}"
           type="password" name="passconf" value="" />

    <label for="email">Email:</label>
    <input class="{function="$CI->core_module_library->form_error_class('email')"}"
           type="text" name="email" value="{function="set_value('email')"}" />

    {function="form_submit('add', 'Add acccount')"}

    <a href="{function="current_url()"}">Reset</a>

    {function="form_close()"}
</div>
