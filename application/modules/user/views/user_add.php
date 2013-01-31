<?php $this->load->helper ('form'); ?>
<?php $class = ''; ?>
<?php $class = (validation_errors ()) ? $class = 'error' : $class = ''; ?>
<div class="twelve columns">
  <h4>Add account</h4>
  <?php if (validation_errors ()) : ?>
  <div class="ten columns centered alert-box secondary">
    <?php echo validation_errors (); ?>
    <a href="" class="close">&times;</a>
  </div>
  <?php endif; ?>

  <?php echo form_open ('user/add'); ?>

  <input type="hidden" name="id" value="<?php echo set_value ('id'); ?>">

  <label for="username">Username:</label>
  <input class="<?php echo $class; ?>" type="text" name="username" value="<?php echo set_value ('username'); ?>" />

  <label for="password">Password:</label>
  <input class="<?php echo $class; ?>" type="password" name="password" value="" autocomplete="off" />

  <label for="passconf">Confirm password:</label>
  <input class="<?php echo $class; ?>" type="password" name="passconf" value="" />

  <label for="email">Email:</label>
  <input class="<?php echo $class; ?>" type="text" name="email" value="<?php echo set_value ('email'); ?>" />

  <label for="first_name">First name:</label>
  <input class="<?php echo $class; ?>" type="text" name="first_name" value="<?php echo set_value ('first_name'); ?>" />

  <label for="last_name">Last name:</label>
  <input class="<?php echo $class; ?>" type="text" name="last_name" value="<?php echo set_value ('last_name'); ?>" />

  <?php
  echo form_submit ('add', 'Add acccount');
  echo ' <a href="' . current_url () . '">Reset</a>';
  echo form_close ();
  ?>
</div>
