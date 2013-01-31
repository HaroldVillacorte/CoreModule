<div class="twelve columns">
  <h5>Edit user</h5>

  <?php if (validation_errors ()) : ?>
  <div class="ten columns centered alert-box secondary">
    <?php echo validation_errors (); ?>
    <a href="" class="close">&times;</a>
  </div>
  <?php endif;?>

  <?php echo form_open('user_admin/edit_user');?>

  <input type="hidden" name="id" value="<?php echo set_value('id', isset($user) ? $user->id : '' );?>" />

  <label for="username">Username:</label>
  <input class="<?php echo $this->core_functions->form_error_class('username');?>" type="text" name="username"
         value="<?php echo isset($user) ? $user->username : set_value('username');?>" />

  <label for="email">Email:</label>
  <input class="<?php echo $this->core_functions->form_error_class('email');?>" type="text" name="email"
         value="<?php echo isset($user) ? $user->email : set_value('email');?>" />

  <label for="first_name">First name:</label>
  <input class="<?php echo $this->core_functions->form_error_class('first_name');?>" type="text" name="first_name"
         value="<?php echo  isset($user) ? $user->first_name : set_value('first_name');?>" />

  <label for="last_name">Last name:</label>
  <input class="<?php echo $this->core_functions->form_error_class('last_name');?>" type="text" name="last_name"
         value="<?php echo isset($user) ? $user->last_name : set_value('last_name');?>" />

  <!-- Role form select -->
  <label class="<?php echo $this->core_functions->form_error_class('protected_value');?>" for="role">Role:</label>
  <?php
  $role_select = array();
  foreach ($all_roles as $value) {
    $role_select[$value->role] = $value->role;
  }
  $role_selected = isset($user)  ? $user->role : set_value('role');
  echo form_dropdown('role', $role_select, $role_selected);
  ?>
  <!-- // Role form select -->

  <!-- Protected form select -->
  <label class="<?php echo $this->core_functions->form_error_class('protected_value');?>" for="protected_value">Protected:</label>
  <?php
  $selected = FALSE;
  if (isset($user)) {
    $selected = ($user->protected) ? TRUE : FALSE ;
  }
  else {
    $selected = set_value('protected_value');
  }
  echo form_dropdown('protected_value', array(TRUE => 'Yes', FALSE => 'No'), $selected);
  ?>
  <!-- // Protected form select -->

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