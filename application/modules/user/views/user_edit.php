<?php $this->load->helper('form') ;?>
<?php $class = '' ;?>
<?php $class = (validation_errors()) ? $class = 'error' : $class = '' ;?>
<div class="twelve columns">
    <h4>User Edit</h4>
    <?php if (validation_errors()) :?>
        <div class="ten columns centered alert-box secondary">
            <?php echo validation_errors() ;?>
            <a href="" class="close">&times;</a>
        </div>
    <?php endif ;?>

    <?php echo form_open('user/edit') ;?>

    <input type="hidden" name="id" value="<?php echo (isset($user)) ? $user->id : set_value('id') ;?>">

    <label for="username">Username:</label>
    <input class="<?php echo $class ;?>" type="text" name="username" value="<?php echo (isset($user)) ? $user->username : set_value('username') ;?>" />

    <label for="password">Password:</label>
    <input class="<?php echo $class ;?>" type="password" name="password" value="" autocomplete="off" />

    <label for="passconf">Confirm password:</label>
    <input class="<?php echo $class ;?>" type="password" name="passconf" value="" />

    <label for="email">Email:</label>
    <input class="<?php echo $class ;?>" type="text" name="email" value="<?php echo (isset($user)) ? $user->email : set_value('email') ;?>" />

    <?php
    echo form_submit('save', 'Save');
    if (isset($user))
        echo form_submit('delete', 'Delete');
    echo ' <a href="' . current_url() . '">Reset</a>';
    echo form_close();
    ?>
</div>
