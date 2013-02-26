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
        <title>{$site_name}</title>
        <meta name="description" content="{$site_description}" />
        <!-- Included CSS Files (Compressed) -->
        <link rel="stylesheet" href="{$css_url}foundation.min.css">
        <link rel="stylesheet" href="{$css_url}app.css">

        <!-- Load css files from Asset Loader module -->
        {if="$stylesheets"}
            {loop="$stylesheets"}
                <link href="{$css_url}{$value}" rel="stylesheet" />
            {/loop}
        {/if}
        <script src="{$js_url}modernizr.foundation.js"></script>
    </head>
