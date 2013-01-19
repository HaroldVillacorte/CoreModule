<?php if ($user->getId()) : ?>
<div class="columns twelve">
  <h5>Welcome <?php echo $user->getFirstName() . ' ' . $user->getLastName(); ?>!</h5>
  <p>User ID: <?php echo $user->getId(); ?></p>
  <p>Username: <?php echo $user->getUsername(); ?></p>
  <p>Email: <?php echo $user->getEmail(); ?></p>
  <p>First name: <?php echo $user->getFirstName(); ?></p>
  <p>Last name: <?php echo $user->getLastName(); ?></p>
  <p>Role: <?php echo $user->getRole(); ?></p>
  <p>Member since: <?php $date_time = $user->getCreated();echo $date_time->format('D M, d Y h:i:s a'); ?></p>
  <p><a class="button" href="<?php echo base_url ();?>user/edit/">Edit</a></p>
</div>
<?php endif; ?>
