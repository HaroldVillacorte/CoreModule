<h4>Add menu link</h4>

{function="form_open($CI->core_menu_library->menu_link_add_uri)"}

<label for="parent_menu_id">Parent menu:</label>
<select name="parent_menu_id">
    {loop="$menus"}
        {if="$menu_id == $value->id ||set_value('parent_menu_id') == $value->id"}{$selected="selected"}{else}{$selected=NULL}{/if}
        <option value="{$value->id}" {$selected}>{$value->menu_name}</option>
    {/loop}
</select>

<label for="external">External link:</label>
{if="set_value('external') == 1"}{$checked="checked"}{else}{$checked=NULL}{/if}
<input type="checkbox" name="external" value="1" {$checked} />

<label for="title">Title:</label>
<input type="text" name="title" class="{function="$CI->core_module_library->form_error_class('title')"}"
       value="{function="set_value('title')"}" />

<label for="text">Text:</label>
<input type="text" name="text" class="{function="$CI->core_module_library->form_error_class('text')"}"
       value="{function="set_value('text')"}" />

<label for="link">Link:</label>
<input type="text" name="link" class="{function="$CI->core_module_library->form_error_class('link')"}"
       value="{function="set_value('link')"}" />

<label for="permissions">Permissions:</label>
<input type="text" name="permissions" class="{function="$CI->core_module_library->form_error_class('permissions')"}"
       value="{function="set_value('permissions')"}" />

{function="form_submit('submit', 'Add link')"}

{function="form_close()"}