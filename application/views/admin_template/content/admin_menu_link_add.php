<h4>Add menu link</h4>

<?php echo form_open($this->core_menu_library->menu_link_add_uri) ;?>

<label for="weight">Weight:</label>
<select name="weight">
    <?php foreach ($max_link_count as $weight) :?>
        <option value="<?php echo $weight ;?>" <?php echo set_select('weight', $weight) ;?>><?php echo $weight ;?></option>
    <?php endforeach;?>
</select>

<label for="parent_menu_id">Parent menu:</label>
<select name="parent_menu_id">
    <?php foreach ($menus as $menu) :?>
        <?php $selected = ($menu_id == $menu->id) ? 'selected="selected"' : set_select('parent_menu_id', $menu->id) ;?>
        <option value="<?php echo $menu->id ;?>" <?php echo $selected ;?>><?php echo $menu->menu_name ;?></option>
    <?php endforeach ;?>
</select>

<label for="child_menu_id">Child menu:</label>
<select name="child_menu_id">
    <option value=0 <?php echo set_select('child_menu_id', NULL) ;?>>None</option>
    <?php foreach ($menus as $menu) :?>
        <option value="<?php echo $menu->id ;?>" <?php echo set_select('child_menu_id', $menu->id) ;?>><?php echo $menu->menu_name ;?></option>
    <?php endforeach ;?>
</select>

<label for="external">External link:</label>
<input type="checkbox" name="external" value="1" <?php echo set_checkbox('external', 1) ;?> />

<label for="title">Title:</label>
<input type="text" name="title" class="<?php echo form_error_class('title') ;?>"
       value="<?php echo set_value('title') ;?>" />

<label for="text">Text:</label>
<input type="text" name="text" class="<?php echo form_error_class('text') ;?>"
       value="<?php echo set_value('text') ;?>" />

<label for="link">Link:</label>
<input type="text" name="link" class="<?php echo form_error_class('link') ;?>"
       value="<?php echo set_value('link') ;?>" />

<label for="permissions">Permissions:</label>
<input type="text" name="permissions" class="<?php echo form_error_class('permissions') ;?>"
       value="<?php echo set_value('permissions') ;?>" />

<input type="submit" value="Add link" name="submit" />

<?php echo form_close() ;?>