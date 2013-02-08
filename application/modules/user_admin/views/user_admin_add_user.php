<div class="twelve columns">
    <h5>Add user</h5>

    <?php if (validation_errors()) :?>
        <div class="ten columns centered alert-box secondary">
            <?php echo validation_errors() ;?>
            <a href="" class="close">&times;</a>
        </div>
    <?php endif ;?>

    <?php echo form_open('user_admin/add_user') ;?>

    <label for="username">Username:</label>
    <input class="<?php echo $this->core_library->form_error_class('username') ;?>" type="text" name="username"
           value="<?php set_value('username') ;?>" />

    <label for="password">Password:</label>
    <input class="<?php echo $this->core_library->form_error_class('password') ;?>"
           type="password" name="password" value="" autocomplete="off"/>

    <label for="passconf">Confirm password:</label>
    <input class="<?php echo $this->core_library->form_error_class('passconf') ;?>"
           type="password" name="passconf" value="" autocomplete="off"/>

    <label for="email">Email:</label>
    <input class="<?php echo $this->core_library->form_error_class('email') ;?>" type="text" name="email"
           value="<?php echo set_value('email') ;?>" />

    <!-- Role form select -->
    <label class="<?php echo $this->core_library->form_error_class('protected_value') ;?>" for="role">Role:</label>
    <?php
    $role_select = array();
    foreach ($all_roles as $value) {
        $role_select[$value->id] = $value->role;
        if ($this->session->userdata('role') != 'super_user') {
            unset($role_select[1]);
        }
    }
    echo form_dropdown('role', $role_select, set_value('role'));
    ?>
    <!-- // Role form select -->

    <?php if ($this->session->userdata('role') == 'super_user') :?>
        <label class="<?php echo $this->core_library->form_error_class('protected_value') ;?>" for="protected_value">Protected:</label>
        <?php echo form_dropdown('protected_value', array(TRUE => 'Yes', FALSE => 'No'), set_value('protected_value')) ;?>
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
</div>