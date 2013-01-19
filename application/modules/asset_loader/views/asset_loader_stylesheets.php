<?php if (isset ($stylesheets) ):?>
  <?php foreach ($stylesheets as $style):?>
  <link href="<?php echo $css_url . $style;?>" rel="stylesheet" />
  <?php endforeach;?>
<?php endif;?>