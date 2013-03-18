<h4>Edit menu link</h4>

{function="form_open($CI->core_menu_library->menu_link_edit_uri)"}

<input type="hidden" name="id" value="{if="isset($menu_link->id)"}{$menu_link->id}{else}{function="set_value('id')"}{/if}" />

<label for="parent_menu_id">Parent menu:</label>
<select name="parent_menu_id">
    {loop="$menus"}
        {if="(set_value('parent_menu_id') == $value->id) || (isset($menu_link->parent_menu_id) && $value->id == $menu_link->parent_menu_id)"}
            {$selected="selected"}
            {else}
            {$selected=NULL}
        {/if}
        <option value="{$value->id}" {$selected}>{$value->menu_name}</option>
    {/loop}
</select>

<label for="external">External link:</label>
{if="(isset($menu_link->external) && $menu_link->external == 1) || set_value('external') == 1"}
    {$checked="checked"}
    {else}
    {$checked=NULL}
{/if}
<input type="checkbox" name="external" value="1" {$checked} />

<label for="title">Title:</label>
<input type="text" name="title" class="{function="$CI->core_module_library->form_error_class('title')"}"
       value="{if="isset($menu_link->title)"}{$menu_link->title}{else}{function="set_value('title')"}{/if}" />

<label for="text">Text:</label>
<input type="text" name="text" class="{function="$CI->core_module_library->form_error_class('text')"}"
       value="{if="isset($menu_link->text)"}{$menu_link->text}{else}{function="set_value('text')"}{/if}" />

<label for="link">Link:</label>
<input type="text" name="link" class="{function="$CI->core_module_library->form_error_class('link')"}"
       value="{if="isset($menu_link->link)"}{$menu_link->link}{else}{function="set_value('link')"}{/if}" />

<label for="permissions">Permissions:</label>
<input type="text" name="permissions" class="{function="$CI->core_module_library->form_error_class('permissions')"}"
       value="{if="isset($menu_link->permissions)"}{$menu_link->permissions}{else}{function="set_value('permissions')"}{/if}" />

{function="form_submit('submit', 'Save link')"}

{function="form_close()"}