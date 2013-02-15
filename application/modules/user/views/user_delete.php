<h5><?php echo ucfirst($user->username) ;?>, are you sure you want to delete your account?</h5>
<p>
    <?php echo form_open($this->config->item('user_delete_uri')) ;?>
    <?php echo form_submit('delete', 'Yes, I hate you!') ;?>
    <?php echo form_close() ;?>
    <a href="<?php echo base_url() . 'user/edit/' ;?>">Cancel</a>
</p>
