<h4>Edit category</h4>

<?php echo form_open(current_url()) ;?>

<input type="hidden" name="id" value="<?php echo (isset($category->id)) ? $category->id : set_value('id') ;?>" />

<fieldset>
    <legend>Category level</legend>

    <?php foreach ($levels as $level) :?>

    <label class="<?php echo form_error_class('level')?>" for="level"><?php echo $level->role ;?></label>
    <?php $checked = (isset($category->level) && $category->level == $level->id) ? 'checked="checked"' : set_radio('level', $level->id) ;?>
    <input type="radio" name="level" value="<?php echo $level->id ;?>" <?php echo $checked ;?>/>

    <?php endforeach ;?>

</fieldset>

<fieldset>
    <legend>Category name</legend>

    <label for="level">Name:</label>
    <input type="text" name="name" class="<?php echo form_error_class('name')?>"
           value="<?php echo (isset($category->name)) ? $category->name :set_value('name') ;?>" />

</fieldset>

<input type="submit" value="Save" name="submit" />

<?php echo form_close() ;?>
