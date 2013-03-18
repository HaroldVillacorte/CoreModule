<h4>Add Role</h4>

{function="form_open($CI->config->item('user_admin_role_add_uri'))"}

<label for="role">Role:</label>
<input class="{function="$CI->core_module_library->form_error_class('role')"}"
       type="text" name="role" value="{function="set_value('role')"}" />

<label for="description">Description:</label>
<textarea class="{function="$CI->core_module_library->form_error_class('description')"}"
          name="description">{function="set_value('description')"}</textarea>

{if="$CI->session->userdata('role') == 'super_user'"}
    <label for="protected_value">Protected:</label>
    {function="form_dropdown('protected_value', array(1 => 'Yes',0 => 'No'), set_value('protected_value'))"}
{/if}

{function="form_submit('save', 'Add role')"}

{function="form_close()"}
