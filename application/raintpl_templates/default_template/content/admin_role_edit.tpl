<h4>Edit role</h4>

{function="form_open($CI->config->item('user_admin_role_edit_uri'))"}

<input type="hidden" name="id" value="{if="$role"}{$role->id}{else}{function="set_value('id')"}{/if}" />

<label for="role">Role:</label>
<input class="{function="$CI->core_module_library->form_error_class('role')"}"
       type="text" name="role" value="{if="$role"}{$role->role}{else}{function="set_value('role')"}{/if}" />

<label for="description">Description:</label>
<textarea class="{function="$CI->core_module_library->form_error_class('description')"}"
          name="description">{if="$role"}{$role->description}{else}{function="set_value('description')"}{/if}</textarea>

{if="$CI->session->userdata('role') == 'super_user'"}
    <label for="protected_value">Protected:</label>
    {$array=array($role, 'protected', 'protected_value')}
    {function="form_dropdown('protected_value', array(1 => 'Yes',0 => 'No'), $CI->core_module_library->determine_form_value($array))"}
{/if}

{function="form_submit('save', 'Save')"}

{function="form_close()"}
