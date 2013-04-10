<!DOCTYPE html>
<?php include 'partials/head.php' ;?>
<body>
    <?php include 'partials/header_nav.php' ;?>

    <!-- Content -->
    <div class="row">
        <?php echo Modules::run('core_messages/load') ;?>
        <?php echo $body ;?>
    </div>
    <?php include 'partials/footer.php' ;?>
    <?php include 'partials/scripts.php' ;?>
</body>
</html>
