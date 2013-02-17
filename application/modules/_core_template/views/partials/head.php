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
        <link rel="stylesheet" href="<?php echo $css_url ;?>foundation.min.css">
        <link rel="stylesheet" href="<?php echo $css_url ;?>app.css">

        <!-- Crocery CRUD -->
        <?php if (isset($output->css_files)) : ?>
            <?php foreach ($output->css_files as $file) :?>
                <link type="text/css" rel="stylesheet" href="<?php echo $file ;?>" />
            <?php endforeach ;?>
            <!--
              CI Starter GC CSS.  This file includes overrides to fix some conflicts
              with Grocery crud and Foundation 3
            -->
            <link rel="stylesheet" href="<?php echo $css_url ;?>ci_starter_GC.css">
        <?php endif ;?>

        <!-- Load css files from Asset Loader module -->
        <?php echo Modules::run('_core_asset_loader/stylesheets') ;?>
        <script src="<?php echo $js_url ;?>modernizr.foundation.js"></script>
    </head>
