<div class="twelve columns">
  <h5><?php echo $user->getFirstName() . ' ' . $user->getLastName() ?>, are you sure you want to delete your account?</h5>
  <p>
    <?php echo form_open ('user/delete'); ?>
    <?php echo form_hidden ('id', $user->getId()); ?>
    <?php echo form_submit ('delete', 'Yes, I hate you!') ?>
    <?php echo form_close (); ?>
  </p>
</div>
