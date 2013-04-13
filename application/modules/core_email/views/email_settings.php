<h4>Email Settings</h4>

<?php echo form_open(current_url()) ;?>

<fieldset>
    <legend>Server:</legend>

<label for="core_email_Host" >Host:</label>
<input class="<?php echo form_error_class('core_email_Host') ;?>"
       type="text" name="core_email_Host"
       value="<?php echo (setting_get('core_email_Host')) ? setting_get('core_email_Host') : set_value('core_email_Host') ;?>" />

<label for="core_email_Port" >Port:</label>
<input class="<?php echo form_error_class('core_email_Port') ;?>"
       type="text" name="core_email_Port"
       value="<?php echo (setting_get('core_email_Port')) ? setting_get('core_email_Port') : set_value('core_email_Port') ;?>" />

<label for="core_email_SMTPAuth" >Authorization required:</label>
<input type="checkbox" name="core_email_SMTPAuth" value=1
       <?php echo (setting_get('core_email_SMTPAuth') && setting_get('core_email_SMTPAuth') == 1) ? 'checked="checked"' : set_checkbox('core_email_SMTPAuth', '1') ;?> />

<label for="core_email_SMTPSecure" >Security protocol:</label>
<input class="<?php echo form_error_class('core_email_SMTPSecure') ;?>"
       type="text" name="core_email_SMTPSecure"
       value="<?php echo (setting_get('core_email_SMTPSecure')) ? setting_get('core_email_SMTPSecure') : set_value('core_email_SMTPSecure') ;?>" />

</fieldset>

<fieldset>
    <legend>Login info:</legend>

<label for="core_email_Username" >Username:</label>
<input class="<?php echo form_error_class('core_email_Username') ;?>"
       type="text" name="core_email_Username"
       value="<?php echo (setting_get('core_email_Username')) ? setting_get('core_email_Username') : set_value('core_email_Username') ;?>" />

<label for="core_email_Password" >Password:</label>
<input class="<?php echo form_error_class('core_email_Password') ;?>"
       type="password" name="core_email_Password" value="" />

</fieldset>

<fieldset>
    <legend>Headers:</legend>

<label for="core_email_From" >From email:</label>
<input class="<?php echo form_error_class('core_email_From') ;?>"
       type="text" name="core_email_From"
       value="<?php echo (setting_get('core_email_From')) ? setting_get('core_email_From') : set_value('core_email_From') ;?>" />

<label for="core_email_FromName" >From name:</label>
<input class="<?php echo form_error_class('core_email_FromName') ;?>"
       type="text" name="core_email_FromName"
       value="<?php echo (setting_get('core_email_FromName')) ? setting_get('core_email_FromName') : set_value('core_email_FromName') ;?>" />

<label for="core_email_reply_to" >Reply-to email:</label>
<input class="<?php echo form_error_class('core_email_reply_to') ;?>"
       type="text" name="core_email_reply_to"
       value="<?php echo (setting_get('core_email_reply_to')) ? setting_get('core_email_reply_to') : set_value('core_email_reply_to') ;?>" />

<label for="core_email_reply_to_name" >Reply-to name:</label>
<input class="<?php echo form_error_class('core_email_reply_to_name') ;?>"
       type="text" name="core_email_reply_to_name"
       value="<?php echo (setting_get('core_email_reply_to_name')) ? setting_get('core_email_reply_to_name') : set_value('core_email_reply_to_name') ;?>" />

</fieldset>

<input type="submit" value="Save settings" name="submit" />
<input type="submit" value="Send a test email" name="test" />


<?php echo form_close() ;?>
