<h4>Email Settings</h4>

<?php echo form_open(current_url()) ;?>

<fieldset>
    <legend>Server:</legend>

<label for="core_email_Host" >Host:</label>
<input class="<?php echo form_error_class('core_email_Host') ;?>"
       type="text" name="core_email_Host"
       value="<?php echo (variable_get('core_email_Host')) ? variable_get('core_email_Host') : set_value('core_email_Host') ;?>" />

<label for="core_email_Port" >Port:</label>
<input class="<?php echo form_error_class('core_email_Port') ;?>"
       type="text" name="core_email_Port"
       value="<?php echo (variable_get('core_email_Port')) ? variable_get('core_email_Port') : set_value('core_email_Port') ;?>" />

<label for="core_email_SMTPAuth" >Authorization required:</label>
<input type="checkbox" name="core_email_SMTPAuth" value=1
       <?php echo (variable_get('core_email_SMTPAuth') && variable_get('core_email_SMTPAuth') == 1) ? 'checked="checked"' : set_checkbox('core_email_SMTPAuth', '1') ;?> />

<label for="core_email_SMTPSecure" >Security protocol:</label>
<input class="<?php echo form_error_class('core_email_SMTPSecure') ;?>"
       type="text" name="core_email_SMTPSecure"
       value="<?php echo (variable_get('core_email_SMTPSecure')) ? variable_get('core_email_SMTPSecure') : set_value('core_email_SMTPSecure') ;?>" />

</fieldset>

<fieldset>
    <legend>Login info:</legend>

<label for="core_email_Username" >Username:</label>
<input class="<?php echo form_error_class('core_email_Username') ;?>"
       type="text" name="core_email_Username"
       value="<?php echo (variable_get('core_email_Username')) ? variable_get('core_email_Username') : set_value('core_email_Username') ;?>" />

<label for="core_email_Password" >Password:</label>
<input class="<?php echo form_error_class('core_email_Password') ;?>"
       type="password" name="core_email_Password" value="" />

</fieldset>

<fieldset>
    <legend>Headers:</legend>

<label for="core_email_From" >From email:</label>
<input class="<?php echo form_error_class('core_email_From') ;?>"
       type="text" name="core_email_From"
       value="<?php echo (variable_get('core_email_From')) ? variable_get('core_email_From') : set_value('core_email_From') ;?>" />

<label for="core_email_FromName" >From name:</label>
<input class="<?php echo form_error_class('core_email_FromName') ;?>"
       type="text" name="core_email_FromName"
       value="<?php echo (variable_get('core_email_FromName')) ? variable_get('core_email_FromName') : set_value('core_email_FromName') ;?>" />

<label for="core_email_reply_to" >Reply-to email:</label>
<input class="<?php echo form_error_class('core_email_reply_to') ;?>"
       type="text" name="core_email_reply_to"
       value="<?php echo (variable_get('core_email_reply_to')) ? variable_get('core_email_reply_to') : set_value('core_email_reply_to') ;?>" />

<label for="core_email_reply_to_name" >Reply-to name:</label>
<input class="<?php echo form_error_class('core_email_reply_to_name') ;?>"
       type="text" name="core_email_reply_to_name"
       value="<?php echo (variable_get('core_email_reply_to_name')) ? variable_get('core_email_reply_to_name') : set_value('core_email_reply_to_name') ;?>" />

</fieldset>

<fieldset>
    <legend>System:</legend>

    <label for="core_email_smtp_Timeout" >Smtp timeout:</label>
    <input class="<?php echo form_error_class('core_email_smtp_Timeout') ;?>"
           type="text" name="core_email_smtp_Timeout"
           value="<?php echo (variable_get('core_email_smtp_Timeout')) ? variable_get('core_email_smtp_Timeout') : set_value('core_email_smtp_Timeout') ;?>" />

    <label for="core_email_Priority" >Priority:</label>
    <input class="<?php echo form_error_class('core_email_Priority') ;?>"
           type="text" name="core_email_Priority"
           value="<?php echo (variable_get('core_email_Priority')) ? variable_get('core_email_Priority') : set_value('core_email_Priority') ;?>" />

    <label for="core_email_CharSet" >Charset:</label>
    <input class="<?php echo form_error_class('core_email_CharSet') ;?>"
           type="text" name="core_email_CharSet"
           value="<?php echo (variable_get('core_email_CharSet')) ? variable_get('core_email_CharSet') : set_value('core_email_CharSet') ;?>" />

    <label for="core_email_ContentType" >Content type:</label>
    <input class="<?php echo form_error_class('core_email_ContentType') ;?>"
           type="text" name="core_email_ContentType"
           value="<?php echo (variable_get('core_email_ContentType')) ? variable_get('core_email_ContentType') : set_value('core_email_ContentType') ;?>" />

    <label for="core_email_Encoding" >Encoding:</label>
    <input class="<?php echo form_error_class('core_email_Encoding') ;?>"
           type="text" name="core_email_Encoding"
           value="<?php echo (variable_get('core_email_Encoding')) ? variable_get('core_email_Encoding') : set_value('core_email_Encoding') ;?>" />

    <label for="core_email_Sendmail" >Path to sendmail:</label>
    <input class="<?php echo form_error_class('core_email_Sendmail') ;?>"
           type="text" name="core_email_Sendmail"
           value="<?php echo (variable_get('core_email_Sendmail')) ? variable_get('core_email_Sendmail') : set_value('core_email_Sendmail') ;?>" />

    <label for="core_email_Mailer" >Protocol:</label>
    <input class="<?php echo form_error_class('core_email_Mailer') ;?>"
           type="text" name="core_email_Mailer"
           value="<?php echo (variable_get('core_email_Mailer')) ? variable_get('core_email_Mailer') : set_value('core_email_Mailer') ;?>" />

</fieldset>

<input type="submit" value="Save settings" name="submit" />
<input type="submit" value="Send a test email" name="test" />


<?php echo form_close() ;?>
