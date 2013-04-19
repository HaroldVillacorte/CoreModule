<h4>Edit permission</h4>

<?php echo form_open(current_url()) ;?>

<input type="hidden" name="id" value="<?php echo (isset($permission->id)) ? $permission->id : set_value('id') ;?>" />

<fieldset>
    <legend>Name and description</legend>

    <label for="permission">permission:</label>
    <input class="<?php echo form_error_class('permission') ;?>"
           type="text" name="permission" value="<?php echo (isset($permission->permission)) ? $permission->permission : set_value('permission') ;?>" />

    <label for="description">Description:</label>
    <?php $value = (isset($permission->description)) ? $permission->description : set_value('description') ;?>
    <textarea class="<?php echo form_error_class('description') ;?>" name="description"><?php echo $value ;?></textarea>

    <?php if ($this->session->userdata('permission') == 'super_user') :?>
        <label for="protected_value">Protected:</label>
        <select name="protected_value">
            <option value="1" <?php echo (isset($permission->protected_value) && $permission->protected_value == '1') ? 'selected="selected"' : set_select('protected_value', '1') ;?>>Yes</option>
            <option value="0" <?php echo (isset($permission->protected_value) && $permission->protected_value == '0') ? 'selected="selected"' : set_select('protected_value', '0') ;?>>No</option>
        </select>
    <?php endif ;?>

</fieldset>

<input type="submit" value="Save" name="save" />

<a href="<?php echo get_back_link() ;?>">Cancel</a>

<?php echo form_close() ;?>
