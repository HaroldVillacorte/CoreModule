<meta charset="utf-8" />
<!-- Set the viewport width to device width for mobile -->
<meta name="viewport" content="width=device-width" />
<title><?php echo $site_name ;?></title>
<meta name="description" content="{$site_description}" />
<!-- Included CSS Files (Compressed) -->
<link rel="stylesheet" href="<?php echo $asset ;?>stylesheets/foundation.min.css">
<link rel="stylesheet" href="<?php echo $asset ;?>stylesheets/app.css">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">

<!-- Load css files from controllers -->
<?php if (isset($stylesheets)) :?>
    <?php foreach ($stylesheets as $stylesheet) :?>
        <link href="<?php echo $asset ;?>stylesheets/<?php echo $stylesheet ;?>" rel="stylesheet" />
    <?php endforeach ;?>
<?php endif ;?>
<script src="<?php echo $asset ;?>javascripts/modernizr.foundation.js"></script>
