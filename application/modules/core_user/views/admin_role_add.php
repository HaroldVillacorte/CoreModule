<h4>Add Role</h4>

<?php echo form_open(current_url()) ;?>

<fieldset>
    <legend>Name and description</legend>

    <label for="role">Role:</label>
    <input class="<?php echo form_error_class('role') ;?>"
           type="text" name="role" value="<?php echo set_value('role') ;?>" />

    <label for="description">Description:</label>
    <textarea class="<?php echo form_error_class('description') ;?>"
              name="description"><?php echo set_value('description') ;?></textarea>

    <?php if ($this->session->userdata('role') == 'super_user') :?>
        <label for="protected_value">Protected:</label>
        <select name="protected_value">
            <option value="1" <?php echo set_select('protected_value', '1') ;?>>Yes</option>
            <option value="0" <?php echo set_select('protected_value', '0') ;?>>No</option>
        </select>
    <?php endif ;?>

</fieldset>

<input type="submit" value="Add role" name="save" />

<a href="<?php echo get_back_link() ;?>">Cancel</a>

<?php echo form_close() ;?>
