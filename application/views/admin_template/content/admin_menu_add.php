<h4>Add menu</h4>

<?php echo form_open($this->core_menu_library->menu_add_uri) ;?>

<label for="menu_name">Menu name:</label>
<input type="text" name="menu_name" class="<?php echo form_error_class('menu_name') ;?>"
       value="<?php echo set_value('menu_name') ;?>" />

<label for="description">Description:</label>
<textarea name="description" class="<?php echo form_error_class('description') ;?>"/><?php echo set_value('description') ;?></textarea>

<input type="submit" value="Add menu" name="submit" />

<?php echo form_close() ;?>