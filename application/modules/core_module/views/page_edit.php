<h4>Edit page</h4>

<?php echo form_open($this->core_module_library->page_edit_uri) ;?>

<input type="hidden" name="id" value="<?php echo (isset($page->id)) ? $page->id : set_value('id') ;?>" />

<fieldset>
    <legend>Options</legend>

<label for="is_front">Is the front page:</label>
<input type="checkbox" name="is_front" value="1"
    <?php echo (isset($page->is_front) && $page->is_front == 1) ? 'checked="checked"': set_checkbox('is_front', 1) ;?> />

<label for="published">Published:</label>
<input type="checkbox" name="published" value="1"
    <?php echo (isset($page->published) && $page->published == 1) ? 'checked="checked"': set_checkbox('published', 1) ;?> />

</fieldset>

<fieldset>
    <legend>Slug and title</legend>

<label for="slug">Slug:</label>
<input type="text" name="slug" class="<?php form_error_class('slug') ;?>"
       value="<?php echo (isset($page->slug)) ? $page->slug : set_value('slug') ;?>" />

<label for="title">Title:</label>
<input type="text" name="title" class="<?php form_error_class('title') ;?>"
       value="<?php echo (isset($page->title)) ? $page->title : set_value('title') ;?>" />

</fieldset>

<fieldset>
    <legend>Body</legend>

<label for="body">Body:</label>
<textarea name="body" class="<?php form_error_class('body') ;?>" />
<?php echo (isset($page->body)) ? $page->body : set_value('body') ;?></textarea>

</fieldset>

<fieldset>
    <legend>Template</legend>
    <?php foreach ($template_array as $template) :?>
        <?php $template_name = $template['name'] . '/' . $template['file'] ;?>
        <label for="template"><?php echo '<strong>' . $template['name'] . ':</strong> ' . $template['file'] ;?></label>
        <input type="radio" name="template" value="<?php echo $template_name ;?>"
            <?php echo (isset($page->template) && $page->template == $template_name) ? 'checked="checked"' : set_radio('template', $template_name) ;?> />
    <?php endforeach ;?>
</fieldset>

<input type="submit" value="Save page" name="submit" />

<?php echo form_close() ;?>