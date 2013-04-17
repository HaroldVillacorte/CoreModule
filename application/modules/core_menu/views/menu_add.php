<h4>Add menu</h4>

<?php echo form_open(current_url()) ;?>

<fieldset>
    <legend>Name and description</legend>

    <label for="menu_name">Menu name:</label>
    <input type="text" name="menu_name" class="<?php echo form_error_class('menu_name') ;?>"
           value="<?php echo set_value('menu_name') ;?>" />

    <label for="menu_classes">Menu ul classes:</label>
    <input type="text" name="menu_classes" class="<?php echo form_error_class('menu_classes') ;?>"
           value="<?php echo set_value('menu_classes') ;?>" />

    <label for="description">Description:</label>
    <textarea name="description" class="<?php echo form_error_class('description') ;?>"/><?php echo set_value('description') ;?></textarea>

</fieldset>

<input type="submit" value="Add menu" name="submit" />

<?php echo form_close() ;?>