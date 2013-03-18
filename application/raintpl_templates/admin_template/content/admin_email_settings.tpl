<h4>Email Settings</h4>

{function="form_open('admin/email_settings')"}

<label for="core_email_Host" >Host:</label>
<input class="{function="$CI->core_module_library->form_error_class('core_email_Host')"}"
       type="text" name="core_email_Host"
       value="{if="isset($settings->core_email_Host)"}{$settings->core_email_Host}{else}{function="set_value('core_email_Host')"}{/if}" />

<label for="core_email_Port" >Port:</label>
<input class="{function="$CI->core_module_library->form_error_class('core_email_Port')"}"
       type="text" name="core_email_Port"
       value="{if="isset($settings->core_email_Port)"}{$settings->core_email_Port}{else}{function="set_value('core_email_Port')"}{/if}" />

<label for="core_email_SMTPAuth" >Authorization required:</label>
<input type="checkbox" name="core_email_SMTPAuth" value=1
       {if="isset($settings->core_email_SMTPAuth) && $settings->core_email_SMTPAuth == 1"}{$checked="checked"}{else}{$checked=NULL}{/if} {$checked} />

<label for="core_email_SMTPSecure" >Security protocol:</label>
<input class="{function="$CI->core_module_library->form_error_class('core_email_SMTPSecure')"}"
       type="text" name="core_email_SMTPSecure"
       value="{if="isset($settings->core_email_SMTPSecure)"}{$settings->core_email_SMTPSecure}{else}{function="set_value('core_email_SMTPSecure')"}{/if}" />

<label for="core_email_Username" >Username:</label>
<input class="{function="$CI->core_module_library->form_error_class('core_email_Username')"}"
       type="text" name="core_email_Username"
       value="{if="isset($settings->core_email_Username)"}{$settings->core_email_Username}{else}{function="set_value('core_email_Username')"}{/if}" />

<label for="core_email_Password" >Password:</label>
<input class="{function="$CI->core_module_library->form_error_class('core_email_Password')"}"
       type="password" name="core_email_Password" value="" />

<label for="core_email_From" >From email:</label>
<input class="{function="$CI->core_module_library->form_error_class('core_email_From')"}"
       type="text" name="core_email_From"
       value="{if="isset($settings->core_email_From)"}{$settings->core_email_From}{else}{function="set_value('core_email_From')"}{/if}" />

<label for="core_email_FromName" >From name:</label>
<input class="{function="$CI->core_module_library->form_error_class('core_email_FromName')"}"
       type="text" name="core_email_FromName"
       value="{if="isset($settings->core_email_FromName)"}{$settings->core_email_FromName}{else}{function="set_value('core_email_FromName')"}{/if}" />

<label for="core_email_reply_to" >Reply-to email:</label>
<input class="{function="$CI->core_module_library->form_error_class('core_email_reply_to')"}"
       type="text" name="core_email_reply_to"
       value="{if="isset($settings->core_email_reply_to)"}{$settings->core_email_reply_to}{else}{function="set_value('core_email_reply_to')"}{/if}" />

<label for="core_email_reply_to_name" >Reply-to name:</label>
<input class="{function="$CI->core_module_library->form_error_class('core_email_reply_to_name')"}"
       type="text" name="core_email_reply_to_name"
       value="{if="isset($settings->core_email_reply_to_name)"}{$settings->core_email_reply_to_name}{else}{function="set_value('core_email_reply_to_name')"}{/if}" />

{function="form_submit('submit', 'Save settings')"}

{function="form_submit('test', 'Send a test email')"}

{function="form_close()"}
