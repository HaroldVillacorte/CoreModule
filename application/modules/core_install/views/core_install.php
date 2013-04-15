<h3>CoreModule install</h3>

<?php if (isset($directories)) :?>

    <?php $success_array = array() ;?>

    <?php foreach ($directories as $directory) :?>

        <?php if (!is_really_writable($directory)) :?>

            <p class="red">
                The directory <span class="black">[<?php echo $directory ;?>]</span> is not writable.  CHMOD directory to 777 &#x2717
            </p>

        <?php else :?>

            <p class="green">
                The directory <span class="black">[<?php echo $directory ;?>']</span> is writable. &#x2713
            </p>

            <?php $success_array[] = $directory ;?>

        <?php endif ;?>

    <?php endforeach ;?>

<?php endif ;?>

<?php if (!$db_connect) :?>

    <p class="red">
        The database config is not configured correctly.  Confugure database.php in the config folder. &#x2717
    </p>

<?php else :?>

    <p class="green">
        The database config is configured correctly. &#x2713
    </p>

<?php endif ;?>

<?php if (count($success_array) == count($directories) && $db_connect && $schema && !$installed) :?>

        <hr/>

        <h4>Install the schema file?  This cannot be undone!</h4>

        <?php echo form_open(current_url()) ;?>

        <input type="hidden" name="match" value="yes" />

        <label for="yes">Please type "yes" in the box to install:</label>
        <input type="text" name="yes" value="" />

        <input type="submit" value="Install" name="submit" />

        <?php echo form_close() ;?>

        <div class="red"><?php echo (validation_errors()) ? validation_errors() : '' ;?></div>

<?php elseif (count($success_array) == count($directories) && $db_connect && $schema && $installed) :?>

        <p class="green">The shema file is installed. &#x2713</p>

<?php endif ;?>

<?php if (!$session_db && $installed) :?>

    <hr/>

    <p class="red">
        Sessions are not configured to use the database. Set "sess_use_database" to TRUE in config.php in the config folder. &#x2717
    </p>

<?php elseif ($session_db && $installed) :?>

    <p class="green">
        Sessions are configured to use the database. &#x2713
    </p>

<?php endif ;?>

<?php if (count($success_array) == count($directories) && $db_connect && $schema && $installed && $session_db && !$custom_routing) :?>

    <p class="red">
        CorModule uses custom routing. Uncomment the custom code in routes.php in config folder. &#x2717
    </p>

<?php elseif (count($success_array) == count($directories) && $db_connect && $schema && $installed && $session_db && $custom_routing) :?>

    <p class="green">
        Custom routing is enabled. &#x2713
    </p>

<?php endif ;?>

<?php if (count($success_array) == count($directories) && $db_connect && $schema && $installed && $session_db && $custom_routing) :?>

    <hr />

    <h1>Welcome!  You have successfully installed CoreModule.</h1>

    <p>It is vitally important now that there is no public access to this install module.</p>
    <p>The module is located at <span class="red">[application/modules/core_install]</span>.  Either delete it or restrict access in your own way.</p>
    <p>You may now visit your new application at <a href="<?php echo base_url() ;?>">Home Page</a>.</p>
    <p>There are three accounts pre-installed:</p>
    <table border="1" cellpadding="10">
        <thead>
            <th>Username</th>
            <th>Password</th>
        </thead>
        <tbody>
            <tr>
                <td>user</td>
                <td>user</td>
            </tr>
            <tr>
                <td>admin</td>
                <td>admin</td>
            </tr>
            <tr>
                <td>super_user</td>
                <td>super_user</td>
            </tr>
        </tbody>
    </table>
    <p>Just login and have at it.</p>

<?php endif ;?>

<style>
    .red {
        color:red;
    }
    .green {
        color:green;
    }
    .black {
        color:black;
    }
</style>
