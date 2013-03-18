<h5>Edit user</h5>

{function="form_open($CI->config->item('user_admin_user_edit_uri'))"}

<input type="hidden" name="id" value="{if="isset($user)"}{$user->id}{else}{function="set_value('id')"}{/if}" />

<label for="username">Username:</label>
<input class="{function="$CI->core_module_library->form_error_class('username')"}" type="text" name="username"
       value="{if="isset($user)"}{$user->username}{else}{function="set_value('username')"}{/if}" />

<label for="email">Email:</label>
<input class="{function="$CI->core_module_library->form_error_class('email')"}" type="text" name="email"
       value="{if="isset($user)"}{$user->email}{else}{function="set_value('email')"}{/if}" />

<!-- Role form fieldset: radios -->
{function="form_fieldset('Select a role')"}
    {loop="$all_roles"}
        {if="isset($user) && $user->role == $value.role"}{$checked="checked"}{else}{$checked=NULL}{/if}
        <label for="{$value.id}">{$value.role}:</label>
        <input type="radio" name="role" value="{$value.id}" {$checked} />
    {/loop}
{function="form_fieldset_close()"}

<!-- Protected form select -->
{if="$CI->session->userdata('role') == 'super_user'"}
    <label for="protected_value">Protected:</label>
    {$selected="0"}
    {if="!isset($user)"}{$user=NULL}{/if}
    {$array=array($user,'protected','protected_value')}
    {function="form_dropdown('protected_value', array(0 => 'No', 1 => 'Yes'), $CI->core_module_library->determine_form_value($array))"}
{/if}
<!-- // Protected form select -->

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