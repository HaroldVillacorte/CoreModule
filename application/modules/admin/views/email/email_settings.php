<h4>Email Settings</h4>

<?php echo form_open('admin/email_settings') ;?>

<label for="core_email_Host" >Host:</label>
<input class="<?php echo form_error_class('core_email_Host') ;?>"
       type="text" name="core_email_Host" value="<?php echo (isset($settings->core_email_Host)) ?
       $settings->core_email_Host : set_value('core_email_Host') ;?>" />

<label for="core_email_Port" >Port:</label>
<input class="<?php echo form_error_class('core_email_Port') ;?>"
       type="text" name="core_email_Port" value="<?php echo (isset($settings->core_email_Port)) ?
       $settings->core_email_Port : set_value('core_email_Port') ;?>" />

<label for="core_email_SMTPAuth" >Authorization required:</label>
<input type="checkbox" name="core_email_SMTPAuth" value=1  <?php echo (isset($settings->core_email_SMTPAuth)
       && $settings->core_email_SMTPAuth == 1) ? 'checked="checked"' : '' ;?> />

<label for="core_email_SMTPSecure" >Security protocol:</label>
<input class="<?php echo form_error_class('core_email_SMTPSecure') ;?>"
       type="text" name="core_email_SMTPSecure" value="<?php echo (isset($settings->core_email_SMTPSecure)) ?
       $settings->core_email_SMTPSecure : set_value('core_email_SMTPSecure') ;?>" />

<label for="core_email_Username" >Username:</label>
<input class="<?php echo form_error_class('core_email_Username') ;?>"
       type="text" name="core_email_Username" value="<?php echo (isset($settings->core_email_Username)) ?
       $settings->core_email_Username : set_value('core_email_Username') ;?>" />

<label for="core_email_Password" >Password:</label>
<input class="<?php echo form_error_class('core_email_Password') ;?>"
       type="password" name="core_email_Password" value="" />

<label for="core_email_From" >From email:</label>
<input class="<?php echo form_error_class('core_email_From') ;?>"
       type="text" name="core_email_From" value="<?php echo (isset($settings->core_email_From)) ?
       $settings->core_email_From : set_value('core_email_From') ;?>" />

<label for="core_email_FromName" >From name:</label>
<input class="<?php echo form_error_class('core_email_FromName') ;?>"
       type="text" name="core_email_FromName" value="<?php echo (isset($settings->core_email_FromName)) ?
       $settings->core_email_FromName : set_value('core_email_FromName') ;?>" />

<label for="core_email_reply_to" >Reply-to email:</label>
<input class="<?php echo form_error_class('core_email_Freply_to') ;?>"
       type="text" name="core_email_reply_to" value="<?php echo (isset($settings->core_email_reply_to)) ?
       $settings->core_email_reply_to : set_value('core_email_reply_to') ;?>" />

<label for="core_email_reply_to_name" >Reply-to name:</label>
<input class="<?php echo form_error_class('core_email_Freply_to_name') ;?>"
       type="text" name="core_email_reply_to_name" value="<?php echo (isset($settings->core_email_reply_to_name)) ?
       $settings->core_email_reply_to_name : set_value('core_email_reply_to_name') ;?>" />

<?php echo form_submit('submit', 'Save settings') ;?>

<?php echo form_submit('test', 'Send a test email') ;?>

<?php echo form_close() ;?>
