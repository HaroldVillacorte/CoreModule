<h4>Add a user</h4>

<?php echo form_open(current_url()) ;?>

<fieldset>
    <legend>Account info</legend>

    <label for="username">Username:</label>
    <input class="<?php echo form_error_class('username') ;?>" type="text" name="username"
           value="<?php echo set_value('username') ;?>" />

    <label for="password">Password:</label>
    <input class="<?php echo form_error_class('password') ;?>"
           type="password" name="password" value="" autocomplete="off"/>

    <label for="passconf">Confirm password:</label>
    <input class="<?php echo form_error_class('passconf') ;?>"
           type="password" name="passconf" value="" autocomplete="off"/>

    <label for="email">Email:</label>
    <input class="<?php echo form_error_class('email') ;?>" type="text" name="email"
           value="<?php echo set_value('email') ;?>" />

</fieldset>

<fieldset>
    <legend>Role</legend>

    <?php foreach ($all_roles as $role) :?>
        <label for="<?php echo $role['id'] ;?>"><?php echo $role['role'];?>:</label>
        <input type="radio" name="role" value="<?php echo $role['id'] ;?>" <?php echo set_radio('role', $role['id']) ;?> />
    <?php endforeach ;?>

</fieldset>

<?php if ($this->session->userdata('role') == 'super_user') :?>
<fieldset>
    <legend>Protected</legend>

        <label for="protected_value">Protected:</label>
        <input type="checkbox" name="protected_value" value="1"
            <?php echo set_checkbox('protected_value', '1') ;?>/>

</fieldset>
<?php endif ;?>

<input type="submit" value="Save" name="save" />

<a href="<?php echo get_back_link() ;?>">Cancel</a>

</p>
<?php echo form_close() ;?>