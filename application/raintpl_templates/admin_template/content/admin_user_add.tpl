{function="form_open($CI->config->item('user_admin_user_add_uri'))"}

{function="form_fieldset('Add a user')"}

<label for="username">Username:</label>
<input class="{function="$CI->core_module_library->form_error_class('username')"}" type="text" name="username"
       value="{function="set_value('username')"}" />

<label for="password">Password:</label>
<input class="{function="$CI->core_module_library->form_error_class('password')"}"
       type="password" name="password" value="" autocomplete="off"/>

<label for="passconf">Confirm password:</label>
<input class="{function="$CI->core_module_library->form_error_class('passconf')"}"
       type="password" name="passconf" value="" autocomplete="off"/>

<label for="email">Email:</label>
<input class="{function="$CI->core_module_library->form_error_class('email')"}" type="text" name="email"
       value="{function="set_value('email')"}" />

{function="form_fieldset_close()"}

<!-- Role form fieldset: radios -->

{function="form_fieldset('Select a role')"}
    {loop="$all_roles"}
        <label for="{$value['id']}">{$value['role']}:</label>
        <input type="radio" name="role" value="{$value['id']}" />
    {/loop}
{function="form_fieldset_close()"}

<!-- Protected form select -->
{if="$CI->session->userdata('role') == 'super_user'"}
    <label for="protected_value">Protected:</label>
    {fucntion="form_dropdown('protected_value', array(FALSE => 'No', TRUE => 'Yes'), set_value('protected_value'))"}
{/if}


<p style="margin-top:1em;">

    {function="form_submit('save', 'Save')"}

    <noscript>
    <a href="{function="base_url()"}user_admin/users/{$user_page}">Back to list</a>
    </noscript>
    <script>
        document.write(
        '<a id="ajax-back-button" href="back" ONCLICK="history.go(-1)">Back to list</a>'
    );
    </script>

</p>
{function="form_close()"}