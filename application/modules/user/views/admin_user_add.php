<?php echo form_open($this->config->item('user_admin_user_add_uri')) ;?>

<?php echo form_fieldset('Add a user') ;?>
<label for="username">Username:</label>
<input class="<?php echo form_error_class('username') ;?>" type="text" name="username"
       value="<?php set_value('username') ;?>" />

<label for="password">Password:</label>
<input class="<?php echo form_error_class('password') ;?>"
       type="password" name="password" value="" autocomplete="off"/>

<label for="passconf">Confirm password:</label>
<input class="<?php echo form_error_class('passconf') ;?>"
       type="password" name="passconf" value="" autocomplete="off"/>

<label for="email">Email:</label>
<input class="<?php echo form_error_class('email') ;?>" type="text" name="email"
       value="<?php echo set_value('email') ;?>" />
<?php echo form_fieldset_close() ;?>

<!-- Role form fieldset: radios -->
<?php
echo form_fieldset('Select a role');
foreach ($all_roles as $value)
{
    echo '<label for="' . $value['id'] . '">' . $value['role'] . ':</label>';
    echo '<input type="radio" name="role" value="' . $value['id'] . '" />';
}
echo form_fieldset_close();
?>

<!-- Protected form select -->
<?php if ($this->session->userdata('role') == 'super_user') :?>
    <label for="protected_value">Protected:</label>
    <?php echo form_dropdown('protected_value', array(FALSE => 'No', TRUE => 'Yes'), set_value('protected_value')) ;?>
<?php endif ;?>


<p style="margin-top:1em;">

    <?php echo form_submit('save', 'Save') ;?>

    <noscript>
    <a href="<?php echo base_url() . 'user_admin/users/' . $user_page ; ?>">Back to list</a>
    </noscript>
    <script>
        document.write(
        '<a id="ajax-back-button" href="back" ONCLICK="history.go(-1)">Back to list</a>'
    );
    </script>

</p>
<?php echo form_close() ;?>