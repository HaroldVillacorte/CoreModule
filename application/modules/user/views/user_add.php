<?php $this->load->helper('form') ;?>
<?php $class = ''; ?>
<?php $class = (validation_errors()) ? $class = 'error' : $class = '' ;?>
<div class="twelve columns">
    <h4>Add account</h4>
    <?php if (validation_errors()) :?>
        <div class="ten columns centered alert-box secondary">
            <?php echo validation_errors() ;?>
            <a href="" class="close">&times;</a>
        </div>
    <?php endif ;?>

    <?php echo form_open('user/add') ;?>

    <label for="username">Username:</label>
    <input class="<?php echo $class ;?>" type="text" name="username" value="<?php echo set_value('username') ;?>" />

    <label for="password">Password:</label>
    <input class="<?php echo $class ;?>" type="password" name="password" value="" autocomplete="off" />

    <label for="passconf">Confirm password:</label>
    <input class="<?php echo $class ;?>" type="password" name="passconf" value="" />

    <label for="email">Email:</label>
    <input class="<?php echo $class ;?>" type="text" name="email" value="<?php echo set_value('email') ;?>" />

    <?php
    echo form_submit('add', 'Add acccount');
    echo ' <a href="' . current_url() . '">Reset</a>';
    echo form_close();
    ?>
</div>
