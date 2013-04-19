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
    <legend>Permissions</legend>
    <select name="permissions[]" multiple="TRUE">
        <?php foreach ($all_permissions as $permission) :?>
            <?php $selected = (isset($user->permissions) && strstr($user->permissions, $permission['permission'])) ?
            'selected="selected"' : set_select('permissions', $permission['id']) ;?>
            <option value="<?php echo $permission['id'] ;?>" <?php echo $selected ;?> />
                <?php echo $permission['permission'] ;?>
            </option>
        <?php endforeach ;?>
    </select>

</fieldset>

<?php if ($this->session->userdata('permission') == 'super_user') :?>
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
