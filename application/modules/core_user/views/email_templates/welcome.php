<h4>Welcome <?php echo ucfirst($username);?></h4>

<p>A new account has been generated for you with the following settings.</p>

<p>
    Username: <?php echo $username ;?><br/>
    Email: <?php echo $email ;?>
</p>

<p>Visit this link within <?php echo $expire_time ;?> to activate your account: <?php echo $activation_url_html ;?></p>

<p>Once activated you can log into our site at <?php echo $login_url_html ;?>.</p>

<p>
    Regards,<br/>
    <strong><em><?php echo $site_name ;?></em></strong>
</p>

