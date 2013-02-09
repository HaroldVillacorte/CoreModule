<h4>Add Role</h4>

<?php echo form_open('user_admin/add_role') ;?>

<label for="role">Role:</label>
<input class="<?php echo $this->core_library->form_error_class('role') ;?>"
       type="text" name="role" value="<?php echo set_value('role') ;?>" />

<label for="description">Description:</label>
<textarea class="<?php echo $this->core_library->form_error_class('description') ;?>"
          name="description"><?php echo set_value('description') ;?></textarea>

<?php if ($this->session->userdata('role') == 'super_user') :?>
    <label for="protected_value">Protected:</label>
           <?php
           $options = array(
               1 => 'Yes',
               0 => 'No',
           );
           ?>
           <?php echo form_dropdown('protected_value', $options, set_value('protected_value')) ;?>
       <?php endif ;?>

<?php echo form_submit('save', 'Add role') ;?>

<?php echo form_close() ;?>
