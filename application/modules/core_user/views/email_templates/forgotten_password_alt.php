<?php
echo 'Grretings .' . ucfirst($username) . "\r\n";
echo "\r\n";
echo 'A forgotten password login has been generated for your account.' . "\r\n";
echo "\r\n";
echo 'Username: ' . $username  . "\r\n";
echo 'Email: ' .  $email . "\r\n";
echo "\r\n";
echo 'You may login one time within the next '. $expire_time .' with following link: ' . $recovery_url_text . "\r\n";
echo "\r\n";
echo 'Once activated you can log into our site at ' . $login_url_text . "\r\n";
echo "\r\n";
echo 'Regards,'. "\r\n";
echo $site_name ;
