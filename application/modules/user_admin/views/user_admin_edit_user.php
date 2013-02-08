<div class="twelve columns">
  <h5>Edit user</h5>

  <?php if (validation_errors ()) : ?>
  <div class="ten columns centered alert-box secondary">
    <?php echo validation_errors (); ?>
    <a href="" class="close">&times;</a>
  </div>
  <?php endif;?>

  <?php echo form_open('user_admin/edit_user');?>

  <input type="hidden" name="id" value="<?php echo isset($user) ? $user->id : set_value ('id'); ?>" />

  <label for="username">Username:</label>
  <input class="<?php echo $this->core_library->form_error_class('username');?>" type="text" name="username"
         value="<?php echo isset($user) ? $user->username : set_value('username');?>" />

  <label for="email">Email:</label>
  <input class="<?php echo $this->core_library->form_error_class('email');?>" type="text" name="email"
         value="<?php echo isset($user) ? $user->email : set_value('email');?>" />

  <!-- Role form select -->
  <label class="<?php echo $this->core_library->form_error_class('protected_value');?>" for="role">Role:</label>
  <?php
  $role_select = array();
  foreach ($all_roles as $value) {
      $role_select[$value->id] = $value->role;
      if ($this->session->userdata('role') != 'super_user') {
          unset($role_select[1]);
      }
  }
  $role_selected = isset($user) ? $user->role_id : set_value('role');
  echo form_dropdown('role', $role_select, $role_selected);
  ?>
  <!-- // Role form select -->

  <!-- Protected form select -->
  <?php if ($this->session->userdata('role') == 'super_user') :?>
    <label class="<?php echo $this->core_library->form_error_class('protected_value');?>" for="protected_value">Protected:</label>
    <?php
    $selected = 0;
    if (isset($user)) {
      $selected = $user->protected;
    }
    else {
      $selected = set_value('protected_value');
    }
    echo form_dropdown('protected_value', array(1 => 'Yes', 0 => 'No'), $selected);
    ?>
  <?php endif ;?>
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