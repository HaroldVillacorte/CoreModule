<?php if ($user->id) : ?>
<div class="columns twelve">
  <h5>Welcome <?php echo $user->first_name . ' ' . $user->last_name; ?>!</h5>
  <p>User ID: <?php echo $user->id; ?></p>
  <p>Username: <?php echo $user->username; ?></p>
  <p>Email: <?php echo $user->email; ?></p>
  <p>First name: <?php echo $user->first_name; ?></p>
  <p>Last name: <?php echo $user->last_name; ?></p>
  <p>Role: <?php echo $user->role; ?></p>
  <p>Member since: <?php echo unix_to_human($user->created); ?></p>
  <p><a class="button" href="<?php echo base_url ();?>user/edit/">Edit</a></p>
</div>
<?php endif; ?>
