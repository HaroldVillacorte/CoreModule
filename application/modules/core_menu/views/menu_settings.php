<h4>Menu settings</h4>

<?php echo form_open(current_url()) ;?>

<fieldset>
    <legend>Administrative settings</legend>

    <label for="menu_link_maximum_weight" >Maximum link weight:</label>
    <input class="<?php echo form_error_class('menu_link_maximum_weight') ;?>"
           type="text" name="menu_link_maximum_weight"
           value="<?php echo (variable_get('menu_link_maximum_weight')) ?
           variable_get('menu_link_maximum_weight') : set_value('menu_link_maximum_weight') ;?>" />

    </fieldset>

<input type="submit" value="Save" name="submit" />

<?php echo form_close() ;?>
