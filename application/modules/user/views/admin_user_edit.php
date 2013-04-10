<h5>Edit user</h5>

<?php echo form_open($this->config->item('user_admin_user_edit_uri')) ;?>

<input type="hidden" name="id" value="<?php echo isset($user) ? $user->id : set_value('id') ;?>" />

<label for="username">Username:</label>
<input class="<?php echo form_error_class('username') ;?>" type="text" name="username"
       value="<?php echo isset($user) ? $user->username : set_value('username') ;?>" />

<label for="email">Email:</label>
<input class="<?php echo form_error_class('email') ;?>" type="text" name="email"
       value="<?php echo isset($user) ? $user->email : set_value('email') ;?>" />

<!-- Role form fieldset: radios -->
<?php
echo form_fieldset('Select a role');
foreach ($all_roles as $value)
{
    $checked = (isset($user) && $user->role == $value['role']) ? 'checked="checked"' : '';
    echo '<label for="' . $value['id'] . '">' . $value['role'] . ':</label>';
    echo '<input type="radio" name="role" value="' . $value['id'] . '"' . $checked .' />';
}
echo form_fieldset_close();
?>

<!-- Protected form select -->
<?php if ($this->session->userdata('role') == 'super_user') :?>
    <label for="protected_value">Protected:</label>
    <?php
    $selected = 0;
    if (isset($user)) {
        $selected = $user->protected;
    } else {
        $selected = set_value('protected_value');
    }
    echo form_dropdown('protected_value', array(0 => 'No', 1 => 'Yes'), $selected);
    ?>
<?php endif ;?>
<!-- // Protected form select -->

<p style="margin-top:1em;">

    <?php echo form_submit('save', 'Save') ;?>

    <noscript>
    <a href="<?php echo base_url() . 'user_admin/users/' . $user_page ;?>">Back to list</a>
    </noscript>
    <script>
        document.write(
        '<a id="ajax-back-button" href="back" ONCLICK="history.go(-1)">Back to list</a>'
    );
    </script>

</p>
<?php echo form_close() ;?>