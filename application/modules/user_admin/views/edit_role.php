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

<label for="role">Role:</label>
<input class="<?php echo $class; ?>" type="text" name="role" value="<?php echo set_value('role');?>" />

<?php echo form_submit('add', 'Add');?>

<?php echo form_close();?>
