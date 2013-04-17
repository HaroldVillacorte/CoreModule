<h4>Edit menu</h4>

<?php echo form_open(current_url()) ;?>

<input type="hidden" name="id" value="<?php echo (isset($menu->id)) ? $menu->id : set_value('id') ;?>" />

<fieldset>
    <legend>Name and description</legend>

    <label for="menu_name">Menu name:</label>
    <input type="text" name="menu_name" class="<?php echo form_error_class('menu_name') ;?>"
           value="<?php echo (isset($menu->menu_name)) ? $menu->menu_name : set_value('menu_name') ;?>" />

    <label for="menu_classes">Menu ul classes:</label>
    <input type="text" name="menu_classes" class="<?php echo form_error_class('menu_classes') ;?>"
           value="<?php echo (isset($menu->menu_classes)) ? $menu->menu_classes : set_value('menu_classes') ;?>" />

    <label for="description">Description:</label>
    <?php $value = (isset($menu->description)) ? $menu->description : set_value('description') ;?>
    <textarea name="description" class="<?php echo form_error_class('description') ;?>"/><?php echo $value ;?></textarea>

</fieldset>

<input type="submit" value="Save menu" name="submit" />

<?php echo form_close() ;?>