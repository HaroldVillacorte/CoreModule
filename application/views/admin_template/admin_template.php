<!DOCTYPE html>
<?php include 'partials/head.php' ;?>
<body>
    <?php include 'partials/header_nav.php' ;?>

    <!-- Content -->
    <div class="row">
        <section class="twelve columns">

            <div id="messages">
                <?php echo Modules::run('core_messages/load') ;?>
            </div>

            <?php echo (isset($body)) ? $body : '' ;?>

        </section>
    </div>

    <?php include 'partials/footer.php' ;?>
    <?php include 'partials/scripts.php' ;?>
</body>
</html>
