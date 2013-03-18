<h4>Edit menu</h4>

{function="form_open($CI->core_menu_library->menu_edit_uri)"}

<input type="hidden" name="id" value="{if="isset($menu->id)"}{$menu->id}{else}{function="set_value('id')"}{/if}" />

<label for="menu_name">Menu name:</label>
<input type="text" name="menu_name" class="{function="$CI->core_module_library->form_error_class('menu_name')"}"
       value="{if="isset($menu->menu_name)"}{$menu->menu_name}{else}{function="set_value('menu_name')"}{/if}" />

<label for="description">Description:</label>
<textarea name="description" class="{function="$CI->core_module_library->form_error_class('description')"}"/>
{if="isset($menu->description)"}{$menu->description}{else}{function="set_value('description')"}{/if}
</textarea>

{function="form_submit('submit', 'Save menu')"}

{function="form_close()"}