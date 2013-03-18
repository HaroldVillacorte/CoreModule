<?php foreach ($links as $link) :?>
    <?php if (!$link->external) :?>
        <li><a href="<?php echo base_url() . $link->link ;?>" title="<?php echo $link->title ;?>"><?php echo $link->text ;?></a></li>
        <li class="divider hide-for-small"></li>
    <?php else :?>
        <li><a href="http://<?php echo $link->link ;?>" title="<?php echo $link->title ;?>"><?php echo $link->text ;?></a></li>
        <li class="divider hide-for-small"></li>
    <?php endif ;?>
<?php endforeach; ?>