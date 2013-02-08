<?php $class = ''; ?>
<?php $class = (validation_errors ()) ? 'error' : ''; ?>

<h4>Add Role</h4>

<?php if (validation_errors ()) : ?>
  <div class="ten columns centered alert-box secondary">
    <?php echo validation_errors (); ?>
    <a href="" class="close">&times;</a>
  </div>
<?php endif; ?>

<?php echo form_open('user_admin/edit_role');?>

<input type="hidden" name="id" value="<?php echo isset ($role) ? $role->id : set_value('id') ;?>" />

<label for="role">Role:</label>
<input class="<?php echo $class; ?>" type="text" name="role" value="<?php echo isset ($role) ? $role->role : set_value('role') ;?>" />

<label for="description">Description:</label>
<textarea class="<?php echo $this->core_library->form_error_class('description'); ?>"
       name="description"><?php echo isset($role) ? $role->description : set_value('description')?></textarea>

<?php if ($this->session->userdata('role') == 'super_user'):?>
    <label class="<?php echo $this->core_library->form_error_class('protected_value'); ?>"
           for="protected_value">Protected:</label>
    <?php
    $options = array(
        1 => 'Yes',
        0 => 'No',
    );
    $default_value = (isset($role->protected)) ? $role->protected : set_value('protected_value') ;
    ?>
    <?php echo form_dropdown('protected_value', $options, $default_value) ;?>
<?php endif;?>

<?php echo form_submit('save', 'Save');?>

<?php echo form_close();?>
