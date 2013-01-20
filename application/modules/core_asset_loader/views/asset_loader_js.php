<?php if (isset ($scripts) ):?>
  <?php foreach ($scripts as $script):?>
  <script src="<?php echo $js_url . $script;?>"></script>
  <?php endforeach;?>
<?php endif;?>