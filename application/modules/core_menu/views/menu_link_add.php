<h4>Add menu link</h4>

<?php echo form_open(current_url()) ;?>

<fieldset>
    <legend>Link options</legend>

    <label for="weight">Weight:</label>
    <select name="weight">
        <?php foreach ($max_link_count as $weight) :?>
            <?php $selected = ($weight == $next_weight) ? 'selected="selected"' : set_select('weight', $weight) ;?>
            <option value="<?php echo $weight ;?>" <?php echo $selected ;?>><?php echo $weight ;?></option>
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

</fieldset>

<fieldset>
    <legend>Link content</legend>

    <label for="title">Title:</label>
    <input type="text" name="title" class="<?php echo form_error_class('title') ;?>"
           value="<?php echo set_value('title') ;?>" />

    <label for="text">Text:</label>
    <input type="text" name="text" class="<?php echo form_error_class('text') ;?>"
           value="<?php echo set_value('text') ;?>" />

    <label for="link">Link:</label>
    <input type="text" name="link" class="<?php echo form_error_class('link') ;?>"
           value="<?php echo set_value('link') ;?>" />

</fieldset>

<fieldset>
    <legend>Link permissions</legend>

    <select name="permissions[]" multiple="multiple">
        <?php if (isset($all_permissions)) :?>
            <?php foreach ($all_permissions as $permission) :?>
            <option value="<?php echo $permission['permission'] ;?>" <?php echo set_select('permissions', $permission['permission']) ;?>>
                <?php echo $permission['permission'] ;?>
            </option>
            <?php endforeach ;?>
        <?php endif ;?>
    </select>

</fieldset>

<input type="submit" value="Add link" name="submit" />

<?php echo form_close() ;?>