{if="$user->id"}
    <h5>Welcome {function="ucfirst($user->username)"}!</h5>
    <p>User ID: {$user->id}</p>
    <p>Username: {$user->username}</p>
    <p>Email: {$user->email}</p>
    <p>Role: {$user->role}</p>
    <p>Member since: {function="unix_to_human($user->created)"}</p>
    <p><a class="button" href="{function="base_url()"}user/edit/">Edit</a></p>
{/if}
