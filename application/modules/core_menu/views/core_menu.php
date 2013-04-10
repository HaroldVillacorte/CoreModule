<?php if (file_exists($this->core_raintpl_library->menu_template_directory . $template . '.php')) :?>
    <?php include $this->core_raintpl_library->menu_template_directory . $template . '.php' ;?>
<?php else :?>
    <?php foreach ($links as $link) :?>
        <li><a <?php echo $link->link ;?> title="<?php echo $link->title ;?>"><?php echo $link->text ;?></a></li>
    <?php endforeach; ?>
<?php endif ;?>