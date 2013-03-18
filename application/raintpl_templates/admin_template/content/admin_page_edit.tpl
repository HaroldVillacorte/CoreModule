<h4>Edit page</h4>

{function="form_open($CI->core_pages_library->page_edit_uri)"}

<input type="hidden" name="id" value="{if="isset($page->id)"}{$page->id}{else}{function="set_value('id')"}{/if}" />

<label for="is_front">Is the front page:</label>
{if="(isset($page->is_front) && $page->is_front == 1) || set_value('is_front')"}{$checked="checked"}{else}{$checked=NULL}{/if}
<input type="checkbox" name="is_front" value="1" {$checked} />

<label for="published">Published:</label>
{if="(isset($page->published) && $page->published == 1) || set_value('published')"}{$checked="checked"}{else}{$checked=NULL}{/if}
<input type="checkbox" name="published" value="1" {$checked} />

<label for="slug">Slug:</label>
<input type="text" name="slug" class="{function="$CI->core_module_library->form_error_class('slug')"}"
       value="{if="isset($page->slug)"}{$page->slug}{else}{function="set_value('slug')"}{/if}" />

<label for="title">Title:</label>
<input type="text" name="title" class="{function="$CI->core_module_library->form_error_class('title')"}"
       value="{if="isset($page->title)"}{$page->title}{else}{function="set_value('title')"}{/if}" />

<label for="teaser">Teaser:</label>
<input type="text" name="teaser" class="{function="$CI->core_module_library->form_error_class('teaser')"}"
       value="{if="isset($page->teaser)"}{$page->teaser}{else}{function="set_value('teaser')"}{/if}" />

<label for="body">Body:</label>
<textarea name="body" class="{function="$CI->core_module_library->form_error_class('body')"}"/>
{if="isset($page->body)"}{$page->body}{else}{function="set_value('body')"}{/if}</textarea>

{function="form_submit('submit', 'Save page')"}

{function="form_close()"}