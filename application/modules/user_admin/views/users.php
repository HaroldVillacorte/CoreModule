<h5>Users</h5>
<div id="ajax-content">
    <p>Displaying <?php echo $first . ' to ' . $last ;?> of <?php echo $count ;?> records.  <img id="loading-img"
        src="<?php echo $img_url ;?>load.gif" style="display:none;" />
    </p>
    <?php echo $output ;?>
    <p><?php echo $pagination_links ;?></p>
</div>