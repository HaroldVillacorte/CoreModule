<h4>Greetings <?php echo ucfirst($username);?></h4>

<p>A forgotten password login has been generated for your account.</p>

<p>
    Username: <?php echo $username ;?><br/>
    Email: <?php echo $email ;?>
</p>

<p>You may login one time within the next <?php echo $expire_time ;?> with following link: <?php echo $recovery_url_html ;?></p>

<p>
    Regards,<br/>
    <strong><em><?php echo $site_name ;?></em></strong>
</p>

