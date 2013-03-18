<h4>Add menu</h4>

{function="form_open($CI->core_menu_library->menu_add_uri)"}

<label for="menu_name">Menu name:</label>
<input type="text" name="menu_name" class="{function="$CI->core_module_library->form_error_class('menu_name')"}"
       value="{function="set_value('menu_name')"}" />

<label for="description">Description:</label>
<textarea name="description" class="{function="$CI->core_module_library->form_error_class('description')"}"/>{function="set_value('description')"}</textarea>

{function="form_submit('submit', 'Add menu')"}

{function="form_close()"}