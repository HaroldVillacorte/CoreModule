<h5>Edit user</h5>

<?php echo form_open($this->config->item('user_admin_user_edit_uri')) ;?>

<input type="hidden" name="id" value="<?php echo (isset($user->id)) ? $user->id : set_value('id') ;?>" />

<label for="username">Username:</label>
<input class="<?php echo form_error_class('username') ;?>" type="text" name="username"
       value="<?php echo (isset($user->username)) ? $user->username : set_value('username') ;?>" />

<label for="email">Email:</label>
<input class="<?php echo form_error_class('email') ;?>" type="text" name="email"
       value="<?php echo (isset($user->email)) ? $user->email : set_value('email') ;?>" />

<!-- Role form fieldset: radios -->
<?php echo form_fieldset('Select a role') ;?>
    <?php foreach ($all_roles as $role) :?>
        <label for="<?php echo $role['id'] ;?>"><?php echo $role['role'] ;?>:</label>
        <input type="radio" name="role" value="<?php echo $role['id'] ;?>"
               <?php echo (isset($user) && $user->role == $role['role']) ? 'checked="checked"' : set_radio('role', $role['id']) ;?> />
    <?php endforeach ;?>
<?php echo form_fieldset_close() ;?>

<!-- Protected form select -->
<?php if ($this->session->userdata('role') == 'super_user') :?>
    <label for="protected_value">Protected:</label>
    <select name="protected_value">
        <option value="0" <?php echo (isset($user->protected_value) && $user->protected_value == '0') ?
        'selected="selected"' : set_select('protected_value', '0') ;?>>No</option>
        <option value="1" <?php echo (isset($user->protected_value) && $user->protected_value == '1') ?
        'selected="selected"' : set_select('protected_value', '1') ;?>>Yes</option>
    </select>
<?php endif ;?>
<!-- // Protected form select -->

<p style="margin-top:1em;">

    <input type="submit" value="Save" name="save" />

    <noscript>
    <a href="<?php echo base_url() ;?>user_admin/users/<?php echo $user_page ;?>">Back to list</a>
    </noscript>
    <script>
        document.write(
        '<a id="ajax-back-button" href="back" ONCLICK="history.go(-1)">Back to list</a>'
    );
    </script>

</p>
<?php echo form_close() ;?>
