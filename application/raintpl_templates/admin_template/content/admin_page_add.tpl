<h4>Add page</h4>

{function="form_open($CI->core_pages_library->page_add_uri)"}

<label for="is_front">Is the front page:</label>
{if="set_value('is_front')"}{$checked="checked"}{else}{$checked=NULL}{/if}
<input type="checkbox" name="is_front" value="1" {$checked} />

<label for="published">Published:</label>
{if="set_value('published')"}{$checked="checked"}{else}{$checked=NULL}{/if}
<input type="checkbox" name="published" value="1" {$checked} />

<label for="slug">Slug:</label>
<input type="text" name="slug" class="{function="$CI->core_module_library->form_error_class('slug')"}"
       value="{function="set_value('slug')"}" />

<label for="title">Title:</label>
<input type="text" name="title" class="{function="$CI->core_module_library->form_error_class('title')"}"
       value="{function="set_value('title')"}" />

<label for="teaser">Teaser:</label>
<input type="text" name="teaser" class="{function="$CI->core_module_library->form_error_class('teaser')"}"
       value="{function="set_value('teaser')"}" />

<label for="body">Body:</label>
<textarea name="body" class="{function="$CI->core_module_library->form_error_class('body')"}"/>{function="set_value('body')"}</textarea>

{function="form_submit('submit', 'Add page')"}

{function="form_close()"}