<?php
echo 'Welcome .' . ucfirst($username) . "\r\n";
echo "\r\n";
echo 'A new account has been generated for you with the following settings.' . "\r\n";
echo "\r\n";
echo 'Username: ' . $username  . "\r\n";
echo 'Email: ' .  $email . "\r\n";
echo "\r\n";
echo 'Visit this link within 24 hours to activate your account: ' . $activation_url_text . "\r\n";
echo "\r\n";
echo 'Once activated you can log into our site at ' . $login_url_text . "\r\n";
echo "\r\n";
echo 'Regards,'. "\r\n";
echo $site_name ;
