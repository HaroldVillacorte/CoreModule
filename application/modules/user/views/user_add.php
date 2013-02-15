<h4>Add account</h4>

    <?php echo form_open($this->config->item('user_add_uri')) ;?>

    <label for="username">Username:</label>
    <input class="<?php echo $this->core_module_library->form_error_class('username') ;?>"
           type="text" name="username" value="<?php echo set_value('username') ;?>" />

    <label for="password">Password:</label>
    <input class="<?php echo $this->core_module_library->form_error_class('password') ;?>"
           type="password" name="password" value="" autocomplete="off" />

    <label for="passconf">Confirm password:</label>
    <input class="<?php echo $this->core_module_library->form_error_class('passconf') ;?>"
           type="password" name="passconf" value="" />

    <label for="email">Email:</label>
    <input class="<?php echo $this->core_module_library->form_error_class('email') ;?>"
           type="text" name="email" value="<?php echo set_value('email') ;?>" />

    <?php
    echo form_submit('add', 'Add acccount');
    echo ' <a href="' . current_url() . '">Reset</a>';
    echo form_close();
    ?>
</div>
