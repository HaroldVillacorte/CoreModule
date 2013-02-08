<?php if ($user->id) : ?>
<div class="columns twelve">
    <h5>Welcome <?php echo ucfirst($user->username) ;?>!</h5>
  <p>User ID: <?php echo $user->id ;?></p>
  <p>Username: <?php echo $user->username ;?></p>
  <p>Email: <?php echo $user->email ;?></p>
  <p>Role: <?php echo $user->role ;?></p>
  <p>Member since: <?php echo unix_to_human($user->created) ;?></p>
  <p><a class="button" href="<?php echo base_url () ;?>user/edit/">Edit</a></p>
</div>
<?php endif ;?>
