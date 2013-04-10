<h4>User Edit</h4>

{function="form_open($CI->config->item('user_edit_uri'))"}

<input type="hidden" name="id" value="{if="$user"}{$user->id }{else}{function="set_value('id')"}{/if}">

<label for="username">Username:</label>
<input class="{function="$CI->core_module_library->form_error_class('username')"}"
       type="text" name="username" value="{if="$user"}{$user->username }{else}{function="set_value('username')"}{/if}" />

<label for="password">Password:</label>
<input class="{function="$CI->core_module_library->form_error_class('password')"}"
       type="password" name="password" value="" autocomplete="off" />

<label for="passconf">Confirm password:</label>
<input class="{function="$CI->core_module_library->form_error_class('passconf')"}"
       type="password" name="passconf" value="" />

<label for="email">Email:</label>
<input class="{function="$CI->core_module_library->form_error_class('email')"}"
       type="text" name="email" value="{if="$user"}{$user->email }{else}{function="set_value('email')"}{/if}" />

{function="form_submit('save', 'Save')"}

{if="$user"}{function="form_submit('delete', 'Delete')"}{/if}

<a href="{function="current_url()"}">Reset</a>

{function="form_close()"}

