<h5>Edit user</h5>

<?php echo form_open(current_url()) ;?>

<input type="hidden" name="id" value="<?php echo (isset($user->id)) ? $user->id : set_value('id') ;?>" />

<fieldset>
    <legend>Account info</legend>

    <label for="username">Username:</label>
    <input class="<?php echo form_error_class('username') ;?>" type="text" name="username"
           value="<?php echo (isset($user->username)) ? $user->username : set_value('username') ;?>" />

    <label for="email">Email:</label>
    <input class="<?php echo form_error_class('email') ;?>" type="text" name="email"
           value="<?php echo (isset($user->email)) ? $user->email : set_value('email') ;?>" />

</fieldset>


<fieldset>
    <legend>Account options</legend>

    <?php foreach ($all_roles as $role) :?>
        <label for="<?php echo $role['id'] ;?>"><?php echo $role['role'] ;?>:</label>
        <input type="radio" name="role" value="<?php echo $role['id'] ;?>"
               <?php echo (isset($user) && $user->role == $role['role']) ? 'checked="checked"' : set_radio('role', $role['id']) ;?> />
    <?php endforeach ;?>

</fieldset>

<?php if ($this->session->userdata('role') == 'super_user') :?>
<fieldset>
    <legend>Protected</legend>

        <label for="protected_value">Protected:</label>
        <input type="checkbox" name="protected_value" value="1"
            <?php echo (isset($user->protected) && $user->protected == '1') ?
            'checked="checked"' : set_checkbox('protected_value', '1') ;?>/>

</fieldset>
<?php endif ;?>

<input type="submit" value="Save" name="save" />

<a href="<?php echo get_back_link() ;?>">Cancel</a>

</p>
<?php echo form_close() ;?>
