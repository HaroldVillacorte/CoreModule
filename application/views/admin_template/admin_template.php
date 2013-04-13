<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <?php include 'partials/head.php' ;?>
</head>

<body>

    <div>
        <?php include 'partials/header.php' ;?>
    </div>

    <div class="row">

        <section id="messages">
            <?php echo Modules::run('core_messages/load') ;?>
        </section>

        <section class="twelve columns" id="main-content">
            <?php echo (isset($body)) ? $body : '' ;?>
        </section>

    </div>

    <footer class="row">
        <div class="twelve columns">
            <?php include 'partials/footer.php' ;?>
        </div>
    </footer>

    <?php include 'partials/scripts.php' ;?>
</body>
</html>
