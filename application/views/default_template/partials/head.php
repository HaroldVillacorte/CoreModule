<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8" />
        <!-- Set the viewport width to device width for mobile -->
        <meta name="viewport" content="width=device-width" />
        <title><?php echo $site_name ;?></title>
        <meta name="description" content="<?php echo $site_description ;?>" />
        <!-- Included CSS Files (Compressed) -->
        <link rel="stylesheet" href="<?php echo $asset ;?>stylesheets/foundation.min.css">
        <link rel="stylesheet" href="<?php echo $asset ;?>stylesheets/app.css">

        <!-- Load css files from controllers -->
        <?php if (isset($stylesheets)) :?>
            <?php foreach ($stylesheets as $stylesheet) :?>
                <link href="<?php echo $asset ;?>stylesheets/<?php echo $stylesheet ;?>" rel="stylesheet" />
            <?php endforeach ;?>
        <?php endif ;?>
        <script src="<?php echo $asset ;?>javascripts/modernizr.foundation.js"></script>
    </head>
