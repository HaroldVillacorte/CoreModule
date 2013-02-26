<h5>{function="ucfirst($user->username)"}, are you sure you want to delete your account?</h5>
<p>
    {function="form_open($CI->config->item('user_delete_uri'))"}
    {function="form_submit('delete', 'Yes, I hate you!')"}
    {function="form_close()"}
    <a href="{function="base_url()"}user/edit/">Cancel</a>
</p>
