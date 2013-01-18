<!-- Header and Nav -->
<div class="row">
  <div class="three columns">
    <h1>
      <img src="http://placehold.it/400x100&text=<?php echo $site_name; ?>" />
    </h1>
  </div>
  <div class="nine columns">
    <ul class="nav-bar right">
      <li><a href="<?php echo base_url () ?>">Slider</a></li>
      <li><a href="<?php echo base_url () ?>default_controller/columns/one_column">One column</a></li>
      <li><a href="<?php echo base_url () ?>default_controller/columns/two_column">Two column</a></li>
      <li><a href="<?php echo base_url () ?>default_controller/columns/three_column">Three column</a></li>
      <li><a href="<?php echo base_url () ?>crud">Crud</a></li>
      <!-- Login links -->
      <?php if ($this->session->userdata ('user_id')) : ?>
      <li><a href="<?php echo base_url () ?>user/profile/">Profile</a></li>
      <?php endif; ?>
      <?php
      $user_action = 'login';
      if ($this->session->userdata ('user_id')) {
        $user_action = 'logout';
      }
      ?>
      <li><a href="<?php echo base_url () ?>user/<?php echo $user_action; ?>"><?php echo $user_action; ?></a></li>
    </ul>
  </div>
</div>
<!-- End Header and Nav -->
