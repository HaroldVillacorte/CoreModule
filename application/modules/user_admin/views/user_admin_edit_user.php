<div class="twelve columns">
  <h5>Edit user</h5>

  <?php if (validation_errors ()) : ?>
  <div class="ten columns centered alert-box secondary">
    <?php echo validation_errors (); ?>
    <a href="" class="close">&times;</a>
  </div>
  <?php endif;?>

  <?php echo form_open('user_admin/edit_user');?>

  <input type="hidden" name="id" value="<?php echo set_value('id', isset($user) ? $user->getId() : '' );?>" />

  <label for="username">Username:</label>
  <input class="<?php echo $this->core_functions->form_error_class('username');?>" type="text" name="username"
         value="<?php echo isset($user) ? $user->getUsername() : set_value('username');?>" />

  <label for="email">Email:</label>
  <input class="<?php echo $this->core_functions->form_error_class('email');?>" type="text" name="email"
         value="<?php echo isset($user) ? $user->getEmail() : set_value('email');?>" />

  <label for="first_name">First name:</label>
  <input class="<?php echo $this->core_functions->form_error_class('first_name');?>" type="text" name="first_name"
         value="<?php echo  isset($user) ? $user->getFirstname() : set_value('first_name');?>" />

  <label for="last_name">Last name:</label>
  <input class="<?php echo $this->core_functions->form_error_class('last_name');?>" type="text" name="last_name"
         value="<?php echo isset($user) ? $user->getLastname() : set_value('last_name');?>" />

  <label for="role">Role:</label>
  <input class="<?php echo $this->core_functions->form_error_class('role');?>" type="text" name="role"
         value="<?php echo isset($user)  ? $user->getRole() : set_value('role');?>" />

  <label class="<?php echo $this->core_functions->form_error_class('protected_value');?>" for="protected_value">Protected:</label>
  <?php
  $selected = FALSE;
  if (isset($user)) {
    $selected = ($user->getProtected()) ? TRUE : FALSE ;
  }
  else {
    $selected = set_value('protected_value');
  }
  echo form_dropdown('protected_value', array(TRUE => 'Yes', FALSE => 'No'), $selected);
  ?>

  <p style="margin-top:1em;">

    <?php echo form_submit('save', 'Save');?>

    <noscript>
      <a href="<?php echo base_url() . 'user_admin/users/' . $user_page;?>">Back to list</a>
    </noscript>
    <script>
      document.write(
       '<a id="ajax-back-button" href="back" ONCLICK="history.go(-1)">Back to list</a>'
      );
    </script>

  </p>
  <?php echo form_close();?>
</div>