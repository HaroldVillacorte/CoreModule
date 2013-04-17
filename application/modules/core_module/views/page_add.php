<h4>Add page</h4>

<?php echo form_open(current_url()) ;?>

<fieldset>
    <legend>Category</legend>

    <?php if (!empty($categories)) :?>
        <?php foreach ($categories as $category) :?>
    <label class="<?php echo form_error_class('category') ;?>"><?php echo $category->name ;?></label>
            <input type="radio" name="category" value="<?php echo $category->id ;?>"
                <?php echo set_radio('category', $category->id) ;?>/>
        <?php endforeach ;?>
    <?php endif ;?>

</fieldset>

<fieldset>
    <legend>Options</legend>

    <label for="is_front">Is the front page:</label>
    <input type="checkbox" name="is_front" value="1" <?php echo set_checkbox('is_front', 1) ;?> />

    <label for="published">Published:</label>
    <input type="checkbox" name="published" value="1" <?php echo set_checkbox('published', 1) ;?> />

</fieldset>

<fieldset>
    <legend>Slug and title</legend>

    <label for="slug">Slug:</label>
    <input type="text" name="slug" class="<?php echo form_error_class('slug') ;?>"
           value="<?php echo set_value('slug') ;?>" />

    <label for="title">Title:</label>
    <input type="text" name="title" class="<?php echo form_error_class('title') ;?>"
           value="<?php echo set_value('title') ;?>" />

</fieldset>

<fieldset>
    <legend>Body</legend>

    <label for="body">Body:</label>
    <textarea name="body" class="<?php echo form_error_class('body') ;?>"/><?php echo set_value('body') ;?></textarea>

</fieldset>

<fieldset>
    <legend>Template</legend>
    <?php foreach ($template_array as $template) :?>
        <?php $template_name = $template['name'] . '/' . $template['file'] ;?>
        <label for="template"><?php echo '<strong>' . $template['name'] . ':</strong> ' . $template['file'] ;?></label>
        <input type="radio" name="template" value="<?php echo $template_name ;?>" <?php echo set_radio('template', $template_name) ;?> />
    <?php endforeach ;?>
</fieldset>

<input type="submit" value="Add page" name="submit" />

<a href="<?php echo get_back_link() ;?>">Cancel</a>

<?php echo form_close() ;?>