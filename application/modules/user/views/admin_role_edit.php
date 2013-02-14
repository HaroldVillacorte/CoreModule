<h4>Add Role</h4>

<<<<<<< HEAD:application/modules/user_admin/views/user_admin_edit_role.php
<?php echo form_open('user_admin/edit_role') ;?>
=======
<?php echo form_open('user/admin_edit_role') ;?>
>>>>>>> 66a0b12:application/modules/user/views/admin_role_edit.php

<input type="hidden" name="id" value="<?php echo isset($role) ? $role->id : set_value('id') ;?>" />

<label for="role">Role:</label>
<<<<<<< HEAD:application/modules/user_admin/views/user_admin_edit_role.php
<input class="<?php echo $this->core_library->form_error_class('role') ;?>"
=======
<input class="<?php echo $this->core_module_library->form_error_class('role') ;?>"
>>>>>>> 66a0b12:application/modules/user/views/admin_role_edit.php
       type="text" name="role" value="<?php echo isset($role) ? $role->role : set_value('role') ;?>" />

<label for="description">Description:</label>
<textarea class="<?php echo $this->core_module_library->form_error_class('description') ;?>"
          name="description"><?php echo isset($role) ? $role->description : set_value('description') ;?></textarea>

<?php if ($this->session->userdata('role') == 'super_user') :?>
    <label for="protected_value">Protected:</label>
           <?php
           $options = array(
               1 => 'Yes',
               0 => 'No',
           );
           $default_value = (isset($role->protected)) ? $role->protected : set_value('protected_value');
           ?>
           <?php echo form_dropdown('protected_value', $options, $default_value) ;?>
       <?php endif ;?>

<?php echo form_submit('save', 'Save') ;?>

<?php echo form_close() ;?>
