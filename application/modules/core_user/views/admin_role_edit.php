<h4>Edit role</h4>

<?php echo form_open(current_url()) ;?>

<input type="hidden" name="id" value="<?php echo (isset($role->id)) ? $role->id : set_value('id') ;?>" />

<fieldset>
    <legend>Name and description</legend>

    <label for="role">Role:</label>
    <input class="<?php echo form_error_class('role') ;?>"
           type="text" name="role" value="<?php echo (isset($role->role)) ? $role->role : set_value('role') ;?>" />

    <label for="description">Description:</label>
    <textarea class="<?php echo form_error_class('description') ;?>"
              name="description"><?php echo (isset($role->description)) ? $role->description : set_value('description') ;?></textarea>

    <?php if ($this->session->userdata('role') == 'super_user') :?>
        <label for="protected_value">Protected:</label>
        <select name="protected_value">
            <option value="1" <?php echo (isset($role->protected_value) && $role->protected_value == '1') ? 'selected="selected"' : set_select('protected_value', '1') ;?>>Yes</option>
            <option value="0" <?php echo (isset($role->protected_value) && $role->protected_value == '0') ? 'selected="selected"' : set_select('protected_value', '0') ;?>>No</option>
        </select>
    <?php endif ;?>

</fieldset>

<input type="submit" value="Save" name="save" />

<a href="<?php echo get_back_link() ;?>">Cancel</a>

<?php echo form_close() ;?>
