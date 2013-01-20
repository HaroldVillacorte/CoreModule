<div class="twelve columns">
  <h5>This is Doctrine 2</h5>
  <div id="ajax-content">
    <p>Render time: <?php echo $elapsed_time;?> | Displaying <?php echo $first
      . ' to ' . $last;?> of <?php echo $count;?> records.  <img id="loading-img"
      src="<?php echo $img_url;?>load.gif" style="display:none;" />
    </p>
    <?php echo $output;?>
    <p><?php echo $pagination_links;?></p>
  </div>
</div>