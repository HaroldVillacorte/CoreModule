<div class="twelve columns">
  <h5>This is Grocery CRUD.</h5>
  <div>
    <p>Render time: <?php if(isset($elapsed_time)) echo $elapsed_time ;?></p>
    <?php if (isset ($output->output)) : ?>
    <?php echo $output->output; ?>
    <?php endif; ?>
  </div>
</div>
