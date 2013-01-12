<?php $class = '';?>
<?php $class = (validation_errors()) ? 'error' : '';?>
<div class="columns twelve">

  <!-- demonstration accounts -->
  <p><strong><em>demo/demo (authenticated) or admin/admin (admin)</em></strong></p>
  
  <h4>User Login</h4>
  <?php if (validation_errors()):?>
    <div class="ten columns centered alert-box secondary">
      <?php echo validation_errors();?>
      <a href="" class="close">&times;</a>
    </div>
  <?php endif;?>

  <?php echo form_open('user/login');?>
    <label for="username">Username:</label>
    <input class="<?php echo $class;?>" type="text" name="username" value="" />
    <label for="password">Password:</label>
    <input class="<?php echo $class;?>" type="password" name="password" value="" autocomplete="off" />
    <?php echo form_submit('submit', 'Login');?>
    <a href="<?php echo base_url() . 'user/crud';?>">Create account</a>
  <?php echo form_close();?>

</div>