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

<?php if (isset($role)):?>
<input type="hidden" name="id" value="<?php echo isset ($role) ? $role->id : set_value('id') ;?>" />
<?php endif;?>

<label for="role">Role:</label>
<input class="<?php echo $class; ?>" type="text" name="role" value="<?php echo isset ($role) ? $role->role : set_value('role') ;?>" />

<?php echo form_submit('save', 'Save');?>

<?php echo form_close();?>
