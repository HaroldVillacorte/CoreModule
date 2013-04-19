<h4>Edit page</h4>

<p class="panel">
    Page title: <?php echo (isset($page->title)) ? $page->title : set_value('title') ;?></br>
    Author: <?php echo (isset($page->author)) ? $page->author : set_value('author') ;?></br>
    Post date and time: <?php echo (isset($page->created)) ? $page->created : set_value('created') ;?></br>
    Last edited by <strong><em><?php echo (isset($page->last_edit_username)) ?
    $page->last_edit_username : set_value('last_edit_username') ;?></em></strong> on <strong><em><?php echo (isset($page->last_edit)) ?
    $page->last_edit : set_value('last_edit') ;?></br></em></strong>
</p>

<?php echo form_open(current_url()) ;?>

<input type="hidden" name="id" value="<?php echo (isset($page->id)) ? $page->id : set_value('id') ;?>" />

<fieldset>
    <legend>Category</legend>

    <?php if (!empty($categories)) :?>
        <?php foreach ($categories as $category) :?>
    <label class="<?php echo form_error_class('category') ;?>"><?php echo $category->name ;?></label>
            <?php $checked = (isset($page->category) && $page->category == $category->id) ? 'checked="checked"' : set_radio('category', $category->id) ;?>
            <input type="radio" name="category" value="<?php echo $category->id ;?>" <?php echo $checked ;?> />
        <?php endforeach ;?>
    <?php endif ;?>

</fieldset>

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
    <legend>Permissions</legend>

    <select name="permissions[]" multiple="multiple">
        <?php if (isset($all_permissions)) :?>
            <?php foreach ($all_permissions as $permission) :?>
            <?php $selected = (isset($page->permissions) && strstr($page->permissions, $permission['permission'])) ?
            'selected="selected"' : set_select('permissions', $permission['permission']) ;?>
            <option value="<?php echo $permission['permission'] ;?>" <?php echo $selected ;?>>
                <?php echo $permission['permission'] ;?>
            </option>
            <?php endforeach ;?>
        <?php endif ;?>
    </select>

</fieldset>

<fieldset>
    <legend>Slug and title</legend>

<label for="slug">Slug:</label>
<input type="text" name="slug" class="<?php echo form_error_class('slug') ;?>"
       value="<?php echo (isset($page->slug)) ? $page->slug : set_value('slug') ;?>" />

<label for="title">Title:</label>
<input type="text" name="title" class="<?php echo form_error_class('title') ;?>"
       value="<?php echo (isset($page->title)) ? $page->title : set_value('title') ;?>" />

</fieldset>

<fieldset>
    <legend>Body</legend>

<label for="body">Body:</label>
<textarea name="body" class="<?php echo form_error_class('body') ;?>" />
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

<a href="<?php echo get_back_link() ;?>">Cancel</a>

<?php echo form_close() ;?>